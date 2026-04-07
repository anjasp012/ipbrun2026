<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PromotionBlast extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $subjectStr;
    public $messageStr;
    public $attachmentPath;

    /**
     * Create a new message instance.
     */
    public function __construct($subjectStr, $messageStr, $attachmentPath = null)
    {
        $this->subjectStr = $subjectStr;
        $this->messageStr = $messageStr;
        $this->attachmentPath = $attachmentPath;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: $this->subjectStr,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.promotion_blast',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        if ($this->attachmentPath && file_exists(storage_path('app/' . $this->attachmentPath))) {
            return [
                \Illuminate\Mail\Mailables\Attachment::fromPath(storage_path('app/' . $this->attachmentPath))
            ];
        }
        return [];
    }
}
