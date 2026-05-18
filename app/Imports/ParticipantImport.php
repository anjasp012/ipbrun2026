<?php

namespace App\Imports;

use App\Models\Participant;
use App\Models\Order;
use App\Models\RaceEntry;
use App\Models\Ticket;
use App\Models\Category;
use App\Models\User;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use App\Jobs\SendQueuedEmail;
use App\Mail\ParticipantPaidNotification;

class ParticipantImport implements ToCollection, WithHeadingRow, SkipsEmptyRows, WithColumnFormatting
{
    protected $periodId;
    protected $ticketType;
    protected $orderEmail;

    public function __construct($periodId, $ticketType, $orderEmail)
    {
        $this->periodId = $periodId;
        $this->ticketType = $ticketType;
        $this->orderEmail = $orderEmail;
    }

    /**
     * Force NIK column (C = column 3) to be read as text to prevent scientific notation.
     * Adjust column letter if your template column order changes.
     */
    public function columnFormats(): array
    {
        return [
            'B' => NumberFormat::FORMAT_TEXT, // NIK column
        ];
    }

    /**
     * Safely parse NIK regardless of how Excel reads it.
     * - Strips leading apostrophe (') that users add to force text in Excel.
     * - Handles scientific notation (e.g. 3.20132E+15).
     */
    private function parseNik(mixed $value): string
    {
        if (is_null($value) || $value === '') return '';

        // If it's still a float (scientific notation), convert to full integer string
        if (is_float($value) || is_int($value)) {
            return number_format($value, 0, '.', '');
        }

        // Strip leading apostrophe (Excel text prefix trick: '3201320012345678)
        return ltrim(trim((string)$value), "'");
    }

    /**
     * Safely parse phone number — strips leading apostrophe.
     */
    private function parsePhone(mixed $value): string
    {
        if (is_null($value) || $value === '') return '-';
        if (is_float($value) || is_int($value)) {
            return (string)(int)$value;
        }
        return ltrim(trim((string)$value), "'");
    }

    public function collection(Collection $rows)
    {
        if ($rows->isEmpty()) {
            throw new \Exception("File Excel kosong atau tidak memiliki data di bawah header.");
        }

        // Check for duplicate names within the Excel file itself
        $namesInExcel = $rows->pluck('name')->filter()->map(fn($n) => trim($n))->toArray();
        if (count($namesInExcel) !== count(array_unique($namesInExcel))) {
            $duplicates = array_unique(array_diff_assoc($namesInExcel, array_unique($namesInExcel)));
            throw new \Exception("Ditemukan nama duplikat di dalam file Excel: " . implode(', ', $duplicates));
        }

        foreach ($rows as $index => $row) {
            $lineNum = $index + 2;

            $name = $row['name'] ?? null;
            $nik  = $row['nik']  ?? null;

            if (empty($name) || empty($nik)) {
                $availableHeaders = implode(', ', array_keys($row->toArray()));
                throw new \Exception("Kolom 'Name' atau 'NIK' tidak ditemukan atau kosong pada baris $lineNum. Kolom yang terdeteksi: [$availableHeaders]. Pastikan header sesuai dengan template.");
            }

            DB::transaction(function () use ($row, $lineNum) {
                // 1. Find Ticket
                $raceCategory = trim($row['race_category'] ?? ($row['category'] ?? ''));
                if (!$raceCategory) {
                    throw new \Exception("Kolom 'Race Category' kosong pada baris $lineNum.");
                }

                $category = Category::where('name', 'like', "%$raceCategory%")->first();
                if (!$category) {
                    throw new \Exception("Kategori '$raceCategory' tidak ditemukan di sistem pada baris $lineNum. Pilihan: 5K, 10K, HM, dsb.");
                }

                $ticket = Ticket::where('period_id', $this->periodId)
                    ->where('type', $this->ticketType)
                    ->where('category_id', $category->id)
                    ->first();

                if (!$ticket) {
                    $typeName = $this->ticketType === 'ipb' ? 'IPB Family' : 'Public (Umum)';
                    throw new \Exception("Tiket untuk kategori '$raceCategory' dengan tipe '$typeName' tidak tersedia pada periode ini (Baris $lineNum).");
                }

                // 2. Create or Update Participant (Name as unique key)
                $participant = Participant::updateOrCreate(
                    ['name' => trim($row['name'])],
                    [
                        'email'                            => $this->orderEmail,
                        'nik'                              => $this->parseNik($row['nik'] ?? ''),
                        'phone_number'                     => $this->parsePhone($row['phone_number'] ?? ($row['phone'] ?? null)),
                        'jersey_size'                      => $row['jersey_size'] ?? ($row['ukuran_jersey'] ?? '-'),
                        'blood_type'                       => $row['blood_type'] ?? ($row['golongan_darah'] ?? '-'),
                        'date_birth'                       => '-',
                        'sex'                              => '-',
                        'nationality'                      => '-',
                        'address'                          => '-',
                        'emergency_contact_name'           => '-',
                        'emergency_contact_phone_number'   => '-',
                        'emergency_contact_relationship'   => '-',
                    ]
                );

                // 3. Create individual User Account per participant (NIK as username & password)
                $user = User::where('username', $participant->nik)->first();
                if (!$user) {
                    $user = User::create([
                        'name'     => $participant->name,
                        'email'    => $participant->email,
                        'username' => $participant->nik,
                        'password' => Hash::make($participant->nik),
                        'role'     => 'participant',
                    ]);
                } else {
                    $user->update([
                        'name'     => $participant->name,
                        'email'    => $participant->email,
                        'password' => Hash::make($participant->nik),
                    ]);
                }

                // 4. Link Participant → User
                $participant->update(['user_id' => $user->id]);

                // 5. Check for existing entry to avoid duplicates
                $existingEntry = RaceEntry::where('participant_id', $participant->id)
                    ->where('ticket_id', $ticket->id)
                    ->whereIn('status', ['paid', 'pending'])
                    ->first();

                if (!$existingEntry) {
                    // 6. Create Order
                    $orderCode = 'IPBR26-SP-' . strtoupper(Str::random(6));
                    $order = Order::create([
                        'participant_id' => $participant->id,
                        'order_code'     => $orderCode,
                        'total_price'    => 0,
                        'status'         => 'paid',
                    ]);

                    // 7. Create Race Entry
                    RaceEntry::create([
                        'participant_id' => $participant->id,
                        'ticket_id'      => $ticket->id,
                        'order_id'       => $order->id,
                        'status'         => 'paid',
                    ]);

                    // 8. Send email notification (same as Sponsorship flow)
                    try {
                        // Reload order with relations needed by the mailable
                        $order->load(['raceEntries.ticket.category', 'participant']);
                        SendQueuedEmail::dispatch(
                            $participant->email,
                            new ParticipantPaidNotification($participant, $participant->nik, $order, false)
                        );
                    } catch (\Exception $e) {
                        Log::error("Import email failed for {$participant->name}: " . $e->getMessage());
                    }
                }
            });
        }
    }
}
