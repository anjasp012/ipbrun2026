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
        
        // Get all unique categories (items)
        $categories = RaceResult::select('item')
            ->distinct()
            ->orderBy('item')
            ->pluck('item');

        $activeCategory = $request->input('category');
        
        // Default to the first category if none is selected
        if (empty($activeCategory) && $categories->isNotEmpty()) {
            $activeCategory = $categories->first();
        }

        $query = RaceResult::query();

        // If searching, search by BIB or Name
        if ($request->filled('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('bib', 'like', "%{$search}%");
            });
        }

        // If there's an active category, filter by it (or search globally if category is set to 'all', but tabs are better)
        if ($activeCategory) {
            $query->where('item', $activeCategory);
        }

        // Order by net_time as plain text, pushing empty values to the end
        $results = $query->orderByRaw("CASE WHEN net_time IS NULL OR net_time = '' THEN 1 ELSE 0 END")
            ->orderBy('net_time', 'asc')
            ->paginate(50)
            ->withQueryString();

        return view('pages.enduser.time_result', compact('categories', 'activeCategory', 'results', 'search'));
    }
}
