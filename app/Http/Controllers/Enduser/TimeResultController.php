<?php

namespace App\Http\Controllers\Enduser;

use App\Http\Controllers\Controller;
use App\Models\RaceResult;
use Illuminate\Http\Request;

class TimeResultController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        
        // Get all unique tabs
        $categories = RaceResult::select('tab')
            ->distinct()
            ->whereNotNull('tab')
            ->orderBy('tab')
            ->pluck('tab');

        $activeTab = $request->input('tab', 'SEMUA');

        $query = RaceResult::query();

        // Search by BIB or Name
        if ($request->filled('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('bib', 'like', "%{$search}%");
            });
        }

        // Filter by the selected tab name (skip if SEMUA)
        if ($activeTab !== null && $activeTab !== 'SEMUA') {
            $query->where('tab', $activeTab);
        } elseif ($activeTab === 'SEMUA') {
            // Filter based on CP2 (only show rows where CP2 is recorded)
            $query->whereNotNull('cp2')->where('cp2', '!=', '');
        }

        // Order by id (order of excel import / data entry)
        $results = $query->orderBy('id', 'asc')
            ->paginate(50)
            ->withQueryString();

        return view('pages.enduser.time_result', compact('categories', 'activeTab', 'results', 'search'));
    }
}
