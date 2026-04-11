<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Participant;
use App\Models\Category;
use App\Models\Setting;
use App\Models\RaceEntry;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public function dashboard()
    {
        // 1. Ringkasan Data
        $totalTicketsSold = RaceEntry::where('status', 'paid')->count();
        $totalOrders = \App\Models\Order::where('status', 'paid')->count();
        $totalCapacity = \App\Models\Ticket::sum('qty');

        $stats = [
            'total_revenue' => \App\Models\Order::where('status', 'paid')->sum('total_price'),
            'total_order' => \App\Models\Order::count(),
            'total_participants' => Participant::count(),
            'total_remaining_tickets' => $totalCapacity - $totalTicketsSold,
            'is_running' => Setting::getValue('is_running', '0') === '1'
        ];

        // 2. Periods & Tickets Breakdown
        $periods = \App\Models\Period::with(['tickets.category', 'tickets.raceEntries' => function ($q) {
            $q->whereIn('status', ['pending', 'paid']);
        }])->get();

        $periodsData = $periods->map(function ($period) {
            $tickets = $period->tickets->map(function ($ticket) {
                $terjual = $ticket->raceEntries->count();
                return (object) [
                    'kategori' => $ticket->category->name ?? '-',
                    'name' => $ticket->name,
                    'type' => $ticket->type,
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
        $query = Participant::with(['raceEntries.ticket.category', 'raceEntries.ticket.period']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%$search%")
                    ->orWhere('email', 'like', "%$search%")
                    ->orWhere('nik', 'like', "%$search%")
                    ->orWhere('phone_number', 'like', "%$search%")
                    ->orWhereHas('raceEntries.order', function ($rq) use ($search) {
                        $rq->where('order_code', 'like', "%$search%");
                    });
            });
        }

        if ($request->filled('status')) {
            $status = $request->status;
            $query->whereHas('raceEntries.order', function ($rq) use ($status) {
                $rq->where('status', $status);
            });
        }

        if ($request->filled('ticket_type')) {
            $type = $request->ticket_type;
            $query->whereHas('raceEntries.ticket', function ($rq) use ($type) {
                $rq->where('type', $type);
            });
        }

        $participants = $query->latest()->paginate(25);

        return view('pages.admin.participants.index', compact('participants'));
    }

    public function exportParticipants(Request $request)
    {
        $query = Participant::with(['raceEntries.ticket.category', 'raceEntries.ticket.period', 'raceEntries.order']);

        // Filter Status
        if ($request->filled('status')) {
            $status = $request->status;
            $query->whereHas('raceEntries.order', function ($rq) use ($status) {
                $rq->where('status', $status);
            });
        }

        // Filter Ticket Type
        if ($request->filled('ticket_type')) {
            $type = $request->ticket_type;
            $query->whereHas('raceEntries.ticket', function ($rq) use ($type) {
                $rq->where('type', $type);
            });
        }

        // Filter Date Range
        if ($request->filled('start_date')) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }

        $participants = $query->latest()->get();

        $filename = "participants_export_" . date('Y-m-d_H-i-s') . ".csv";
        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$filename",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $columns = [
            // Participant Data
            'Name',
            'Email',
            'Phone',
            'NIK',
            'Birth Date',
            'Gender',
            'Blood Type',
            'Jersey Size',
            'NIM/NRP',
            'Nationality',
            'Address',
            'Emergency Contact Name',
            'Emergency Contact Phone',
            'Emergency Relationship',
            'Community',
            'Best Time',
            'Previous Events',
            'Medical Condition',
            'Shuttle Bus',
            // Order Data
            'Order Code',
            'Order Status',
            'Total Order Price',
            'Registered At',
            // Ticket Data
            'Ticket Name',
            'Category',
            'Ticket Type',
            'Registration Period'
        ];

        $callback = function () use ($participants, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($participants as $p) {
                foreach ($p->raceEntries as $entry) {
                    fputcsv($file, [
                        // Participant
                        $p->name,
                        $p->email,
                        $p->phone_number,
                        $p->nik,
                        $p->date_birth,
                        strtoupper($p->sex),
                        $p->blood_type,
                        $p->jersey_size,
                        $p->nim_nrp ?: '-',
                        $p->nationality,
                        $p->address,
                        $p->emergency_contact_name,
                        $p->emergency_contact_phone_number,
                        $p->emergency_contact_relationship,
                        $p->running_community ?: '-',
                        $p->best_time ?: '-',
                        $p->previous_events ?: '-',
                        $p->medical_condition ?: '-',
                        $p->shuttle_bus ?: 'No',
                        // Order
                        $entry->order->order_code ?? '-',
                        strtoupper($entry->order->status ?? ($entry->status ?? 'unknown')),
                        $entry->order->total_price ?? 0,
                        $p->created_at->format('Y-m-d H:i'),
                        // Ticket
                        $entry->ticket->name ?? ($entry->ticket->type ? strtoupper($entry->ticket->type) : '-'),
                        $entry->ticket->category->name ?? '-',
                        strtoupper($entry->ticket->type ?? '-'),
                        $entry->ticket->period->name ?? 'Standard'
                    ]);
                }
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function participantShow(Participant $participant)
    {
        return view('pages.admin.participants.show', compact('participant'));
    }

    public function participantUpdate(Request $request, Participant $participant)
    {
        $validated = $request->validate([
            'name'                          => 'required|string|max:255',
            'phone_number'                  => 'required|string|max:20',
            'nik'                           => 'required|string|max:16',
            'date_birth'                    => 'required|string',
            'sex'                           => 'required|in:male,female',
            'blood_type'                    => 'required|string',
            'jersey_size'                   => 'required|string',
            'nim_nrp'                       => 'nullable|string|min:6',
            'nationality'                   => 'required|string',
            'address'                       => 'required|string',
            'emergency_contact_name'        => 'required|string',
            'emergency_contact_phone_number' => 'required|string',
            'emergency_contact_relationship' => 'required|string',
            'running_community'             => 'nullable|string',
            'best_time'                     => 'nullable|string',
            'previous_events'               => 'nullable|string',
            'medical_condition'             => 'nullable|string',
            'shuttle_bus'                   => 'nullable|string',
        ]);

        $participant->update($validated);

        return back()->with('success', 'Data peserta berhasil diperbarui.');
    }

    public function resendInvoice(Participant $participant)
    {
        try {
            $orders = \App\Models\Order::where('participant_id', $participant->id)->where('status', 'paid')->latest()->get();

            if ($orders->isEmpty()) {
                return back()->with('error', 'Tidak ada order yang sudah dibayar. Tidak bisa mengirim ulang invoice.');
            }

            \Illuminate\Support\Facades\Mail::to($participant->email)->send(new \App\Mail\ParticipantInvoiceResend($participant, $orders));
            return back()->with('success', 'E-Invoice berhasil dikirim ulang ke ' . $participant->email);
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal mengirim email: ' . $e->getMessage());
        }
    }
}
