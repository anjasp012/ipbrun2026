<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Participant;
use App\Models\User;
use App\Mail\PromotionBlast;
use App\Services\FonnteService;
use Illuminate\Support\Facades\Mail;

class BlastController extends Controller
{
    public function index()
    {
        return view('pages.admin.blast.index');
    }

    public function blastEmail(Request $request)
    {
        $request->validate([
            'subject' => 'required|string|max:255',
            'message' => 'required|string'
        ]);

        $participants = Participant::whereNotNull('email')->distinct('email')->get();
        $count = 0;

        foreach ($participants as $participant) {
            try {
                Mail::to($participant->email)->send(new PromotionBlast($request->subject, $request->message));
                $count++;
            } catch (\Exception $e) {
                // Skip failed emails
            }
        }

        return back()->with('success', "Email blast sent successfully to {$count} participants.");
    }

    public function blastWhatsapp(Request $request)
    {
        $request->validate([
            'message' => 'required|string'
        ]);

        $fonnte = new FonnteService();
        $participants = Participant::whereNotNull('phone_number')->get();
        $count = 0;

        foreach ($participants as $participant) {
            $response = $fonnte->sendMessage($participant->phone_number, $request->message);
            if (isset($response['status']) && $response['status']) {
                $count++;
            }
        }

        return back()->with('success', "WhatsApp blast sent successfully to {$count} participants.");
    }
}
