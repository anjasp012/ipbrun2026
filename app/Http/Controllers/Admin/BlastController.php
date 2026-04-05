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
            'targets' => 'required|string',
            'subject' => 'required|string|max:255',
            'message' => 'required|string'
        ]);

        $recipients = array_filter(preg_split("/[\s,]+/", $request->targets));
        $count = 0;

        foreach ($recipients as $recipient) {
            try {
                if (filter_var($recipient, FILTER_VALIDATE_EMAIL)) {
                    Mail::to(trim($recipient))->send(new PromotionBlast($request->subject, $request->message));
                    $count++;
                }
            } catch (\Exception $e) {
                // Skip failed emails
            }
        }

        return back()->with('success', "Email blast sent successfully to {$count} recipients.");
    }

    public function blastWhatsapp(Request $request)
    {
        $request->validate([
            'targets' => 'required|string',
            'message' => 'required|string'
        ]);

        $fonnte = new FonnteService();
        $recipients = array_filter(preg_split("/[\s,]+/", $request->targets));
        $count = 0;

        foreach ($recipients as $recipient) {
            $response = $fonnte->sendMessage(trim($recipient), $request->message);
            if (isset($response['status']) && $response['status']) {
                $count++;
            }
        }

        return back()->with('success', "WhatsApp blast sent successfully to {$count} recipients.");
    }
}
