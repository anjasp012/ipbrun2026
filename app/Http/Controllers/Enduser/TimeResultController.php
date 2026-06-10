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
            // Show all rows that have a recorded gun time (finished runners)
            $query->whereNotNull('gun_time')->where('gun_time', '!=', '');
        }

        // Order by gun_time ascending (fastest first), nulls last
        $results = $query->orderByRaw('CASE WHEN gun_time IS NULL OR gun_time = \'\' THEN 1 ELSE 0 END ASC')
            ->orderBy('gun_time', 'asc')
            ->paginate(50)
            ->withQueryString();

        return view('pages.enduser.time_result', compact('categories', 'activeTab', 'results', 'search'));
    }
}
