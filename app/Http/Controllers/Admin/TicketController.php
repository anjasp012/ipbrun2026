<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Ticket;
use App\Models\Period;

class TicketController extends Controller
{
    public function index()
    {
        $tickets = Ticket::with(['category', 'period'])->get();
        $periods = Period::all();
        return view('pages.admin.tickets.index', compact('tickets', 'periods'));
    }

    public function togglePeriod(Period $period)
    {
        // Deactivate all periods
        Period::where('id', '!=', $period->id)->update(['is_active' => false]);
        
        // Toggle (or just set true since only one can be active)
        $period->is_active = true;
        $period->save();

        return back()->with('success', "Periode {$period->name} sekarang aktif!");
    }
}
