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
            'message' => 'required|string',
            'attachment' => 'nullable|file|mimes:jpg,jpeg,png,pdf,doc,docx|max:5120'
        ]);

        $attachmentPath = null;
        if ($request->hasFile('attachment')) {
            $attachmentPath = $request->file('attachment')->store('attachments', 'public');
        }

        $recipients = array_filter(preg_split("/[\s,]+/", $request->targets));
        $count = 0;

        foreach ($recipients as $index => $recipient) {
            $recipient = trim($recipient);
            if (filter_var($recipient, FILTER_VALIDATE_EMAIL)) {
                \App\Jobs\SendQueuedEmail::dispatch($recipient, new PromotionBlast($request->subject, $request->message, $attachmentPath));
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

        foreach ($recipients as $index => $recipient) {
            $recipient = trim($recipient);
            if (!empty($recipient)) {
                \App\Jobs\SendWhatsAppBlast::dispatch($recipient, $request->message)->delay(now()->addSeconds($index * 5));
                $count++;
            }
        }

        return back()->with('success', "Success! {$count} WhatsApp messages have been added to the queue for background delivery.");
    }
}
