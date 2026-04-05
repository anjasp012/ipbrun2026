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
            'email' => 'required|email',
            'order_code' => 'nullable|string'
        ]);

        $email = $request->email;
        $orderCode = $request->order_code;
        
        // 1. Logic Pencarian Data: Prioritaskan Email pendaftar, lalu Order Code, lalu Latest
        $query = \App\Models\Order::with('raceEntries.ticket.category');
        
        // Coba cari berdasarkan Email Peserta dulu jika terdaftar
        $participantMatch = Participant::where('email', $email)->first();
        
        if ($participantMatch) {
            $order = $query->where('participant_id', $participantMatch->id)->latest()->first();
        } elseif ($orderCode) {
            $order = $query->where('order_code', 'LIKE', "%{$orderCode}%")->first();
        } else {
            $order = $query->latest()->first();
        }

        if (!$order) {
            return back()->with('error', 'Data tidak ditemukan. Pastikan email atau Kode Order sudah benar.');
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
