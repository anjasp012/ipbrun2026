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
            
            $itemUpper = strtoupper($item);
            
            if ($gender) {
                $genderClean = strtoupper(trim($gender));
                $genderUpper = $genderClean;
                if ($genderClean === 'M' || $genderClean === 'L' || $genderClean === 'MALE' || $genderClean === 'LAKI') {
                    $genderUpper = 'MALE';
                } elseif ($genderClean === 'F' || $genderClean === 'P' || $genderClean === 'FEMALE' || $genderClean === 'PEREMPUAN') {
                    $genderUpper = 'FEMALE';
                }
                
                // If the item name already contains MALE or FEMALE, just use it capitalized
                if (str_contains($itemUpper, 'MALE') || str_contains($itemUpper, 'FEMALE')) {
                    $displayName = $itemUpper;
                } else {
                    // Split the item to insert gender after the first word (usually "10K" or "5K")
                    $words = explode(' ', $itemUpper);
                    if (count($words) > 1) {
                        array_splice($words, 1, 0, $genderUpper);
                        $displayName = implode(' ', $words);
                    } else {
                        $displayName = $itemUpper . ' ' . $genderUpper;
                    }
                }
            } else {
                $displayName = $itemUpper;
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

        // Order by id (order of excel import / data entry)
        $results = $query->orderBy('id', 'asc')
            ->paginate(50)
            ->withQueryString();

        return view('pages.enduser.time_result', compact('categories', 'activeItem', 'activeGender', 'results', 'search'));
    }
}
