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
    public string $htmlContent;
    public string $user;

    public function __construct(mixed $data, $htmlContent, $user)
    {
        // avoid using common names as they are reserved by built in variables (causes unexpected errors)

        // $this->htmlContent = $data->content ?? '[content]';
        // $this->customSubject = $data->subject ?? '[subject]';
        $this->emailSubject = $data->suject;
        $this->htmlContent = $htmlContent;
        $this->user = $user;
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
                'htmlContent' => $this->htmlContent,
                'emailSubject' => $this->emailSubject,
                'user' => $this->user,
            ],

        );
    }

    public function attachments(): array
    {
        return [];
    }
}
