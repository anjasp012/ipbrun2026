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

            return \Illuminate\Support\Facades\DB::transaction(function() use ($orderCode, $transactionStatus, $fraudStatus) {
                $participant = Participant::where('order_code', $orderCode)->lockForUpdate()->first();

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
            });
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
        // 4. Send Notification Email with Credentials and Invoice PDF
        try {
            Mail::to($participant->email)->send(new ParticipantPaidNotification($participant, $randomPassword));
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Email Sending Failed', [
                'order_code' => $participant->order_code,
                'email' => $participant->email,
                'error' => $e->getMessage()
            ]);
        }
    }

    public function finish(Request $request)
    {
        $order_id = $request->query('order_id');
        $status = $request->query('transaction_status');
        
        $participant = Participant::where('order_code', $order_id)->first();
        $email = $participant ? $participant->email : null;

        return view('pages.enduser.payment_finish', [
            'order_id' => $order_id,
            'status' => $status,
            'email' => $email
        ]);
    }
}
