<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Voucher;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class VoucherController extends Controller
{
    public function index()
    {
        $vouchers = Voucher::with('participant')->latest()->paginate(20);
        return view('pages.admin.vouchers.index', compact('vouchers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'count' => 'required|integer|min:1|max:500',
            'type' => 'required|in:nominal,percentage',
            'value' => 'required|integer|min:1',
            'prefix' => 'nullable|string|max:10',
        ]);

        $count = $request->count;

        for ($i = 0; $i < $count; $i++) {
            $prefix = $request->prefix ? strtoupper($request->prefix) : 'VCH';
            $code = $prefix . '-' . strtoupper(Str::random(6));
            
            // Ensure uniqueness
            while (Voucher::where('code', $code)->exists()) {
                $code = $prefix . '-' . strtoupper(Str::random(6));
            }

            Voucher::create([
                'code' => $code,
                'type' => $request->type,
                'value' => $request->value,
            ]);
        }

        return back()->with('success', "$count voucher berhasil digenerate.");
    }

    public function destroy(Voucher $voucher)
    {
        if ($voucher->is_used) {
            return back()->with('error', 'Voucher yang sudah digunakan tidak dapat dihapus.');
        }

        $voucher->delete();
        return back()->with('success', 'Voucher berhasil dihapus.');
    }
}
