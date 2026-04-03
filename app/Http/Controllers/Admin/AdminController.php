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
        $stats = [
            'total_participants' => Participant::where('status', 'paid')->count(),
            'pending_payments' => Participant::where('status', 'pending')->count(),
            'total_revenue' => Participant::where('status', 'paid')->sum('total_price'),
            'registrations_by_category' => DB::table('participants')
                ->join('tickets', 'participants.ticket_id', '=', 'tickets.id')
                ->join('categories', 'tickets.category_id', '=', 'categories.id')
                ->select('categories.name', DB::raw('count(*) as count'))
                ->where('participants.status', 'paid')
                ->groupBy('categories.name')
                ->get(),
            'is_running' => Setting::getValue('is_running', '0') === '1'
        ];

        return view('pages.admin.dashboard', compact('stats'));
    }

    public function toggleRunning()
    {
        $setting = Setting::firstOrCreate(['key' => 'is_running']);
        $setting->value = $setting->value === '1' ? '0' : '1';
        $setting->save();

        return back()->with('success', 'Site status updated successfully!');
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
}
