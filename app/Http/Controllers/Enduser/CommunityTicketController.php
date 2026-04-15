<?php

namespace App\Http\Controllers\Enduser;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Ticket;
use App\Models\Participant;
use App\Models\Setting;
use App\Models\RaceEntry;
use App\Models\Order;
use App\Models\Voucher;
use App\Models\VoucherUsage;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Midtrans\Config;
use Midtrans\Snap;

class CommunityTicketController extends Controller
{
    public function __construct()
    {
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized = config('midtrans.is_sanitized');
        Config::$is3ds = config('midtrans.is_3ds');
    }

    public function home()
    {
        $isMaintenance = Setting::where('key', 'is_running')->first()?->value !== '1';
        $ticketSaleStart = Setting::where('key', 'ticket_sale_start')->first()?->value;
        $ticketSaleStartValue = $ticketSaleStart ? \Carbon\Carbon::parse($ticketSaleStart, 'Asia/Jakarta') : null;

        $tickets_ipb = Ticket::where('type', 'ipb')->whereHas('period', function ($query) {
            $query->where('is_active', true);
        })->with(['category', 'period'])->withCount(['raceEntries as participants_count' => function ($query) {
            $query->whereIn('status', ['pending', 'paid']);
        }])->get();

        $tickets_public = Ticket::where('type', 'umum')->whereHas('period', function ($query) {
            $query->where('is_active', true);
        })->with(['category', 'period'])->withCount(['raceEntries as participants_count' => function ($query) {
            $query->whereIn('status', ['pending', 'paid']);
        }])->get();

        return view('pages.enduser.komunitas.index', compact('tickets_ipb', 'tickets_public', 'isMaintenance', 'ticketSaleStart', 'ticketSaleStartValue'));
    }

    public function checkout(Ticket $ticket)
    {
        if (!$ticket->period || !$ticket->period->is_active) {
            return redirect('/komunitas')->with('error', 'Maaf, periode pendaftaran untuk tiket ini tidak aktif.');
        }

        $usedQty = RaceEntry::where('ticket_id', $ticket->id)
            ->whereIn('status', ['pending', 'paid'])->count();

        if ($usedQty >= $ticket->qty) {
            return redirect('/komunitas')->with('error', 'Maaf, tiket untuk kategori ini baru saja habis terjual.');
        }

        // Find the pair ticket
        $categoryName = strtoupper($ticket->category->name ?? '');
        $pairTarget = '';
        if (str_contains($categoryName, '5K') || str_contains($categoryName, '42K')) {
            $pairTarget = '10K';
        } elseif (str_contains($categoryName, '10K') || str_contains($categoryName, '21K')) {
            $pairTarget = '5K';
        }

        $pairTicket = null;
        if ($pairTarget) {
            $pairTicket = Ticket::where('period_id', $ticket->period_id)
                ->where('type', $ticket->type)
                ->whereHas('category', function ($q) use ($pairTarget) {
                    $q->where('name', 'LIKE', "%$pairTarget%");
                })
                ->where('id', '!=', $ticket->id)
                ->first();
        }

        return view('pages.enduser.komunitas.checkout', compact('ticket', 'pairTicket'));
    }

    public function checkVoucher(Request $request)
    {
        $nik = $request->nik;
        $code = $request->code;
        
        $voucher = Voucher::where('code', $code)->first();

        if (!$voucher) {
            return response()->json(['valid' => false, 'message' => 'Kode voucher tidak valid.']);
        }

        if (!$voucher->isAvailable()) {
            return response()->json(['valid' => false, 'message' => 'Maaf, kuota pemakaian voucher ini sudah habis.']);
        }

        // Check if this NIK has already used this voucher
        if ($nik) {
            $participant = Participant::where('nik', $nik)->first();
            if ($participant) {
                $alreadyUsed = VoucherUsage::where('voucher_id', $voucher->id)
                    ->where('participant_id', $participant->id)
                    ->exists();

                if ($alreadyUsed) {
                    return response()->json(['valid' => false, 'message' => 'Voucher ini sudah pernah Anda gunakan.']);
                }
            }
        }

        $discount = $voucher->calculateDiscount($request->price);

        return response()->json([
            'valid' => true,
            'discount' => $discount,
            'type' => $voucher->type,
            'value' => $voucher->value,
            'code' => $voucher->code,
            'assigned' => false
        ]);
    }

    public function register(Request $request)
    {
        $ticket = Ticket::findOrFail($request->ticket_id);
        
        $validated = $request->validate([
            'ticket_id' => 'required',
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'phone_number' => 'required|numeric',
            'nik' => 'required|numeric|digits:16',
            'date_birth' => 'required',
            'sex' => 'required|in:male,female',
            'blood_type' => 'required|in:A,A+,A-,B,B+,B-,AB,AB+,AB-,O,O+,O-,-',
            'jersey_size' => 'required|in:XS,S,M,L,XL,2XL,3XL,4XL,5XL',
            'nationality' => 'required',
            'address' => 'required|string',
            'emergency_contact_name' => 'required|string',
            'emergency_contact_phone_number' => 'required|numeric',
            'emergency_contact_relationship' => 'required|string',
            'running_community' => 'nullable|string',
            'shuttle_bus' => 'nullable|string',
            'medical_condition' => 'nullable|string',
            'voucher_code' => 'nullable|string',
        ]);

        $nik = $request->nik;
        $email = $request->email;

        // Identity check: One NIK cannot buy SAME ticket category twice if active
        $duplicateCheck = RaceEntry::whereHas('participant', function ($q) use ($nik) {
            $q->where('nik', $nik);
        })
        ->where('ticket_id', $ticket->id)
        ->whereIn('status', ['pending', 'paid'])
        ->first();

        if ($duplicateCheck) {
            return back()->withInput()->withErrors([
                'nik' => "Peserta dengan NIK ini sudah terdaftar atau memiliki pesanan tertunda untuk kategori ({$ticket->category->name}).",
            ]);
        }

        return DB::transaction(function () use ($request, $validated, $ticket, $nik, $email) {
            $lockedTicket = Ticket::where('id', $ticket->id)->lockForUpdate()->first();
            $usedQty = RaceEntry::where('ticket_id', $lockedTicket->id)
                ->whereIn('status', ['pending', 'paid'])->count();

            if ($usedQty >= $lockedTicket->qty) {
                return redirect('/komunitas')->with('error', 'Maaf, tiket untuk kategori ini baru saja habis terjual.');
            }

            // Voucher Validation
            $voucher = null;
            $discountAmount = 0;
            if ($request->voucher_code) {
                $voucher = Voucher::where('code', $request->voucher_code)->first();
                
                if ($voucher) {
                    // Check global limit
                    if (!$voucher->isAvailable()) {
                        throw new \Exception('Maaf, kuota voucher ini sudah habis.');
                    }

                    // 3. Check per-user limit
                    $participant = Participant::where('nik', $nik)->first();
                    if ($participant) {
                        $alreadyUsed = VoucherUsage::where('voucher_id', $voucher->id)
                            ->where('participant_id', $participant->id)
                            ->exists();
                        
                        if ($alreadyUsed) {
                            throw new \Exception('Voucher ini sudah pernah Anda gunakan.');
                        }
                    }

                    $discountAmount = $voucher->calculateDiscount($ticket->price);
                }
            }

            $adminFee = 4500;
            $totalPrice = ($ticket->price - $discountAmount) + $adminFee;

            // Handle Second Ticket Price
            $second_ticket_id = null;
            if ($request->other_race_interest) {
                // Find matching pair ticket
                $categoryName = strtoupper($ticket->category->name ?? '');
                $pairTarget = (str_contains($categoryName, '5K') || str_contains($categoryName, '42K')) ? '10K' : '5K';
                
                $pairTicket = Ticket::where('period_id', $ticket->period_id)
                    ->where('type', $ticket->type)
                    ->whereHas('category', function ($q) use ($pairTarget) {
                        $q->where('name', 'LIKE', "%$pairTarget%");
                    })
                    ->where('id', '!=', $ticket->id)
                    ->first();
                
                if ($pairTicket) {
                    $second_ticket_id = $pairTicket->id;
                    $totalPrice += $pairTicket->price;
                }
            }

            // 3. Find or Create Participant Profile (One NIK = One Participant)
            $participant = Participant::updateOrCreate(
                ['nik' => $nik],
                \Illuminate\Support\Arr::except($validated, ['ticket_id', 'voucher_code', 'other_race_interest'])
            );

            // 5. Create Order
            $orderCode = 'IPBR26-' . strtoupper(Str::random(6));
            $order = Order::create([
                'participant_id' => $participant->id,
                'order_code' => $orderCode,
                'status' => 'pending',
                'admin_fee' => $adminFee,
                'total_price' => $totalPrice,
            ]);

            // 6. Record Voucher Usage
            if ($voucher) {
                VoucherUsage::create([
                    'voucher_id' => $voucher->id,
                    'participant_id' => $participant->id,
                    'order_id' => $order->id,
                ]);
            }

            // Create Race Entry
            $participant->raceEntries()->create([
                'ticket_id' => $ticket->id,
                'order_id' => $order->id,
                'status' => 'pending',
            ]);

            // Create Second Race Entry if needed
            if ($second_ticket_id) {
                $participant->raceEntries()->create([
                    'ticket_id' => $second_ticket_id,
                    'order_id' => $order->id,
                    'status' => 'pending',
                ]);
            }

            // Community User logic: Credentials share NIK
            $user = User::updateOrCreate(
                ['username' => $nik],
                [
                    'name' => $participant->name,
                    'email' => $participant->email, // Store real email but uniqueness is no longer enforced in DB
                    'password' => Hash::make($nik),
                    'role' => 'participant'
                ]
            );

            $participant->update(['user_id' => $user->id]);

            return $this->createMidtransTransaction($order);
        });
    }

    private function createMidtransTransaction(Order $order)
    {
        $participant = $order->participant;
        $itemDetails = [];

        foreach ($order->raceEntries as $entry) {
            $itemDetails[] = [
                'id' => $entry->ticket_id,
                'price' => $entry->ticket->price,
                'quantity' => 1,
                'name' => 'Tiket IPB Run 2026 - ' . $entry->ticket->category->name
            ];
        }

        if ($order->discount_amount > 0) {
            $itemDetails[] = [
                'id' => 'VOUCHER_DISCOUNT',
                'price' => -$order->discount_amount,
                'quantity' => 1,
                'name' => 'Potongan Voucher (' . $order->voucher_code . ')'
            ];
        }

        $itemDetails[] = ['id' => 'ADMIN_FEE', 'price' => $order->admin_fee, 'quantity' => 1, 'name' => 'Biaya Layanan'];

        $params = [
            'transaction_details' => ['order_id' => $order->order_code, 'gross_amount' => $order->total_price],
            'customer_details' => ['first_name' => $participant->name, 'email' => $participant->email, 'phone' => $participant->phone_number],
            'item_details' => $itemDetails,
            'expiry' => [
                'unit' => 'minute',
                'duration' => 10
            ],
            'callbacks' => [
                'finish' => route('payment.finish'),
                'unfinish' => route('payment.finish'),
                'error' => route('payment.finish')
            ]
        ];

        try {
            $snapResponse = Snap::createTransaction($params);
            $order->update([
                'snap_token' => $snapResponse->token,
                'payment_url' => $snapResponse->redirect_url
            ]);

            return redirect($snapResponse->redirect_url);
        } catch (\Exception $e) {
            throw new \Exception('Midtrans integration failed: ' . $e->getMessage());
        }
    }
}
