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
            // Consolidated Data
            'Order Codes',
            'Order Statuses',
            'Ticket Details',
            'Total Paid Amount',
            'First Registered At'
        ];

        $callback = function () use ($participants, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($participants as $p) {
                // Aggregate Entry Data
                $orderCodes = $p->raceEntries->map(fn($e) => $e->order->order_code ?? '-')->unique()->implode(' | ');
                $statuses = $p->raceEntries->map(fn($e) => strtoupper($e->order->status ?? ($e->status ?? 'unknown')))->implode(' | ');
                
                $ticketDetails = $p->raceEntries->map(function($e) {
                    $cat = $e->ticket->category->name ?? '-';
                    $type = strtoupper($e->ticket->type ?? '-');
                    return "($type - $cat)";
                })->implode(' | ');

                $totalPaid = $p->raceEntries->where('status', 'paid')->unique('order_id')->sum(fn($e) => $e->order->total_price ?? 0);

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
                    // Consolidated Data
                    $orderCodes,
                    $statuses,
                    $ticketDetails,
                    $totalPaid,
                    $p->created_at->format('Y-m-d H:i')
                ]);
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
            'email'                         => 'required|email|unique:participants,email,' . $participant->id . '|unique:users,email,' . ($participant->user_id ?: 'NULL') . ',id',
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

        $oldEmail = $participant->email;
        $newEmail = $request->email;
        $emailChanged = strtolower($oldEmail) !== strtolower($newEmail);

        $participant->update($validated);

        if ($emailChanged) {
            $user = $participant->user ?: \App\Models\User::where('email', $oldEmail)->first();

            if ($user) {
                // Reset password if email changed as requested
                $randomPassword = \Illuminate\Support\Str::random(8);
                $user->update([
                    'email' => $newEmail,
                    'username' => $newEmail,
                    'password' => \Illuminate\Support\Facades\Hash::make($randomPassword)
                ]);

                if (!$participant->user_id) {
                    $participant->update(['user_id' => $user->id]);
                }

                // Send New Credentials Email
                $orders = \App\Models\Order::where('participant_id', $participant->id)
                    ->whereIn('status', ['paid', 'pending'])
                    ->latest()
                    ->get();

                if ($orders->isNotEmpty()) {
                    try {
                        \Illuminate\Support\Facades\Mail::to($newEmail)->send(
                            new \App\Mail\ParticipantInvoiceResend($participant, $orders, $randomPassword)
                        );
                        return back()->with('success', 'Data peserta & Akun Login berhasil diperbarui. Email kredensial baru telah dikirim ke ' . $newEmail);
                    } catch (\Exception $e) {
                        return back()->with('success', 'Data peserta & Akun diperbarui, tapi GAGAL mengirim email: ' . $e->getMessage());
                    }
                }
            }
        }

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

    public function changePassword(Request $request, Participant $participant)
    {
        $request->validate([
            'password' => 'required|min:6'
        ]);

        $user = \App\Models\User::where('email', $participant->email)->first();
        
        if (!$user) {
            return back()->with('error', 'Gagal: Akun user belum dibuat karena pembayaran tiket belum diverifikasi.');
        }

        $user->password = \Illuminate\Support\Facades\Hash::make($request->password);
        $user->save();

        if (!$participant->user_id) {
            $participant->update(['user_id' => $user->id]);
        }

        return back()->with('success', "Password untuk profil ({$participant->name}) telah berhasil diubah secara permanen.");
    }
}
