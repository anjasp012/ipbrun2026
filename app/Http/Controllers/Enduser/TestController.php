<?php

namespace App\Http\Controllers\Enduser;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Participant;
use App\Mail\ParticipantPaidNotification;
use Illuminate\Support\Facades\Mail;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Str;

class TestController extends Controller
{
    public function emailForm()
    {
        return view('pages.test.email');
    }

    public function sendEmail(Request $request)
    {
        $request->validate([
            'email' => 'required|email'
        ]);

        $email = $request->email;
        
        // 1. Get sample order (the latest one)
        $order = \App\Models\Order::with('raceEntries.ticket.category')->latest()->first();

        if (!$order) {
            return back()->with('error', 'Belum ada data pesanan (Order) di database. Silakan isi form pendaftaran sekali dulu.');
        }

        $participant = $order->participant;

        // 2. Mock password
        $mockPassword = Str::random(8);

        // 3. Send Email
        try {
            Mail::to($email)->send(new ParticipantPaidNotification($participant, $mockPassword, $order));

            return back()->with('success', 'Email Test berhasil dikirim ke ' . $email . '. Silakan cek Inbox/Spam.');

        } catch (\Exception $e) {
            return back()->with('error', 'Gagal kirim email: ' . $e->getMessage());
        }
    }
}
