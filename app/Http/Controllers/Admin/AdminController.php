<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Participant;
use App\Models\Category;
use App\Models\Setting;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public function dashboard()
    {
        // 1. Ringkasan Data
        $totalSoldTickets = Participant::whereIn('status', ['pending', 'paid'])->count();
        $totalPaidParticipants = Participant::where('status', 'paid')->count();
        $totalTicketsSold = $totalSoldTickets; 
        $totalCapacity = \App\Models\Ticket::sum('qty');

        $stats = [
            'total_revenue' => Participant::where('status', 'paid')->sum('total_price'),
            'total_order' => Participant::count(),
            'total_participants' => $totalPaidParticipants,
            'total_remaining_tickets' => $totalCapacity - $totalTicketsSold,
            'is_running' => Setting::getValue('is_running', '0') === '1'
        ];

        // 2. Periods & Tickets Breakdown
        $periods = \App\Models\Period::with(['tickets.category', 'tickets.participants' => function($q) {
            $q->whereIn('status', ['pending', 'paid']);
        }])->get();

        $periodsData = $periods->map(function($period) {
            $tickets = $period->tickets->map(function($ticket) {
                $terjual = $ticket->participants->count();
                return (object) [
                    'kategori' => $ticket->category->name ?? '-',
                    'name' => $ticket->name,
                    'price' => $ticket->price,
                    'kapasitas' => $ticket->qty,
                    'terjual' => $terjual,
                    'sisa_stok' => $ticket->qty - $terjual
                ];
            });

            return (object) [
                'name' => $period->name,
                'total_kapasitas' => $tickets->sum('kapasitas'),
                'total_terjual' => $tickets->sum('terjual'),
                'total_sisa_stok' => $tickets->sum('sisa_stok'),
                'tickets' => $tickets
            ];
        });

        return view('pages.admin.dashboard', compact('stats', 'periodsData'));
    }

    public function toggleRunning()
    {
        $setting = Setting::firstOrCreate(['key' => 'is_running']);
        $isActive = $setting->value === '1';
        $setting->value = $isActive ? '0' : '1';
        $setting->save();

        $statusMessage = $setting->value === '1' 
            ? 'Registration is now OPEN and Live!' 
            : 'Website has been set to MAINTENANCE mode.';

        return back()->with('success', $statusMessage);
    }

    public function participants(Request $request)
    {
        $query = Participant::with(['ticket.category', 'ticket.period']);

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%$search%")
                  ->orWhere('email', 'like', "%$search%")
                  ->orWhere('order_code', 'like', "%$search%");
            });
        }

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        $participants = $query->latest()->paginate(25);

        return view('pages.admin.participants.index', compact('participants'));
    }

    public function participantShow(Participant $participant)
    {
        return view('pages.admin.participants.show', compact('participant'));
    }

    public function resendInvoice(Participant $participant)
    {
        try {
            \Illuminate\Support\Facades\Mail::to($participant->email)->send(new \App\Mail\ParticipantInvoiceResend($participant));
            return back()->with('success', 'E-Invoice has been resent to ' . $participant->email);
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to send email: ' . $e->getMessage());
        }
    }
}
