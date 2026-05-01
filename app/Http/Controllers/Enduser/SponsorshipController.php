<?php

namespace App\Http\Controllers\Enduser;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Setting;
use App\Models\Ticket;
use App\Models\Period;
use App\Models\Participant;
use App\Models\Order;
use App\Models\RaceEntry;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Traits\PaymentHandlerTrait;

class SponsorshipController extends Controller
{
    use PaymentHandlerTrait;

    public function index()
    {
        $isMaintenance = Setting::getValue('is_running', '0') !== '1';
        if ($isMaintenance) {
            return view('pages.enduser.coming-soon');
        }

        // Find the "Invitation & Sponsorship" period (regardless of active status)
        $period = Period::where('name', 'Invitation & Sponsorship')->first();
        
        if (!$period) {
            return "Periode 'Invitation & Sponsorship' tidak ditemukan. Silakan hubungi admin.";
        }

        $periodId = $period->id;
        
        $tickets_ipb = Ticket::where('period_id', $periodId)
            ->where('type', 'ipb')
            ->with(['category', 'period'])
            ->withCount(['raceEntries as participants_count' => function ($query) {
                $query->whereIn('status', ['pending', 'paid']);
            }])->get();

        $tickets_public = Ticket::where('period_id', $periodId)
            ->where('type', 'umum')
            ->with(['category', 'period'])
            ->withCount(['raceEntries as participants_count' => function ($query) {
                $query->whereIn('status', ['pending', 'paid']);
            }])->get();

        $isPeriodSoldOut = $period ? $period->is_sold_out : false;

        return view('pages.enduser.sponsorship.index', compact('tickets_ipb', 'tickets_public', 'isPeriodSoldOut', 'period'));
    }

    public function checkout(Ticket $ticket)
    {
        $isMaintenance = Setting::getValue('is_running', '0') !== '1';
        if ($isMaintenance) {
            return view('pages.enduser.coming-soon');
        }

        if (!$ticket->period) {
            return redirect('/sponsorship')->with('error', 'Maaf, periode pendaftaran untuk tiket ini tidak ditemukan.');
        }

        return view('pages.enduser.sponsorship.checkout', compact('ticket'));
    }

    public function register(Request $request)
    {
        $ticket = Ticket::findOrFail($request->ticket_id);
        
        $validated = $request->validate([
            'ticket_id' => 'required',
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'email_confirmation' => 'required|same:email',
            'phone_number' => 'required|numeric',
            'nik' => 'required|numeric|digits:16',
            'date_birth' => 'required',
            'sex' => 'required|in:male,female',
            'blood_type' => 'required',
            'jersey_size' => 'required',
            'nationality' => 'required',
            'address' => 'required|string',
            'emergency_contact_name' => 'required|string',
            'emergency_contact_phone_number' => 'required|numeric',
            'emergency_contact_relationship' => 'required|string',
        ]);

        $nik = $request->nik;

        // Duplicate check (active orders)
        $duplicateCheck = RaceEntry::whereHas('participant', function ($q) use ($nik) {
            $q->where('nik', $nik);
        })
        ->where('ticket_id', $ticket->id)
        ->whereIn('status', ['pending', 'paid'])
        ->first();

        if ($duplicateCheck) {
            return back()->withInput()->withErrors([
                'nik' => "Peserta dengan NIK ini sudah terdaftar untuk kategori ini.",
            ]);
        }


        return DB::transaction(function () use ($request, $validated, $ticket, $nik) {
            $lockedTicket = Ticket::where('id', $ticket->id)->lockForUpdate()->first();
            $usedQty = RaceEntry::where('ticket_id', $lockedTicket->id)
                ->whereIn('status', ['pending', 'paid'])->count();

            if ($usedQty >= $lockedTicket->qty) {
                return redirect('/sponsorship')->with('error', 'Maaf, tiket untuk kategori ini baru saja habis terjual.');
            }

            $adminFee = 0;
            $ticketSubtotal = $ticket->price;
            


            $totalPrice = $ticketSubtotal + $adminFee;

            $participant = Participant::create(
                array_merge(
                    \Illuminate\Support\Arr::except($validated, ['email_confirmation', 'ticket_id']),
                    ['is_community' => false] 
                )
            );

            $orderCode = 'IPBR26-SP-' . strtoupper(Str::random(6));
            $order = Order::create([
                'participant_id' => $participant->id,
                'order_code' => $orderCode,
                'status' => 'pending',
                'admin_fee' => $adminFee,
                'total_price' => $totalPrice,
            ]);

            $participant->raceEntries()->create([
                'ticket_id' => $ticket->id,
                'order_id' => $order->id,
                'status' => 'pending',
            ]);



            // Mark as success using the shared handler
            $this->handleSuccessPayment($order, $participant);

            return redirect()->route('payment.finish', [
                'order_id' => $order->order_code,
                'transaction_status' => 'capture'
            ]);
        });
    }
}
