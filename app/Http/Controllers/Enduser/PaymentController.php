<?php

namespace App\Http\Controllers\Enduser;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Participant;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Mail\ParticipantPaidNotification;
use Midtrans\Config;
use Midtrans\Notification;
use Illuminate\Support\Str;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Traits\PaymentHandlerTrait;

class PaymentController extends Controller
{
    use PaymentHandlerTrait;

    public function __construct()
    {
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized = config('midtrans.is_sanitized');
        Config::$is3ds = config('midtrans.is_3ds');
    }

    public function callback(Request $request)
    {
        \Illuminate\Support\Facades\Log::info('Midtrans Callback Received', [
            'method' => $request->method(),
            'url' => $request->fullUrl(),
            'ip' => $request->ip(),
            'payload' => $request->all()
        ]);

        if ($request->isMethod('GET')) {
            return response()->json(['message' => 'Payment callback endpoint is ready.'], 200);
        }

        try {
            try {
                $notification = new Notification();
            } catch (\Exception $e) {
                return response()->json(['message' => 'Notification test received successfully.'], 200);
            }

            $transactionStatus = $notification->transaction_status;
            $orderCode = $notification->order_id;
            $fraudStatus = $notification->fraud_status;

            return \Illuminate\Support\Facades\DB::transaction(function () use ($orderCode, $transactionStatus, $fraudStatus) {
                $order = \App\Models\Order::where('order_code', $orderCode)->with('raceEntries')->first();

                if (!$order) {
                    return response()->json(['message' => 'Order not found'], 404);
                }

                $participant = $order->participant;

                if ($transactionStatus == 'capture') {
                    if ($fraudStatus == 'challenge') {
                        $order->update(['status' => 'pending']);
                        foreach ($order->raceEntries as $entry) $entry->update(['status' => 'pending']);
                    } else if ($fraudStatus == 'accept') {
                        $this->handleSuccessPayment($order, $participant);
                    }
                } else if ($transactionStatus == 'settlement') {
                    $this->handleSuccessPayment($order, $participant);
                } else if ($transactionStatus == 'cancel' || $transactionStatus == 'deny' || $transactionStatus == 'expire') {
                    $order->update(['status' => 'failed']);
                    foreach ($order->raceEntries as $entry) $entry->update(['status' => 'failed']);

                    // Send WhatsApp notification for failure
                    try {
                        $statusText = ($transactionStatus == 'expire') ? 'Telah Kadaluarsa' : 'Gagal';
                        $message = "📢 *Tiket Expired – IPB Run 2026*\n\n" .
                            "Halo *{$participant->name}*!\n\n" .
                            "Pembayaran untuk kode order *{$orderCode}* dinyatakan *{$statusText}*.\n" .
                            "Jika ini adalah kesalahan, Anda dapat mencoba mendaftar kembali.\n\n" .
                            "Terima kasih.";
                        \App\Jobs\SendWhatsAppBlast::dispatch($participant->phone_number, $message);
                    } catch (\Exception $e) {
                        \Illuminate\Support\Facades\Log::error('Fonnte failure notification failed: ' . $e->getMessage());
                    }
                } else if ($transactionStatus == 'pending') {
                    $order->update(['status' => 'pending']);
                    foreach ($order->raceEntries as $entry) {
                        $entry->update(['status' => 'pending']);
                    }
                }

                return response()->json(['status' => 'success']);
            });
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function finish(Request $request)
    {
        $order_id = $request->query('order_id');
        $status = $request->query('transaction_status');

        $order = \App\Models\Order::where('order_code', $order_id)->first();
        $email = $order ? $order->participant->email : null;

        return view('pages.enduser.payment_finish', [
            'order_id' => $order_id,
            'status' => $status,
            'email' => $email
        ]);
    }
}
