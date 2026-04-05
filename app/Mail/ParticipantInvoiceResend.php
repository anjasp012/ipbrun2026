<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ParticipantInvoiceResend extends Mailable
{
    use Queueable, SerializesModels;
 
    public $participant;
    public $orders;
    public $password;

    /**
     * Create a new message instance.
     */
    public function __construct($participant, $orders, $password)
    {
        $this->participant = $participant;
        $this->orders = $orders;
        $this->password = $password;
    }
 
    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'RESEND: E-Invoice & Account Details - IPB RUN 2026',
        );
    }
 
    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.participant_paid',
            with: [
                'userExists' => false, // ALWAYS show login details for resend (contains NEW password)
                'order' => $this->orders->first() // For simple summary in body
            ]
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

        $attachments = [];
        
        foreach ($this->orders as $order) {
            $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('emails.invoice', [
                'participant' => $this->participant,
                'order' => $order,
                'bg_base64' => $bgBase64,
            ])->setPaper('a4', 'portrait');

            $attachments[] = \Illuminate\Mail\Mailables\Attachment::fromData(
                fn() => $pdf->output(), 
                'Invoice-' . $order->order_code . '.pdf'
            );
        }

        return $attachments;
    }
}
