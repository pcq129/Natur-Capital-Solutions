<?php

namespace App\Services;

use App\Models\EmailTemplate;
use App\Mail\EmailTemplate as Template;
use Illuminate\Support\Facades\Mail;
use App\Constants\EmailTemplateConstants as CONSTANTS;
use App\Enums\Role;

class EmailService
{
    // public function __construct()
    // {

    // }

    // METHOD TO SEND EMAIL



    /**
     * @param emailcontent the message body of mail to be sent
     * @param mixed template full database entry that holds the email template
     * which includes subject, heading, email content, role of receiver.
     * @param array dynamicdata an array containing extra variable required by a
     * each template (variable for each template).
     */
    public function sendMail($emailContent, $template, $dynamicData, $email = null)
    {
       if($this->authorizeReceiver($template)){
        $email = $dynamicData['email'] ?? $email;
        Mail::to($email)->send(new Template($emailContent, $template, $dynamicData));
        toastr()->success(CONSTANTS::REGISTRATION_SUCCESSFUL);
        return true;
       }else{
        toastr()->error(CONSTANTS::NOT_AUTHORIZED);
        return false;
       }
    }

    private function authorizeReceiver($template): bool
    {
        // just simple authorization for now, implement complex logic here when required
        // if($template->send_to == Role::ADMIN){
        //     return true;
        // }else{
            return true;
        // }
    }
}
