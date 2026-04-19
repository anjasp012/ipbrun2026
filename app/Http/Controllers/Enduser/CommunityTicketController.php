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

        $activePeriod = \App\Models\Period::where('is_active', true)->first();
        $isPeriodSoldOut = $activePeriod?->is_sold_out ?? false;

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

        return view('pages.enduser.komunitas.index', compact('tickets_ipb', 'tickets_public', 'isMaintenance', 'ticketSaleStart', 'ticketSaleStartValue', 'isPeriodSoldOut', 'activePeriod'));
    }

    public function checkout(Ticket $ticket)
    {
        if (!$ticket->period || !$ticket->period->is_active) {
            return redirect('/komunitas')->with('error', 'Maaf, periode pendaftaran untuk tiket ini tidak aktif.');
        }

        if ($ticket->period->is_sold_out) {
            return redirect('/komunitas')->with('period_sold_out', $ticket->period->name);
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
        
        $voucher = Voucher::where('code', $code)
            ->when($nik, function($q) use ($nik) {
                return $q->orWhere('code', $nik);
            })
            ->first();

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
        $nimRule = ($ticket->type === 'ipb') ? 'required|string|min:6' : 'nullable|string|min:6';

        // Block registration if period is sold out
        if ($ticket->period && $ticket->period->is_sold_out) {
            return redirect('/komunitas')->with('period_sold_out', $ticket->period->name);
        }

        
        $validated = $request->validate([
            'ticket_id' => 'required',
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'email_confirmation' => 'required|same:email',
            'phone_number' => 'required|numeric',
            'nik' => 'required|numeric|digits:16',
            'date_birth' => 'required',
            'sex' => 'required|in:male,female',
            'blood_type' => 'required|in:A,A+,A-,B,B+,B-,AB,AB+,AB-,O,O+,O-,-',
            'jersey_size' => 'required|in:XS,S,M,L,XL,2XL,3XL,4XL,5XL',
            'nim_nrp' => $nimRule,
            'nationality' => 'required',
            'address' => 'required|string',
            'emergency_contact_name' => 'required|string',
            'emergency_contact_phone_number' => 'required|numeric',
            'emergency_contact_relationship' => 'required|string',
            'running_community' => 'nullable|string',
            'previous_events' => 'nullable|string',
            'best_time' => 'nullable|string',
            'shuttle_bus' => 'nullable|string',
            'medical_condition' => 'nullable|string',
            'voucher_code' => 'nullable|string',
            'donation_event' => 'nullable|numeric',
            'donation_scholarship' => 'nullable|numeric',
        ], [
            'required' => ':attribute wajib diisi.',
            'email' => 'Format email tidak valid.',
            'same' => 'Konfirmasi email tidak cocok.',
            'numeric' => ':attribute harus berupa angka.',
            'digits' => ':attribute harus berjumlah :digits digit.',
            'in' => 'Pilihan :attribute tidak valid.',
            'min' => ':attribute minimal :min karakter.',
        ], [
            'name' => 'Nama Lengkap',
            'email' => 'Alamat Email',
            'email_confirmation' => 'Konfirmasi Email',
            'phone_number' => 'Nomor WhatsApp',
            'nik' => 'NIK KTP',
            'date_birth' => 'Tanggal Lahir',
            'sex' => 'Jenis Kelamin',
            'blood_type' => 'Golongan Darah',
            'jersey_size' => 'Ukuran Jersey',
            'address' => 'Alamat Lengkap',
            'emergency_contact_name' => 'Nama Kontak Darurat',
            'emergency_contact_phone_number' => 'Nomor HP Darurat',
            'emergency_contact_relationship' => 'Hubungan Kontak',
            'nim_nrp' => 'NIM / NRP',
        ]);

        $nik = $request->nik;
        $email = $request->email;
        $nim = $request->nim_nrp;

        // Cleanup logic (Copy from TicketController)
        $allIdentities = Participant::where('email', $email)
            ->orWhere('nik', $nik)
            ->when($nim, fn($q) => $q->orWhere('nim_nrp', $nim))
            ->get();

        foreach ($allIdentities as $p) {
            $hasActive = Order::where('participant_id', $p->id)->whereIn('status', ['pending', 'paid'])->exists();
            if (!$hasActive) {
                $p->delete(); 
            }
        }

        // Duplicate check (active orders)
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

            $adminFee = 4500;
            $donationEvent = (int) $request->input('donation_event', 0);
            $donationScholarship = (int) $request->input('donation_scholarship', 0);
            
            $ticketSubtotal = $ticket->price;

            $second_ticket_id = null;
            if ($request->other_race_interest) {
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
                    $ticketSubtotal += $pairTicket->price;
                }
            }

            // Voucher Validation (Only impacts ticket subtotal)
            $voucher = null;
            $discountAmount = 0;
            if ($request->voucher_code || $request->nik) {
                $voucher = Voucher::where('code', $request->voucher_code)
                    ->when($request->nik, function($q) use ($request) {
                        return $q->orWhere('code', $request->nik);
                    })
                    ->first();
                
                if ($voucher) {
                    if (!$voucher->isAvailable()) {
                        throw new \Exception('Maaf, kuota voucher ini sudah habis.');
                    }
                    $discountAmount = $voucher->calculateDiscount($ticketSubtotal);
                }
            }

            $totalPrice = ($ticketSubtotal - $discountAmount) + $adminFee + $donationEvent + $donationScholarship;

            $participant = Participant::updateOrCreate(
                ['nik' => $nik],
                array_merge(
                    \Illuminate\Support\Arr::except($validated, ['email_confirmation', 'ticket_id', 'other_race_interest', 'voucher_code', 'donation_event', 'donation_scholarship']),
                    ['is_community' => true]
                )
            );

            $orderCode = 'IPBR26-' . strtoupper(Str::random(6));
            $order = Order::create([
                'participant_id' => $participant->id,
                'order_code' => $orderCode,
                'status' => 'pending',
                'admin_fee' => $adminFee,
                'donation_event' => $donationEvent,
                'donation_scholarship' => $donationScholarship,
                'total_price' => $totalPrice,
            ]);

            if ($voucher) {
                VoucherUsage::create([
                    'voucher_id' => $voucher->id,
                    'participant_id' => $participant->id,
                    'order_id' => $order->id,
                ]);
            }

            $participant->raceEntries()->create([
                'ticket_id' => $ticket->id,
                'order_id' => $order->id,
                'status' => 'pending',
            ]);

            if ($second_ticket_id) {
                $participant->raceEntries()->create([
                    'ticket_id' => $second_ticket_id,
                    'order_id' => $order->id,
                    'status' => 'pending',
                ]);
            }



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

        $itemDetails[] = ['id' => 'ADMIN_FEE', 'price' => $order->admin_fee, 'quantity' => 1, 'name' => 'Biaya Layanan'];

        if ($order->donation_event > 0) $itemDetails[] = ['id' => 'DONATION_EVENT', 'price' => $order->donation_event, 'quantity' => 1, 'name' => 'Donasi Event'];
        if ($order->donation_scholarship > 0) $itemDetails[] = ['id' => 'DONATION_SCHOLARSHIP', 'price' => $order->donation_scholarship, 'quantity' => 1, 'name' => 'Donasi Beasiswa'];

        if ($order->discount_amount > 0) {
            $itemDetails[] = [
                'id' => 'VOUCHER_DISCOUNT',
                'price' => -$order->discount_amount,
                'quantity' => 1,
                'name' => 'Potongan Voucher (' . $order->voucher_code . ')'
            ];
        }

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
