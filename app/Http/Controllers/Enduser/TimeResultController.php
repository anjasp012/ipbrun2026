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
        
        // Get unique combinations of item (category) and gender
        $rawCategories = RaceResult::select('item', 'gender')
            ->groupBy('item', 'gender')
            ->orderBy('item')
            ->orderBy('gender')
            ->get();

        $categories = $rawCategories->map(function ($row) {
            $item = $row->item;
            $gender = $row->gender;
            
            // Format display name nicely (avoid duplicating gender if it's already in the category name)
            $displayName = $item;
            if ($gender) {
                $itemLower = strtolower($item);
                $genderLower = strtolower($gender);
                if (!str_contains($itemLower, $genderLower)) {
                    // Capitalize gender for presentation (Male / Female)
                    $genderDisplay = ucfirst($genderLower);
                    $displayName = $item . ' ' . $genderDisplay;
                }
            }
            
            return (object) [
                'item' => $item,
                'gender' => $gender,
                'display_name' => $displayName,
            ];
        });

        $activeItem = $request->input('item');
        $activeGender = $request->input('gender');
        
        // Default to the first category combination if none is selected
        if (empty($activeItem) && $categories->isNotEmpty()) {
            $first = $categories->first();
            $activeItem = $first->item;
            $activeGender = $first->gender;
        }

        $query = RaceResult::query();

        // Search by BIB or Name
        if ($request->filled('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('bib', 'like', "%{$search}%");
            });
        }

        // Filter by the selected item and gender combination
        if ($activeItem !== null) {
            $query->where('item', $activeItem);
        }
        
        if ($activeGender !== null && $activeGender !== '') {
            $query->where('gender', $activeGender);
        } else if ($activeItem !== null) {
            // If the selected category has some rows with null gender, filter by null
            // otherwise don't restrict if gender wasn't in the DB group
            $hasNullGender = RaceResult::where('item', $activeItem)->whereNull('gender')->exists();
            if ($hasNullGender) {
                $query->whereNull('gender');
            }
        }

        // Order by net_time as plain text, pushing empty values to the end
        $results = $query->orderByRaw("CASE WHEN net_time IS NULL OR net_time = '' THEN 1 ELSE 0 END")
            ->orderBy('net_time', 'asc')
            ->paginate(50)
            ->withQueryString();

        return view('pages.enduser.time_result', compact('categories', 'activeItem', 'activeGender', 'results', 'search'));
    }
}
