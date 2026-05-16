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
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class ParticipantImport implements ToCollection, WithHeadingRow, SkipsEmptyRows
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

    public function collection(Collection $rows)
    {
        if ($rows->isEmpty()) {
            throw new \Exception("File Excel kosong atau tidak memiliki data di bawah header.");
        }

        // 1. Create or Update the Shared User Account
        $user = User::where('email', $this->orderEmail)->first();
        if (!$user) {
            $randomPassword = Str::random(8);
            $user = User::create([
                'name' => 'Imported Group (' . $this->orderEmail . ')',
                'email' => $this->orderEmail,
                'username' => $this->orderEmail,
                'password' => Hash::make($randomPassword),
                'role' => 'participant',
            ]);
        }

        foreach ($rows as $index => $row) {
            $lineNum = $index + 2; 

            // Debugging helper: if NIK is empty, maybe header mapping failed
            $name = $row['name'] ?? null;
            $nik = $row['nik'] ?? null;

            if (empty($name) || empty($nik)) {
                // If the first row fails, it's likely a header mismatch
                $availableHeaders = implode(', ', array_keys($row->toArray()));
                throw new \Exception("Kolom 'Name' atau 'NIK' tidak ditemukan atau kosong pada baris $lineNum. Kolom yang terdeteksi: [$availableHeaders]. Pastikan header sesuai dengan template.");
            }

            DB::transaction(function () use ($row, $user, $lineNum) {
                // 2. Find Ticket
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

                // 3. Create or Update Participant
                $participant = Participant::updateOrCreate(
                    ['nik' => trim($row['nik'])],
                    [
                        'name' => $row['name'],
                        'email' => $this->orderEmail,
                        'phone_number' => $row['phone_number'] ?? ($row['phone'] ?? '-'),
                        'jersey_size' => $row['jersey_size'] ?? ($row['ukuran_jersey'] ?? '-'),
                        'date_birth' => '-',
                        'sex' => 'male',
                        'blood_type' => '-',
                        'nationality' => 'WNI',
                        'address' => '-',
                        'emergency_contact_name' => '-',
                        'emergency_contact_phone_number' => '-',
                        'emergency_contact_relationship' => '-',
                        'user_id' => $user->id,
                    ]
                );

                // 4. Check for existing entry to avoid duplicates
                $existingEntry = RaceEntry::where('participant_id', $participant->id)
                    ->where('ticket_id', $ticket->id)
                    ->whereIn('status', ['paid', 'pending'])
                    ->first();

                if (!$existingEntry) {
                    // 5. Create Order
                    $orderCode = 'IMP-' . strtoupper(Str::random(8));
                    $order = Order::create([
                        'participant_id' => $participant->id,
                        'order_code' => $orderCode,
                        'email' => $this->orderEmail,
                        'total_price' => 0, 
                        'status' => 'paid',
                        'payment_method' => 'import',
                    ]);

                    // 6. Create Race Entry
                    RaceEntry::create([
                        'participant_id' => $participant->id,
                        'ticket_id' => $ticket->id,
                        'order_id' => $order->id,
                        'status' => 'paid',
                    ]);
                }
            });
        }
    }
}
