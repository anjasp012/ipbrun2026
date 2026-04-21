<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Voucher;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class VoucherController extends Controller
{
    public function index()
    {
        $vouchers = Voucher::with(['usages.participant', 'usages.order'])
            ->latest()
            ->paginate(50);
            
        $categories = Category::orderBy('name')->get();

        return view('pages.admin.vouchers.index', compact('vouchers', 'categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'type' => 'required|in:nominal,percentage',
            'value' => 'required|integer|min:1',
            'usage_limit' => 'nullable|integer|min:1',
            'ticket_type' => 'required|string',
            'category_id' => 'required|exists:categories,id',
            'custom_code' => 'required|string|max:20',
            'expired_at' => 'nullable|date'
        ]);

        $category = Category::findOrFail($request->category_id);
        
        $prefix = strtoupper($request->ticket_type) . '-' . strtoupper($category->name);
        $code = $prefix . '-' . strtoupper(preg_replace('/[^a-zA-Z0-9]/', '', $request->custom_code));

        $activeVoucher = Voucher::where('code', $code)->where('is_active', true)->first();
        if ($activeVoucher) {
            return back()->with('error', "Voucher dengan kode '$code' sudah ada dan sedang AKTIF. Harap nonaktifkan dulu jika ingin membuat voucher baru dengan kode ini.");
        }

        $usageLimit = $request->has('is_unlimited_usage') && $request->is_unlimited_usage == '1' ? null : $request->usage_limit;
        $expiredAt = $request->has('is_unlimited_date') && $request->is_unlimited_date == '1' ? null : $request->expired_at;

        Voucher::create([
            'code' => $code,
            'type' => $request->type,
            'value' => $request->value,
            'usage_limit' => $usageLimit,
            'expired_at' => $expiredAt,
            'ticket_type' => $request->ticket_type,
            'category_id' => $request->category_id,
        ]);

        return back()->with('success', "Voucher $code berhasil digenerate.");
    }

    public function destroy(Voucher $voucher)
    {
        if ($voucher->used_count > 0) {
            return back()->with('error', 'Voucher yang sudah digunakan tidak dapat dihapus.');
        }

        $voucher->delete();
        return back()->with('success', 'Voucher berhasil dihapus.');
    }

    public function toggleActive(Voucher $voucher)
    {
        if (!$voucher->is_active) {
            // Trying to turn ON
            $anotherActive = Voucher::where('code', $voucher->code)
                ->where('is_active', true)
                ->where('id', '!=', $voucher->id)
                ->exists();
            
            if ($anotherActive) {
                return back()->with('error', "Gagal mengaktifkan. Sudah ada voucher lain dengan kode '{$voucher->code}' yang sedang AKTIF.");
            }
        }

        $voucher->update(['is_active' => !$voucher->is_active]);
        $status = $voucher->is_active ? 'diaktifkan' : 'dinonaktifkan';
        return back()->with('success', "Voucher {$voucher->code} berhasil {$status}.");
    }
}
