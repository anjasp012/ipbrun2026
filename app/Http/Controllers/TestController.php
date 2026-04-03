<?php

namespace App\Http\Controllers;

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
        
        // 1. Get sample participant (the latest one)
        $participant = Participant::latest()->first();

        if (!$participant) {
            return back()->with('error', 'Belum ada data pendaftar di database. Silakan isi form pendaftaran sekali dulu.');
        }

        // 2. Mock password
        $mockPassword = Str::random(8);

        // 3. Generate PDF
        try {
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

            // 4. Send Email
            Mail::to($email)->send(new ParticipantPaidNotification($participant, $mockPassword, $pdfOutput));

            return back()->with('success', 'Email Test berhasil dikirim ke ' . $email . '. Silakan cek Inbox/Spam.');

        } catch (\Exception $e) {
            return back()->with('error', 'Gagal kirim email: ' . $e->getMessage());
        }
    }
}
