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
        
        $voucher = Voucher::where('code', $code)->first();

        if (!$voucher) {
            return response()->json(['valid' => false, 'message' => 'Kode voucher tidak valid.']);
        }

        // 1. Check global usage limit
        if (!$voucher->isAvailable()) {
            return response()->json(['valid' => false, 'message' => 'Maaf, kuota pemakaian voucher ini sudah habis.']);
        }

        // 3. Check if THIS participant has used it
        if ($nik) {
            $participant = Participant::where('nik', $nik)->first();
            if ($participant) {
                $alreadyUsed = VoucherUsage::where('voucher_id', $voucher->id)
                    ->where('participant_id', $participant->id)
                    ->exists();
                if ($alreadyUsed) {
                    return response()->json(['valid' => false, 'message' => 'Voucher ini sudah pernah Anda gunakan.']);
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
