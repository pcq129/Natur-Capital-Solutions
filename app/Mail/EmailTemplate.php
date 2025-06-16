<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Blade;

class EmailTemplate extends Mailable
{
    use Queueable, SerializesModels;

    // public string $htmlContent;
    // public string $customSubject;
    // public string $data;
    public string $htmlContent;
    public $data;
    public $emailSubject;


    /**
     * @param  trixRichText,
     * @param  template stored in database
     * @param  ExtraVariables required for efficient data display.
     * @return void
     */
    public function __construct($htmlContent, mixed $template, array $dynamicData)
    {
        // avoid using common names as they are reserved by built in variables (causes unexpected errors)
        $this->emailSubject = $template->subject;
        $this->htmlContent = $this->renderHtml($htmlContent, $dynamicData);
        $this->data = $dynamicData;

    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: $this->emailSubject
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'mail.email-template',
            with: [
                'htmlContent' => $this->htmlContent,
                'emailSubject' => $this->emailSubject,
                'data' => $this->data
            ],
        );
    }

    public function attachments(): array
    {
        return [];
    }

    private function renderHtml($base, array $data)
    {
        $html = Blade::render($base, $data);
        return $html;
    }
}
