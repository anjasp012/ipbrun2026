<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RaceResult;
use App\Imports\RaceResultImport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class RaceResultController extends Controller
{
    public function index(Request $request)
    {
        $query = RaceResult::query();

        // Search by BIB or Name
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('bib', 'like', "%{$search}%");
            });
        }

        // Filter by Item (Category)
        if ($request->filled('item')) {
            $query->where('item', $request->item);
        }

        $results = $query->orderBy('id', 'asc')
            ->paginate(50);

        // Get unique items/categories for filter dropdown
        $items = RaceResult::select('item')->distinct()->pluck('item');

        return view('pages.admin.time-result.index', compact('results', 'items'));
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls,csv',
        ]);

        try {
            $import = new RaceResultImport();
            Excel::import($import, $request->file('file'));

            $successCount = $import->getSuccessCount();
            $errors = $import->getErrors();

            if (!empty($errors)) {
                session()->flash('import_errors', $errors);
                session()->flash('warning', "Successfully imported/updated {$successCount} race results. However, there were some errors/warnings. Check details below.");
            } else {
                session()->flash('success', "Successfully imported/updated {$successCount} race results.");
            }

            return back();
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to import Excel file: ' . $e->getMessage());
        }
    }

    public function destroy()
    {
        try {
            RaceResult::truncate();
            return back()->with('success', 'All race results have been successfully cleared.');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to clear race results: ' . $e->getMessage());
        }
    }
}
