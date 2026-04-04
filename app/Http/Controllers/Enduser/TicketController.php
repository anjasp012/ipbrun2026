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
        if (Setting::getValue('is_running', '0') !== '1') {
            return view('pages.enduser.coming-soon');
        }

        // Fetch tickets only from the active period
        $tickets = Ticket::whereHas('period', function($query) {
            $query->where('is_active', true);
        })->with(['category', 'period'])->get();

        $tickets_ipb = $tickets->filter(fn($t) => strtolower($t->name) === 'ipb');
        $tickets_public = $tickets->filter(fn($t) => strtolower($t->name) === 'umum');

        return view('pages.enduser.index', compact('tickets_ipb', 'tickets_public'));
    }

    public function checkout(Ticket $ticket)
    {
        if (Setting::getValue('is_running', '0') !== '1') {
            return redirect('/');
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
                return redirect('/')->withErrors(['sold_out' => 'Maaf, tiket untuk kategori ini baru saja habis terjual.']);
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
                $field = $duplicateCheck->email === $request->email ? "Email" : ($duplicateCheck->nik === $request->nik ? "NIK" : "NIM/NRP");
                return back()->withInput()->withErrors([
                    'duplicate' => "$field sudah terdaftar dalam sistem dan sedang dalam status Pending/Paid. Silakan gunakan data lain atau selesaikan pembayaran sebelumnya."
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
                'status' => 'pending'
            ]));

            $params = [
                'transaction_details' => ['order_id' => $participant->order_code, 'gross_amount' => $totalPrice],
                'customer_details' => ['first_name' => $participant->name, 'email' => $participant->email, 'phone' => $participant->phone_number],
                'item_details' => [
                    ['id' => $ticket->id, 'price' => $ticket->price, 'quantity' => 1, 'name' => 'Tiket IPB Run 2026 - ' . $ticket->category->name],
                    ['id' => 'ADMIN_FEE', 'price' => $adminFee, 'quantity' => 1, 'name' => 'Biaya Layanan']
                ]
            ];

            if ($donationEvent > 0) $params['item_details'][] = ['id' => 'DONATION_EVENT', 'price' => $donationEvent, 'quantity' => 1, 'name' => 'Donasi Event'];
            if ($donationScholarship > 0) $params['item_details'][] = ['id' => 'DONATION_SCHOLARSHIP', 'price' => $donationScholarship, 'quantity' => 1, 'name' => 'Donasi Beasiswa'];

            try {
                $snapResponse = Snap::createTransaction($params);
                $participant->update(['snap_token' => $snapResponse->token, 'payment_url' => $snapResponse->redirect_url]);
                return redirect($snapResponse->redirect_url);
            } catch (\Exception $e) {
                // Transaction will rollback if an exception is thrown here
                throw new \Exception('Midtrans integration failed: ' . $e->getMessage());
            }
        });
    }
}
