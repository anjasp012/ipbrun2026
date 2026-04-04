<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ParticipantPaidNotification extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $participant;
    public $password;
    public $invoicePdf;

    /**
     * Create a new message instance.
     */
    public function __construct($participant, $password, $invoicePdf = null)
    {
        $this->participant = $participant;
        $this->password = $password;
        $this->invoicePdf = $invoicePdf;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Konfirmasi Pembayaran & Akun IPB RUN 2026 - ' . $this->participant->order_code,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.participant_paid',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, Attachment>
     */
    public function attachments(): array
    {
        $attachments = [];
        if ($this->invoicePdf) {
            $attachments[] = \Illuminate\Mail\Mailables\Attachment::fromData(fn() => $this->invoicePdf, 'E-Invoice-IPB-RUN-2026.pdf');
        }
        return $attachments;
    }
}
