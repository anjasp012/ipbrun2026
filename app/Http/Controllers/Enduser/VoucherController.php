<?php 

namespace App\Http\Controllers\Enduser;

use App\Http\Controllers\Controller;
use App\Models\Participant;
use App\Models\Voucher;
use App\Models\VoucherUsage;
use Illuminate\Http\Request;

class VoucherController extends Controller
{
    public function check(Request $request)
    {
        $nik = $request->nik;
        $code = $request->code;
        $price = $request->price;
        
        $voucher = Voucher::findValid($code);

        if (!$voucher) {
            return response()->json(['valid' => false, 'message' => 'Kode voucher tidak valid.']);
        }

        // 1. Check if active and global usage limit
        if (!$voucher->isAvailable()) {
            return response()->json(['valid' => false, 'message' => 'Maaf, voucher tidak tersedia atau sudah habis/kedaluwarsa.']);
        }

        // 2. Check ticket compatibility if ticket_id provided
        if ($request->ticket_id) {
            $ticket = \App\Models\Ticket::find($request->ticket_id);
            if ($ticket) {
                if ($voucher->ticket_type && strtolower($voucher->ticket_type) !== strtolower($ticket->type)) {
                    return response()->json(['valid' => false, 'message' => 'Voucher tidak berlaku untuk tipe tiket ini.']);
                }
                if ($voucher->category_id && $voucher->category_id !== $ticket->category_id) {
                    return response()->json(['valid' => false, 'message' => 'Voucher tidak berlaku untuk kategori tiket ini.']);
                }
            }
        }

        // 3. Check for duplicate usage by category/type
        if ($nik) {
            $participant = Participant::where('nik', $nik)->first();
            if ($participant && $voucher->isDuplicateForParticipant($participant->id)) {
                return response()->json(['valid' => false, 'message' => 'Anda sudah menggunakan voucher untuk kategori dan tipe tiket ini.']);
            }
        }

        // Check against existing_codes in current session
        if ($request->has('existing_codes') && is_array($request->existing_codes)) {
            foreach ($request->existing_codes as $exCode) {
                if (!$exCode) continue;
                $exVoucher = Voucher::where('code', $exCode)->where('is_active', true)->first();
                if ($exVoucher && $exVoucher->category_id === $voucher->category_id && $exVoucher->ticket_type === $voucher->ticket_type) {
                    return response()->json(['valid' => false, 'message' => 'Anda sudah memasang voucher untuk kategori dan tipe tiket ini.']);
                }
            }
        }

        $discount = $voucher->calculateDiscount($price);

        return response()->json([
            'valid' => true,
            'discount' => $discount,
            'type' => $voucher->type,
            'value' => $voucher->value,
            'code' => $voucher->code
        ]);
    }
}
