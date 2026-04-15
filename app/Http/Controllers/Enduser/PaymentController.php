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

class PaymentController extends Controller
{
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

    private function handleSuccessPayment($order, $participant)
    {
        // Don't repeat if already paid
        if ($order->status === 'paid') {
            return;
        }

        // 1. Update Order & Race Entry Status
        $order->update(['status' => 'paid']);
        foreach ($order->raceEntries as $entry) {
            $entry->update(['status' => 'paid']);
        }

        $entries = $order->raceEntries; 

        // 2. Create or Get User Account & Determine Credentials
        $isCommunity = $participant->is_community;
        $user = null;
        $userExists = false;
        $password = "";
        $loginIdentifier = "";

        if ($isCommunity) {
            $loginIdentifier = $participant->nik;
            $userExists = User::where('username', $participant->nik)->exists();
            $password = $participant->nik; // Credential for community is always NIK

            $user = User::updateOrCreate(
                ['username' => $participant->nik],
                [
                    'name' => $participant->name,
                    'email' => $participant->email,
                    'password' => Hash::make($password),
                    'role' => 'participant'
                ]
            );
        } else {
            $loginIdentifier = $participant->email;
            $userExists = User::where('email', $participant->email)->exists();
            $password = Str::random(8);

            $user = User::firstOrCreate(
                ['email' => $participant->email],
                [
                    'name' => $participant->name,
                    'username' => $participant->email,
                    'password' => Hash::make($password),
                    'role' => 'participant'
                ]
            );
        }

        // 3. Link Participant to User
        $participant->update(['user_id' => $user->id]);

        // 4. Send Email Notification
        try {
            \App\Jobs\SendQueuedEmail::dispatch($participant->email, new ParticipantPaidNotification($participant, $password, $order, $userExists));
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Email Queuing Failed: ' . $e->getMessage());
        }

        // 5. Send WhatsApp Notification
        try {
            $url = "https://dev.ipbrun2026.id/login";
            
            if ($isCommunity) {
                if ($userExists) {
                    $message = "📢 *Konfirmasi Pendaftaran Komunitas – IPB Run 2026*\n\n" .
                        "Halo *{$participant->name}*,\n\n" .
                        "Pembayaran untuk kode order *{$order->order_code}* telah berhasil dikonfirmasi ✅\n" .
                        "*Selamat! Tiket komunitas kamu resmi terdaftar di IPB Run 2026* 🏁\n\n" .
                        "Silakan cek detail tiket kamu di dashboard menggunakan akun NIK yang sudah ada:\n" .
                        "URL: {$url}\n\n" .
                        "Sampai jumpa di garis start! 🏃‍♂️🔥";
                } else {
                    $message = "📢 *Konfirmasi Pendaftaran Komunitas – IPB Run 2026*\n\n" .
                        "Halo *{$participant->name}*,\n\n" .
                        "Pembayaran untuk kode order *{$order->order_code}* telah berhasil dikonfirmasi ✅\n" .
                        "*Selamat! Kamu resmi terdaftar sebagai peserta IPB Run 2026* 🏁\n\n" .
                        "🔐 Gunakan NIK kamu untuk akses dashboard:\n" .
                        "Username: *{$participant->nik}*\n" .
                        "Password: *{$participant->nik}*\n" .
                        "URL: {$url}\n\n" .
                        "Sampai jumpa di garis start! 🏃‍♂️🔥";
                }
            } else {
                // Regular Flow
                if ($userExists) {
                    $message = "📢 *Konfirmasi Pembayaran Tambahan – IPB Run 2026*\n\n" .
                        "Halo *{$participant->name}*,\n\n" .
                        "Pembayaran untuk kode order *{$order->order_code}* telah berhasil dikonfirmasi ✅\n" .
                        "*Selamat! Tiket tambahan kamu resmi terdaftar di IPB Run 2026* 🏁\n\n" .
                        "Silakan cek detail tiket baru kamu di dashboard:\n" .
                        "URL: {$url}\n\n" .
                        "Terima kasih atas partisipasinya,\n" .
                        "Sampai jumpa di garis start! 🏃‍♂️🔥";
                } else {
                    $message = "📢 *Konfirmasi Pendaftaran – IPB Run 2026*\n\n" .
                        "*Halo {$participant->name}*,\n\n" .
                        "Pembayaran untuk kode order *{$order->order_code}* telah berhasil dikonfirmasi ✅\n" .
                        "*Selamat! Kamu resmi menjadi peserta IPB Run 2026* 🏁\n\n" .
                        "🔐 Berikut akses dashboard kamu:\n" .
                        "Email: *{$participant->email}*\n" .
                        "Password: *{$password}*\n" .
                        "URL: {$url}\n\n" .
                        "Terima kasih atas partisipasinya,\n" .
                        "Sampai jumpa di garis start! 🏃‍♂️🔥";
                }
            }
            \App\Jobs\SendWhatsAppBlast::dispatch($participant->phone_number, $message);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Fonnte success notification failed: ' . $e->getMessage());
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
