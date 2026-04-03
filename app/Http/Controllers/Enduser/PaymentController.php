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
        if ($request->isMethod('GET')) {
            return response()->json(['message' => 'Payment callback endpoint is ready.'], 200);
        }

        try {
            $notification = new Notification();

            $transactionStatus = $notification->transaction_status;
            $orderCode = $notification->order_id;
            $fraudStatus = $notification->fraud_status;

            $participant = Participant::where('order_code', $orderCode)->first();

            if (!$participant) {
                return response()->json(['message' => 'Participant not found'], 404);
            }

            if ($transactionStatus == 'capture') {
                if ($fraudStatus == 'challenge') {
                    $participant->update(['status' => 'pending']);
                } else if ($fraudStatus == 'accept') {
                    $this->handleSuccessPayment($participant);
                }
            } else if ($transactionStatus == 'settlement') {
                $this->handleSuccessPayment($participant);
            } else if ($transactionStatus == 'cancel' || $transactionStatus == 'deny' || $transactionStatus == 'expire') {
                $participant->update(['status' => 'failed']);
            } else if ($transactionStatus == 'pending') {
                $participant->update(['status' => 'pending']);
            }

            return response()->json(['status' => 'success']);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    private function handleSuccessPayment($participant)
    {
        // Don't repeat if already paid
        if ($participant->status === 'paid') {
            return;
        }

        // 1. Update Participant Status
        $participant->update(['status' => 'paid']);

        // 2. Create User Account
        $randomPassword = Str::random(8);

        $user = User::create([
            'name' => $participant->name,
            'email' => $participant->email,
            'username' => $participant->email,
            'password' => Hash::make($randomPassword),
            'role' => 'participant' // Default role
        ]);

        // 3. Link Participant to User
        $participant->update(['user_id' => $user->id]);

        // 4. Generate PDF Invoice
        $bgPath = public_path('assets/images/bg_invoice.jpg');
        if (file_exists($bgPath)) {
            $bgData = base64_encode(file_get_contents($bgPath));
            $bgBase64 = 'data:image/jpeg;base64,' . $bgData;
        } else {
            $bgBase64 = '';
        }

        $pdf = Pdf::loadView('emails.invoice', [
            'participant' => $participant,
            'bg_base64' => $bgBase64,
        ])->setPaper('a4', 'portrait');

        $pdfOutput = $pdf->output();

        // 5. Send Notification Email with Credentials and Invoice PDF
        try {
            Mail::to($participant->email)->send(new ParticipantPaidNotification($participant, $randomPassword, $pdfOutput));
        } catch (\Exception $e) {
            // Log error but don't stop the flow
        }
    }
}
