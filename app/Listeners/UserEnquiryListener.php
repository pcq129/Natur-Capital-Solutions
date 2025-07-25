<?php

namespace App\Listeners;

use App\Events\UserEnquiry;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Models\EmailTemplate;
use App\Enums\Email\EmailTypes;
use App\Services\EmailService as MAIL;
use App\Mail\EmailTemplate as TEMPLATE;

class UserEnquiryListener
{
    /**
     * Create the event listener.
     */
    public function __construct(protected MAIL $MAIL)
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(UserEnquiry $event): void
    {
        $enquiry = $event->enquiry;
        $user = $event->user;

        $emailTemplate = EmailTemplate::where('name', 'User Enquiry')->first();
        $emailContent = $emailTemplate->trixRender('EmailTemplateContent');

        $this->MAIL->sendMail($emailContent, $emailTemplate, [
            'user' => $user->name,
            'email' => $user->email,
            'enquiry' => $enquiry->message, // Assuming the enquiry has a message field
        ], $user->email);

        // also handle sending email to the admin here.

    }
}
