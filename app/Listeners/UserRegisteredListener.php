<?php

namespace App\Listeners;

use App\Events\UserRegistered;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Models\EmailTemplate;
use App\Constants\AppConstants as CONSTANTS;
use App\Enums\Role;
use App\Services\EmailService as MAIL;

class UserRegisteredListener
{
    protected $email;
    protected $MAIL;

    /**
     * Create the event listener.
     */
    public function __construct()
    {
        $this->email = EmailTemplate::where('name', 'User Registered')->first();
        $this->MAIL = new MAIL();
    }

    /**
     * Handle the event.
     */
    public function handle(UserRegistered $event): void
    {
        $user = $event->user;

        // Prepare dynamic data for the email
        $emailContent = $this->email->trixRender('EmailTemplateContent');
        // dd($emailContent);
        $dynamicData = [
            'user'=> $user->name,
            'email' => $user->email,
            'role' => $user->role->name, // Assuming role is stored as an enum value
        ];

        // Send the email
        if ($this->email) {
            $this->sendMail($emailContent, $this->email, $dynamicData);
        }else{
            // Handle the case where the email template is not found
            \Log::error('Email template for User Registered not found.');
        }
    }

    //     if($email){
    //         $emailContent = $email->trixRender('EmailTemplateContent');
    //         $dynamicData = [
    //             'user' => $newUser->name,
    //             'email' => $newUser->email,
    //             'role' => 'Admin',
    //         ];
    //         $status = $this->sendMail($emailContent, $email, $dynamicData);
    //         if($status){
    //             $newUser->save();
    //             return $newUser;
    //         }else{
    //             return null;
    //         }
    //     }else{
    //         toastr()->error(CONSTANTS::EMAIL_NOT_FOUND);
    //     }


    // }


    private function sendMail($emailContent, $emailData, $dynamicData)
    {
       return $this->MAIL->sendMail($emailContent, $emailData, $dynamicData);
    }
}
