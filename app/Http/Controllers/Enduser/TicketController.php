<?php

namespace App\Http\Controllers\Enduser;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Ticket;
use App\Models\Participant;
use App\Models\Setting;
use App\Models\RaceEntry;
use App\Models\Order;
use Midtrans\Config;
use Midtrans\Snap;

class TicketController extends Controller
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
        $ticketSaleStart = Setting::getValue('ticket_sale_start');
        $isMaintenance = Setting::getValue('is_running', '0') !== '1';

        // 1. Maintenance Mode (Shows the designed "Coming Soon" background)
        if ($isMaintenance) {
            return view('pages.enduser.coming-soon');
        }

        // 2. Auth Check: If Participant, show Dashboard
        if (auth()->check() && auth()->user()->role === 'participant') {
            $user = auth()->user();
            $participant = $user->participant()->with('raceEntries.ticket.category')->first();

            if (!$participant) {
                // If somehow they are logged in but have no profile, show regular tickets
                goto showTickets;
            }

            $orders = Order::where('participant_id', $participant->id)->with('raceEntries.ticket.category')->latest()->get();
            
            // Logic for Pair Recommendation (Strict Mapping)
            // (5K = 10K; 42K = 10K; 10K = 5K; 21K = 5K)
            $firstEntry = $participant->raceEntries()->whereIn('status', ['paid', 'pending'])->with('ticket.category')->first();
            $ownedCategoryNames = $participant->raceEntries()->whereIn('status', ['paid', 'pending'])->get()->pluck('ticket.category.name')->map(fn($n) => strtoupper($n))->toArray();
            
            $pairTarget = '';
            if ($firstEntry) {
                $firstCatName = strtoupper($firstEntry->ticket->category->name);
                if (str_contains($firstCatName, '5K') || str_contains($firstCatName, '42K')) {
                    $pairTarget = '10K';
                } elseif (str_contains($firstCatName, '10K') || str_contains($firstCatName, '21K')) {
                    $pairTarget = '5K';
                }
            }

            // Don't recommend if they already own the target category
            if (in_array($pairTarget, $ownedCategoryNames)) {
                $pairTarget = '';
            }

            $pairRecommendation = null;
            if ($pairTarget && $firstEntry) {
                $pairRecommendation = Ticket::whereHas('period', fn($q) => $q->where('is_active', true))
                    ->whereHas('category', fn($q) => $q->where('name', 'LIKE', "%$pairTarget%"))
                    ->where('type', $firstEntry->ticket->type)
                    ->with(['category', 'period'])
                    ->first();
            }

            return view('pages.enduser.dashboard', compact('participant', 'orders', 'pairRecommendation'));
        }

        showTickets:
        // 3. Fetch tickets (Standard Landing Page)
        $tickets = Ticket::whereHas('period', function($query) {
            $query->where('is_active', true);
        })->with(['category', 'period'])
        ->withCount(['raceEntries as participants_count' => function($query) {
            $query->whereIn('status', ['pending', 'paid']);
        }])->get();

        $tickets_ipb = $tickets->filter(fn($t) => $t->type === 'ipb');
        $tickets_public = $tickets->filter(fn($t) => $t->type === 'umum');

        // Force WIB for parse
        $ticketSaleStartValue = $ticketSaleStart ? \Illuminate\Support\Carbon::parse($ticketSaleStart, 'Asia/Jakarta') : null;

        return view('pages.enduser.index', compact('tickets_ipb', 'tickets_public', 'ticketSaleStart', 'ticketSaleStartValue'));
    }

    public function checkout(Ticket $ticket)
    {
        $ticketSaleStart = Setting::getValue('ticket_sale_start');
        $isMaintenance = Setting::getValue('is_running', '0') !== '1';

        if ($isMaintenance) {
            return redirect('/');
        }

        if ($ticketSaleStart) {
            $ticketSaleStartValue = \Illuminate\Support\Carbon::parse($ticketSaleStart, 'Asia/Jakarta');
            if (now()->lessThan($ticketSaleStartValue)) {
                return redirect('/')->with('error', 'Pendaftaran belum dibuka!');
            }
        }

        // 1. Check if the ticket's period is active
        if (!$ticket->period || !$ticket->period->is_active) {
            return redirect('/')->with('error', 'Maaf, periode pendaftaran untuk tiket ini tidak aktif.');
        }

        // 2. Check current stock accurately (capacity - pending/paid)
        $usedQty = RaceEntry::where('ticket_id', $ticket->id)
            ->whereIn('status', ['pending', 'paid'])->count();

        if ($usedQty >= $ticket->qty) {
            return redirect('/')->with('error', 'Maaf, tiket untuk kategori ini baru saja habis terjual.');
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
                ->where('type', $ticket->type) // Must match the same type (IPB/Umum)
                ->whereHas('category', function($q) use ($pairTarget) {
                    $q->where('name', 'LIKE', "%$pairTarget%");
                })
                ->where('id', '!=', $ticket->id)
                ->first();
        }

        return view('pages.enduser.checkout', compact('ticket', 'pairTicket'));
    }

    public function register(Request $request)
    {
        $ticket = Ticket::findOrFail($request->ticket_id);
        $nimRule = ($ticket->type === 'ipb') ? 'required|string' : 'nullable|string';

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
            'jersey_size' => 'required|in:S,M,L,XL,XXL',
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
            'other_race_interest' => 'nullable|string',
        ], [
            'required' => ':attribute wajib diisi.',
            'email' => 'Format email tidak valid.',
            'same' => 'Konfirmasi email tidak cocok.',
            'numeric' => ':attribute harus berupa angka.',
            'digits' => ':attribute harus berjumlah :digits digit.',
            'in' => 'Pilihan :attribute tidak valid.',
        ], [
            'name' => 'Nama Lengkap', 'email' => 'Alamat Email', 'email_confirmation' => 'Konfirmasi Email',
            'phone_number' => 'Nomor WhatsApp', 'nik' => 'NIK KTP', 'date_birth' => 'Tanggal Lahir',
            'sex' => 'Jenis Kelamin', 'blood_type' => 'Golongan Darah', 'jersey_size' => 'Ukuran Jersey',
            'address' => 'Alamat Lengkap', 'emergency_contact_name' => 'Nama Kontak Darurat',
            'emergency_contact_phone_number' => 'Nomor HP Darurat', 'emergency_contact_relationship' => 'Hubungan Kontak',
            'nim_nrp' => 'NIM / NRP',
        ]);

        return \Illuminate\Support\Facades\DB::transaction(function () use ($request, $validated, $ticket) {
            // 1. Lock the ticket record to prevent race conditions
            $ticket->lockForUpdate()->first();
            
            // 2. Check current stock accurately
            // 2. Check current stock accurately
            $usedQty = RaceEntry::where('ticket_id', $ticket->id)
                ->whereHas('participant', function($q) {
                    $q->whereIn('status', ['pending', 'paid']);
                })->count();

            if ($usedQty >= $ticket->qty) {
                return redirect('/')->with('error', 'Maaf, tiket untuk kategori ini baru saja habis terjual.');
            }

            // 3. Duplicate check for entry (One NIK cannot buy SAME ticket category twice)
            // This allows users to register for DIFFERENT days/categories in separate orders later.
            $duplicateCheck = RaceEntry::whereHas('participant', function($q) use ($request) {
                    $q->where('nik', $request->nik)
                      ->whereIn('status', ['pending', 'paid']);
                })
                ->where('ticket_id', $ticket->id)
                ->first();

            if ($duplicateCheck) {
                return back()->withInput()->withErrors([
                    'nik' => "Peserta dengan NIK ini sudah terdaftar atau memiliki pesanan tertunda untuk kategori ({$ticket->category->name}).",
                    'duplicate' => "Anda diperbolehkan mendaftar kategori lain (hari berbeda), namun tidak diperbolehkan mendaftar kategori yang sama dua kali."
                ]);
            }

            // Also check for Second Ticket if applicable
            if ($request->other_race_interest) {
                // ... same logic for second ticket if needed ...
            }

            $adminFee = 4500;
            $donationEvent = (int) $request->input('donation_event', 0);
            $donationScholarship = (int) $request->input('donation_scholarship', 0);
            $totalPrice = $ticket->price + $adminFee + $donationEvent + $donationScholarship;
            $second_ticket_id = null;

            if ($request->other_race_interest) {
                $categoryName = strtoupper($ticket->category->name ?? '');
                $pairTarget = '';
                if (str_contains($categoryName, '5K') || str_contains($categoryName, '42K')) {
                    $pairTarget = '10K';
                } elseif (str_contains($categoryName, '10K') || str_contains($categoryName, '21K')) {
                    $pairTarget = '5K';
                }

                if ($pairTarget) {
                    $pairTicket = Ticket::where('period_id', $ticket->period_id)
                        ->where('type', $ticket->type)
                        ->whereHas('category', function($q) use ($pairTarget) {
                            $q->where('name', 'LIKE', "%$pairTarget%");
                        })
                        ->where('id', '!=', $ticket->id)
                        ->first();
                    
                    if ($pairTicket) {
                        $second_ticket_id = $pairTicket->id;
                        $totalPrice += $pairTicket->price;
                    }
                }
            }

            // 4. Find or Create Participant Profile (One NIK = One Participant)
            $participant = Participant::updateOrCreate(
                ['nik' => $request->nik],
                \Illuminate\Support\Arr::except($validated, ['email_confirmation', 'ticket_id', 'other_race_interest'])
            );

            // 5. Create the TRANSACTION (Order)
            $orderCode = 'IPBR26-' . strtoupper(\Illuminate\Support\Str::random(6));
            $order = Order::create([
                'participant_id' => $participant->id,
                'order_code' => $orderCode,
                'status' => 'pending',
                'admin_fee' => $adminFee,
                'donation_event' => $donationEvent,
                'donation_scholarship' => $donationScholarship,
                'total_price' => $totalPrice, // From previous logic
            ]);

            // 6. Create Race Entries (HasMany Support)
            // Primary Entry
            $participant->raceEntries()->create([
                'ticket_id' => $ticket->id,
                'order_id' => $order->id,
                'status' => 'pending',
            ]);

            // Secondary Entry (Ganda Kategori)
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

    public function buyMore(Ticket $ticket)
    {
        $user = auth()->user();
        if (!$user) return redirect('/login');

        // 1. Get personal data from single participant profile
        $latestParticipant = $user->participant;

        if (!$latestParticipant) {
            return redirect('/')->with('error', 'Data profil Anda belum ditemukan. Silakan daftar manual terlebih dahulu.');
        }

        // 2. Duplicate check (NIK + Ticket)
        $exists = RaceEntry::whereHas('participant', function($q) use ($latestParticipant) {
                $q->where('nik', $latestParticipant->nik);
            })->where('ticket_id', $ticket->id)
            ->whereIn('status', ['pending', 'paid'])
            ->count();

        if ($exists) {
            return back()->with('error', 'Anda sudah terdaftar atau memiliki pesanan tertunda untuk kategori ini!');
        }

        // 3. Create new Participant row (Cloning Profile)
        $orderCode = 'IPBR26-' . strtoupper(\Illuminate\Support\Str::random(6));
        $adminFee = 4500;
        
        $order = Order::create([
            'participant_id' => $latestParticipant->id,
            'order_code' => $orderCode,
            'status' => 'pending',
            'admin_fee' => $adminFee,
            'total_price' => $ticket->price + $adminFee,
        ]);

        $latestParticipant->raceEntries()->create([
            'ticket_id' => $ticket->id,
            'order_id' => $order->id,
            'status' => 'pending',
        ]);

        return $this->createMidtransTransaction($order);
    }

    private function createMidtransTransaction(Order $order)
    {
        $participant = $order->participant;
        $itemDetails = [];

        foreach($order->raceEntries as $entry) {
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

        $params = [
            'transaction_details' => ['order_id' => $order->order_code, 'gross_amount' => $order->total_price],
            'customer_details' => ['first_name' => $participant->name, 'email' => $participant->email, 'phone' => $participant->phone_number],
            'item_details' => $itemDetails,
            'callbacks' => [
                'finish' => route('payment.finish')
            ]
        ];

        try {
            $snapResponse = Snap::createTransaction($params);
            $order->update([
                'snap_token' => $snapResponse->token,
                'payment_url' => $snapResponse->redirect_url
            ]);
            
            // Send WhatsApp notification
            try {
                $fonnte = new \App\Services\FonnteService();
                $message = "Halo *{$participant->name}*!\n\nPesanan tiket IPB Run 2026 Anda berhasil dengan kode order *{$order->order_code}*.\n\nSilakan lakukan pembayaran melalui link berikut:\n{$snapResponse->redirect_url}\n\nTerima kasih!";
                $fonnte->sendMessage($participant->phone_number, $message);
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::error('Fonnte notification failed: ' . $e->getMessage());
            }

            return redirect($snapResponse->redirect_url);
        } catch (\Exception $e) {
            throw new \Exception('Midtrans integration failed: ' . $e->getMessage());
        }
    }
}
