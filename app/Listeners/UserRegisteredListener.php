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

    /**
     * Create the event listener.
     */
    public function __construct()
    {
        $this->email = EmailTemplate::where('name', 'User Registered')->first();
    }

    /**
     * Handle the event.
     */
    public function handle(UserRegistered $event): void
    {
        $user = $event->user;

        // Prepare dynamic data for the email
        $emailContent = $this->email->trixRender('EmailTemplateContent');
        $dynamicData = [
            'user'=> $user->name,
            'email' => $user->email,
            'role' => Role::fromValue($user->role)->name, // Assuming role is stored as an enum value
        ];

        // Send the email
        if ($this->email) {
            $this->sendMail($this->email, $user->email, $dynamicData);
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


    private function sendMail($emailContent, $newUserEmail, $dynamicData)
    {
       return MAIL->sendMail($emailContent, $newUserEmail, $dynamicData);
    }
}
