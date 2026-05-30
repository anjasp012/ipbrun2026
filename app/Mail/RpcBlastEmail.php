<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\Participant;

class RpcBlastEmail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $participant;

    /**
     * Create a new message instance.
     */
    public function __construct(Participant $participant)
    {
        $this->participant = $participant;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'IPB RUN RACEPACK DAY 2026',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.rpc_blast',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        // Load the participant and their paid entries so the view has access to them
        $this->participant->load(['raceEntries' => function($q) {
            $q->where('status', 'paid')->with('ticket.category');
        }]);

        // Generate the PDF
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('emails.rpc_pdf', [
            'participant' => $this->participant,
        ])->setPaper('a4', 'portrait');

        return [
            \Illuminate\Mail\Mailables\Attachment::fromData(fn() => $pdf->output(), 'Tanda-Pengambilan-RPC-IPBRUN.pdf')
        ];
    }
}
