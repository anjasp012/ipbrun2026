<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Participant;
use App\Models\Category;
use App\Models\Setting;
use App\Models\RaceEntry;
use App\Models\Period;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ParticipantExport;
use App\Exports\ParticipantImportTemplate;
use App\Imports\ParticipantImport;

class AdminController extends Controller
{
    public function dashboard()
    {
        // 1. Ringkasan Data
        $totalTicketsRegular = RaceEntry::where('status', 'paid')
            ->whereHas('ticket.period', function($q) {
                $q->where('name', '!=', 'Invitation & Sponsorship');
            })->count();
        $totalTicketsSponsor = RaceEntry::where('status', 'paid')
            ->whereHas('ticket.period', function($q) {
                $q->where('name', 'Invitation & Sponsorship');
            })->count();

        $totalTicketsSold = $totalTicketsRegular + $totalTicketsSponsor;
        $totalOrders = \App\Models\Order::where('status', 'paid')->count();
        $totalCapacity = \App\Models\Ticket::sum('qty');

        $stats = [
            'total_revenue' => \App\Models\Order::where('status', 'paid')->sum('total_price'),
            'total_donation_scholarship' => \App\Models\Order::where('status', 'paid')->sum('donation_scholarship'),
            'total_donation_event' => \App\Models\Order::where('status', 'paid')->sum('donation_event'),
            'total_admin' => \App\Models\Order::where('status', 'paid')->sum('admin_fee'),
            'total_participant' => User::where('role', 'participant')->count(),
            'total_tickets_sold' => $totalTicketsSold,
            'total_tickets_regular' => $totalTicketsRegular,
            'total_tickets_sponsor' => $totalTicketsSponsor,
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
            $keywords = array_filter(array_map('trim', explode(',', $search)));
            $query->where(function ($q) use ($keywords) {
                foreach ($keywords as $keyword) {
                    $q->orWhere(function ($sq) use ($keyword) {
                        $sq->where('name', 'like', "%$keyword%")
                            ->orWhere('email', 'like', "%$keyword%")
                            ->orWhere('nik', 'like', "%$keyword%")
                            ->orWhere('phone_number', 'like', "%$keyword%")
                            ->orWhereHas('raceEntries.order', function ($rq) use ($keyword) {
                                $rq->where('order_code', 'like', "%$keyword%");
                            });
                    });
                }
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

        if ($request->filled('category_id')) {
            $categoryId = $request->category_id;
            $query->whereHas('raceEntries.ticket', function ($rq) use ($categoryId) {
                $rq->where('category_id', $categoryId);
            });
        }

        if ($request->filled('period_id')) {
            $periodId = $request->period_id;
            $query->whereHas('raceEntries.ticket', function ($rq) use ($periodId) {
                $rq->where('period_id', $periodId);
            });
        }

        $participants = $query->latest()->paginate(25);
        
        $categories = Category::orderBy('name')->get();
        $periods = Period::orderBy('name')->get();
        $sponsorshipPeriods = Period::where('name', 'like', '%Sponsorship%')->orderBy('name')->get();

        return view('pages.admin.participants.index', compact('participants', 'categories', 'periods', 'sponsorshipPeriods'));
    }

    public function exportParticipants(Request $request)
    {
        $status = $request->status;
        $ticketType = $request->ticket_type;
        $categoryId = $request->category_id;
        $periodId = $request->period_id;
        $search = $request->search;
        $selectedColumns = $request->columns ?? [];
        $participantType = $request->participant_type ?? 'all'; // all, regular, bundling
        $splitBundling = $request->has('split_bundling');

        $query = Participant::query();

        // Constrain Relationship loading based on filters
        $query->with(['raceEntries' => function ($q) use ($status, $ticketType, $categoryId, $periodId) {
            if ($status) {
                $q->whereHas('order', function ($oq) use ($status) {
                    $oq->where('status', $status);
                });
            }
            if ($ticketType) {
                $q->whereHas('ticket', function ($tq) use ($ticketType) {
                    $tq->where('type', $ticketType);
                });
            }
            if ($categoryId) {
                $q->whereHas('ticket', function ($tq) use ($categoryId) {
                    $tq->where('category_id', $categoryId);
                });
            }
            if ($periodId) {
                $q->whereHas('ticket', function ($tq) use ($periodId) {
                    $tq->where('period_id', $periodId);
                });
            }
            $q->with(['ticket.category', 'ticket.period', 'order']);
        }]);

        // Filter Participants (WhereHas ensures the participant has at least one matching entry)
        if ($search) {
            $keywords = array_filter(array_map('trim', explode(',', $search)));
            $query->where(function ($q) use ($keywords) {
                foreach ($keywords as $keyword) {
                    $q->orWhere(function ($sq) use ($keyword) {
                        $sq->where('name', 'like', "%$keyword%")
                            ->orWhere('email', 'like', "%$keyword%")
                            ->orWhere('nik', 'like', "%$keyword%")
                            ->orWhere('phone_number', 'like', "%$keyword%")
                            ->orWhereHas('raceEntries.order', function ($rq) use ($keyword) {
                                $rq->where('order_code', 'like', "%$keyword%");
                            });
                    });
                }
            });
        }

        if ($status) {
            $query->whereHas('raceEntries.order', function ($rq) use ($status) {
                $rq->where('status', $status);
            });
        }

        if ($ticketType) {
            $query->whereHas('raceEntries.ticket', function ($rq) use ($ticketType) {
                $rq->where('type', $ticketType);
            });
        }

        if ($categoryId) {
            $query->whereHas('raceEntries.ticket', function ($rq) use ($categoryId) {
                $rq->where('category_id', $categoryId);
            });
        }

        if ($periodId) {
            $query->whereHas('raceEntries.ticket', function ($rq) use ($periodId) {
                $rq->where('period_id', $periodId);
            });
        }

        // Filter Date Range
        if ($request->filled('start_date')) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }

        // Filter by participant type (regular = 1 entry, bundling = >1 entries)
        if ($participantType === 'regular') {
            $query->has('raceEntries', '=', 1);
        } elseif ($participantType === 'bundling') {
            $query->has('raceEntries', '>', 1);
        }

        $participants = $query->latest()->get();

        $filename = "participants_export_" . date('Y-m-d_H-i-s') . ".xlsx";

        return Excel::download(new ParticipantExport($participants, $status, $selectedColumns, $splitBundling), $filename);
    }

    public function participantShow(Participant $participant)
    {
        $tickets = \App\Models\Ticket::whereHas('period', function ($q) {
            $q->where('is_active', true);
        })->with('category', 'period')->get();
        
        return view('pages.admin.participants.show', compact('participant', 'tickets'));
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

    public function addTicket(Request $request, Participant $participant)
    {
        $request->validate([
            'ticket_id' => 'required|exists:tickets,id',
            'price' => 'required|numeric|min:0'
        ]);

        try {
            DB::beginTransaction();

            $ticket = \App\Models\Ticket::findOrFail($request->ticket_id);
            $price = $request->price;

            // Duplicate check
            $exists = RaceEntry::where('participant_id', $participant->id)
                ->where('ticket_id', $ticket->id)
                ->whereIn('status', ['pending', 'paid'])
                ->exists();

            if ($exists) {
                return back()->with('error', 'Peserta sudah terdaftar di kategori tiket ini.');
            }

            // Create Order
            $orderCode = 'IPBR26-SP-' . strtoupper(\Illuminate\Support\Str::random(6));
            $order = \App\Models\Order::create([
                'participant_id' => $participant->id,
                'order_code' => $orderCode,
                'status' => 'paid',
                'admin_fee' => 0,
                'donation_event' => 0,
                'donation_scholarship' => 0,
                'total_price' => $price,
            ]);

            // Create Race Entry
            $participant->raceEntries()->create([
                'ticket_id' => $ticket->id,
                'order_id' => $order->id,
                'status' => 'paid',
            ]);

            // Jika participant belum punya user (e.g. dulunya belum bayar atau ada masalah)
            // kita create user-nya & generate password
            $userExists = true;
            $password = null;
            if (!$participant->user_id) {
                $userExists = false;
                $user = \App\Models\User::where('email', $participant->email)->first();
                if (!$user) {
                    $password = \Illuminate\Support\Str::random(8);
                    $user = \App\Models\User::create([
                        'name' => $participant->name,
                        'email' => $participant->email,
                        'username' => $participant->email,
                        'password' => \Illuminate\Support\Facades\Hash::make($password),
                        'role' => 'participant'
                    ]);
                }
                $participant->update(['user_id' => $user->id]);
            }

            DB::commit();

            // Send Email Notification
            try {
                \Illuminate\Support\Facades\Mail::to($participant->email)
                    ->send(new \App\Mail\ParticipantPaidNotification($participant, $password, $order, $userExists));
                $emailStatus = "Email notifikasi berhasil dikirim.";
            } catch (\Exception $e) {
                $emailStatus = "Namun gagal mengirim email: " . $e->getMessage();
            }

            return back()->with('success', "Tiket baru berhasil ditambahkan! {$emailStatus}");

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
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

    public function cancelParticipant(Participant $participant)
    {
        try {
            DB::beginTransaction();

            // 1. Update all related orders to failed
            \App\Models\Order::where('participant_id', $participant->id)
                ->update(['status' => 'failed']);

            // 2. Update all race entries to failed
            \App\Models\RaceEntry::where('participant_id', $participant->id)
                ->update(['status' => 'failed']);

            // 3. Delete associated user if exists
            if ($participant->user_id) {
                \App\Models\User::where('id', $participant->user_id)->delete();
                $participant->update(['user_id' => null]);
            } else {
                // Fallback check by email if user_id is null
                \App\Models\User::where('email', $participant->email)
                    ->where('role', 'participant')
                    ->delete();
            }

            DB::commit();

            return back()->with('success', "Peserta ({$participant->name}) telah berhasil dinonaktifkan. Semua pesanan diset ke FAILED dan akun login telah dihapus.");
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal menonaktifkan peserta: ' . $e->getMessage());
        }
    }

    public function bulkCancelParticipants(Request $request)
    {
        $ids = $request->ids;
        if (empty($ids)) {
            return back()->with('error', 'Pilih minimal satu peserta.');
        }

        try {
            DB::beginTransaction();

            // 1. Update all related orders to failed
            \App\Models\Order::whereIn('participant_id', $ids)
                ->update(['status' => 'failed']);

            // 2. Update all race entries to failed
            \App\Models\RaceEntry::whereIn('participant_id', $ids)
                ->update(['status' => 'failed']);

            // 3. Delete associated users
            $participants = Participant::whereIn('id', $ids)->get();
            $emails = $participants->pluck('email');
            $userIds = $participants->whereNotNull('user_id')->pluck('user_id');

            if ($userIds->isNotEmpty()) {
                \App\Models\User::whereIn('id', $userIds)->delete();
            }
            
            \App\Models\User::whereIn('email', $emails)
                ->where('role', 'participant')
                ->delete();

            Participant::whereIn('id', $ids)->update(['user_id' => null]);

            DB::commit();

        return back()->with('success', count($ids) . " peserta telah berhasil dinonaktifkan secara massal.");
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal menonaktifkan peserta secara massal: ' . $e->getMessage());
        }
    }

    public function bulkResendInvoice(Request $request)
    {
        $ids = $request->ids;
        if (empty($ids)) {
            return back()->with('error', 'Pilih minimal satu peserta.');
        }

        $successCount = 0;
        $failCount = 0;
        $skippedCount = 0;

        $participants = Participant::whereIn('id', $ids)->get();

        foreach ($participants as $participant) {
            $orders = \App\Models\Order::where('participant_id', $participant->id)
                ->where('status', 'paid')
                ->latest()
                ->get();

            if ($orders->isEmpty()) {
                $skippedCount++;
                continue;
            }

            try {
                \Illuminate\Support\Facades\Mail::to($participant->email)
                    ->send(new \App\Mail\ParticipantInvoiceResend($participant, $orders));
                $successCount++;
            } catch (\Exception $e) {
                $failCount++;
            }
        }

        $message = "Bulk Resend selesai: {$successCount} email berhasil dikirim";
        if ($skippedCount > 0) $message .= ", {$skippedCount} dilewati (belum paid)";
        if ($failCount > 0) $message .= ", {$failCount} gagal dikirim";
        $message .= ".";

        return back()->with('success', $message);
    }

    public function importTemplate()
    {
        return Excel::download(new ParticipantImportTemplate(), 'participant_import_template.xlsx');
    }

    public function importParticipants(Request $request)
    {
        $request->validate([
            'period_id' => 'required|exists:periods,id',
            'ticket_type' => 'required|in:umum,ipb',
            'file' => 'required',
        ]);

        try {
            Excel::import(new ParticipantImport($request->period_id, $request->ticket_type, $request->order_email), $request->file('file'));
            return back();
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal import data: ' . $e->getMessage());
        }
    }
}
