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
        $tickets = Ticket::with(['category', 'period'])
            ->withCount(['raceEntries' => function($q) {
                $q->whereHas('participant', function($pq) {
                    $pq->whereIn('status', ['pending', 'paid']);
                });
            }])->get();
        $periods = Period::all();
        $categories = \App\Models\Category::all();
        return view('pages.admin.tickets.index', compact('tickets', 'periods', 'categories'));
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

    public function update(Request $request, Ticket $ticket)
    {
        $validated = $request->validate([
            'name' => 'nullable|string|max:255',
            'type' => 'required|string|in:umum,ipb',
            'price' => 'required|numeric|min:0',
            'discount' => 'nullable|numeric|min:0',
            'qty' => 'required|numeric|min:0'
        ]);

        $ticket->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Tiket berhasil diperbarui!'
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'nullable|string|max:255',
            'type' => 'required|string|in:umum,ipb',
            'price' => 'required|numeric|min:0',
            'qty' => 'required|numeric|min:0',
            'category_id' => 'required|exists:categories,id',
            'period_id' => 'required|exists:periods,id'
        ]);

        Ticket::create($validated);

        return back()->with('success', 'Tiket berhasil ditambahkan!');
    }

    public function destroy(Ticket $ticket)
    {
        $ticket->delete();

        return back()->with('success', 'Tiket berhasil dihapus!');
    }
}
