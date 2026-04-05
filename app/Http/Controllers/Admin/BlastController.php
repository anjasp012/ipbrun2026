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
            $recipient = trim($recipient);
            if (filter_var($recipient, FILTER_VALIDATE_EMAIL)) {
                Mail::to($recipient)->queue(new PromotionBlast($request->subject, $request->message));
                $count++;
            }
        }

        return back()->with('success', "Success! {$count} emails have been added to the queue for background delivery.");
    }

    public function blastWhatsapp(Request $request)
    {
        $request->validate([
            'targets' => 'required|string',
            'message' => 'required|string'
        ]);

        $recipients = array_filter(preg_split("/[\s,]+/", $request->targets));
        $count = 0;

        foreach ($recipients as $recipient) {
            $recipient = trim($recipient);
            if (!empty($recipient)) {
                \App\Jobs\SendWhatsAppBlast::dispatch($recipient, $request->message);
                $count++;
            }
        }

        return back()->with('success', "Success! {$count} WhatsApp messages have been added to the queue for background delivery.");
    }
}
