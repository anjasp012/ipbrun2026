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
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class ParticipantImport implements ToCollection, WithHeadingRow
{
    protected $periodId;
    protected $ticketType;
    protected $orderEmail;

    public function __construct($periodId, $ticketType, $orderEmail)
    {
        $this->periodId = $periodId;
        $this->ticketType = $ticketType;
        $this->orderEmail = $orderEmail; // This is now the email for all participants and the user login
    }

    public function collection(Collection $rows)
    {
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

        foreach ($rows as $row) {
            if (empty($row['name']) || empty($row['nik'])) {
                continue;
            }

            DB::transaction(function () use ($row, $user) {
                // 2. Find Ticket
                $raceCategory = $row['race_category'] ?? ($row['category'] ?? '');
                $category = Category::where('name', 'like', "%$raceCategory%")->first();
                
                if (!$category) {
                    return; // Skip if category not found
                }

                $ticket = Ticket::where('period_id', $this->periodId)
                    ->where('type', $this->ticketType)
                    ->where('category_id', $category->id)
                    ->first();

                if (!$ticket) {
                    return; // Skip if ticket not found for this period and category
                }

                // 3. Create or Update Participant (Using NIK as identifier since Email is shared)
                $participant = Participant::updateOrCreate(
                    ['nik' => trim($row['nik'])],
                    [
                        'name' => $row['name'],
                        'email' => $this->orderEmail,
                        'phone_number' => $row['phone_number'] ?? '-',
                        'jersey_size' => $row['jersey_size'] ?? '-',
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
