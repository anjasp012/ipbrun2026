<?php

namespace App\Http\Controllers\Enduser;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Ticket;
use App\Models\Participant;
use App\Models\Setting;
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

        // 2. Fetch tickets
        $tickets = Ticket::whereHas('period', function($query) {
            $query->where('is_active', true);
        })->with(['category', 'period'])
        ->withCount(['participants' => function($query) {
            $query->whereIn('status', ['pending', 'paid']);
        }])->get();

        $tickets_ipb = $tickets->filter(fn($t) => str_contains(strtolower($t->name), 'ipb'));
        $tickets_public = $tickets->filter(fn($t) => !str_contains(strtolower($t->name), 'ipb'));

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
        $usedQty = Participant::where('ticket_id', $ticket->id)
            ->whereIn('status', ['pending', 'paid'])
            ->count();

        if ($usedQty >= $ticket->qty) {
            return redirect('/')->with('error', 'Maaf, tiket untuk kategori ini baru saja habis terjual.');
        }

        return view('pages.enduser.checkout', compact('ticket'));
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'ticket_id' => 'required',
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'email_confirmation' => 'required|same:email',
            'phone_number' => 'required|numeric',
            'nik' => 'required|numeric|digits:16',
            'date_birth' => 'required',
            'sex' => 'required|in:male,female',
            'blood_type' => 'required|in:A,B,AB,O,-',
            'jersey_size' => 'required|in:S,M,L,XL,XXL',
            'nim_nrp' => 'nullable|string',
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
        ]);

        return \Illuminate\Support\Facades\DB::transaction(function () use ($request, $validated) {
            // 1. Lock the ticket record to prevent race conditions
            $ticket = Ticket::where('id', $request->ticket_id)->lockForUpdate()->firstOrFail();
            
            // 2. Check current stock accurately
            $usedQty = Participant::where('ticket_id', $ticket->id)
                ->whereIn('status', ['pending', 'paid'])
                ->count();

            if ($usedQty >= $ticket->qty) {
                return redirect('/')->with('error', 'Maaf, tiket untuk kategori ini baru saja habis terjual.');
            }

            // 3. Duplicate check for participant
            $duplicateCheck = Participant::where(function ($query) use ($request) {
                    $query->where('email', $request->email)
                        ->orWhere('nik', $request->nik);
                    if ($request->filled('nim_nrp')) { $query->orWhere('nim_nrp', $request->nim_nrp); }
                })
                ->whereIn('status', ['pending', 'paid'])
                ->lockForUpdate() // Lock existing participant records if found
                ->first();

            if ($duplicateCheck) {
                $fieldKey = $duplicateCheck->email === $request->email ? 'email' : ($duplicateCheck->nik === $request->nik ? 'nik' : 'nim_nrp');
                $fieldName = $fieldKey === 'email' ? 'Email' : ($fieldKey === 'nik' ? 'NIK' : 'NIM/NRP');
                
                return back()->withInput()->withErrors([
                    $fieldKey => "Field ini sudah terdaftar dalam sistem.",
                    'duplicate' => "$fieldName sudah terdaftar dalam sistem dan sedang dalam status Pending/Paid. Silakan gunakan data lain atau selesaikan pembayaran sebelumnya."
                ]);
            }

            $adminFee = 4500;
            $donationEvent = (int) $request->input('donation_event', 0);
            $donationScholarship = (int) $request->input('donation_scholarship', 0);
            $totalPrice = $ticket->price + $adminFee + $donationEvent + $donationScholarship;

            $orderCode = 'IPBR26-' . strtoupper(\Illuminate\Support\Str::random(6));
            $participantData = \Illuminate\Support\Arr::except($validated, ['email_confirmation']);
            
            $participant = Participant::create(array_merge($participantData, [
                'order_code' => $orderCode,
                'donation_event' => $donationEvent,
                'donation_scholarship' => $donationScholarship,
                'total_price' => $totalPrice,
                'status' => 'pending',
                'running_community' => $request->running_community,
                'previous_events' => $request->previous_events,
                'best_time' => $request->best_time,
                'shuttle_bus' => $request->shuttle_bus,
                'other_race_interest' => $request->other_race_interest,
            ]));

            $params = [
                'transaction_details' => ['order_id' => $participant->order_code, 'gross_amount' => $totalPrice],
                'customer_details' => ['first_name' => $participant->name, 'email' => $participant->email, 'phone' => $participant->phone_number],
                'item_details' => [
                    ['id' => $ticket->id, 'price' => $ticket->price, 'quantity' => 1, 'name' => 'Tiket IPB Run 2026 - ' . $ticket->category->name],
                    ['id' => 'ADMIN_FEE', 'price' => $adminFee, 'quantity' => 1, 'name' => 'Biaya Layanan']
                ],
                'callbacks' => [
                    'finish' => route('payment.finish')
                ]
            ];

            if ($donationEvent > 0) $params['item_details'][] = ['id' => 'DONATION_EVENT', 'price' => $donationEvent, 'quantity' => 1, 'name' => 'Donasi Event'];
            if ($donationScholarship > 0) $params['item_details'][] = ['id' => 'DONATION_SCHOLARSHIP', 'price' => $donationScholarship, 'quantity' => 1, 'name' => 'Donasi Beasiswa'];

            try {
                $snapResponse = Snap::createTransaction($params);
                $participant->update(['snap_token' => $snapResponse->token, 'payment_url' => $snapResponse->redirect_url]);
                
                // Send WhatsApp notification
                try {
                    $fonnte = new \App\Services\FonnteService();
                    $message = "Halo *{$participant->name}*!\n\nRegistrasi IPB Run 2026 Anda berhasil dengan kode order *{$participant->order_code}*.\n\nSilakan lakukan pembayaran melalui link berikut:\n{$snapResponse->redirect_url}\n\nMohon segera selesaikan pembayaran agar registrasi Anda tidak kadaluarsa. Terima kasih!";
                    $fonnte->sendMessage($participant->phone_number, $message);
                } catch (\Exception $e) {
                    \Illuminate\Support\Facades\Log::error('Fonnte registration notification failed: ' . $e->getMessage());
                }

                return redirect($snapResponse->redirect_url);
            } catch (\Exception $e) {
                // Transaction will rollback if an exception is thrown here
                throw new \Exception('Midtrans integration failed: ' . $e->getMessage());
            }
        });
    }
}
