<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class EmailTemplate extends Mailable
{
    use Queueable, SerializesModels;

    // public string $htmlContent;
    // public string $customSubject;
    public string $data;

    public function __construct(mixed $data)
    {
        // avoid using common names as they are reserved by built in variables (causes unexpected errors)

        // $this->htmlContent = $data->content ?? '[content]';
        // $this->customSubject = $data->subject ?? '[subject]';
        $this->data = $data;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Hello world'
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'mail.email-template',
            with: [
                'data' => $this->data,
            ]
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
