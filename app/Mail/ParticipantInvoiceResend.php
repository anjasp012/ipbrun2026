<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ParticipantInvoiceResend extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $participant;

    /**
     * Create a new message instance.
     */
    public function __construct($participant)
    {
        $this->participant = $participant;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'E-Invoice IPB RUN 2026 - ' . $this->participant->order_code,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.participant_invoice_resend',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, Attachment>
     */
    public function attachments(): array
    {
        $bgPath = public_path('assets/images/bg_invoice.jpg');
        if (file_exists($bgPath)) {
            $bgData = base64_encode(file_get_contents($bgPath));
            $bgBase64 = 'data:image/jpeg;base64,' . $bgData;
        } else {
            $bgBase64 = '';
        }

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('emails.invoice', [
            'participant' => $this->participant,
            'bg_base64' => $bgBase64,
        ])->setPaper('a4', 'portrait');

        return [
            \Illuminate\Mail\Mailables\Attachment::fromData(fn() => $pdf->output(), 'E-Invoice-IPB-RUN-2026.pdf')
        ];
    }
}
