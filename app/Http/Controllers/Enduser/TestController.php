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
        
        // 1. Logic Pencarian Data: Prioritaskan Email pendaftar, lalu fallback ke data terbaru
        $query = \App\Models\Order::with('raceEntries.ticket.category');
        
        // Coba cari berdasarkan Email Peserta dulu jika terdaftar
        $participantMatch = Participant::where('email', $email)->first();
        
        if ($participantMatch) {
            $order = $query->where('participant_id', $participantMatch->id)->latest()->first();
        } else {
            $order = $query->latest()->first();
        }

        if (!$order) {
            return back()->with('error', 'Data tidak ditemukan. Silakan isi form pendaftaran sekali dulu agar ada data contoh.');
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
