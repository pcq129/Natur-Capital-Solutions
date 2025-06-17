<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Enums\Role;
use App\Mail\EmailTemplate as Template;
use Illuminate\Http\Request;
use Illuminate\Auth\Events\Registered;
use App\Models\EmailTemplate;
use App\Constants\AppConstants as CONSTANTS;
use App\Services\EmailService as MAIL;



class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(private MAIL $MAIL)
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    protected function create(array $data)
    {

        // dd($data);

        $email = EmailTemplate::where('name', 'User Registered')->first();
        $newUser = new User();
        $newUser->role = Role::ADMIN;
        $newUser->company = CONSTANTS::COMPANY_NAME;
        $newUser->name = $data['name'];
        $newUser->email = $data['email'];
        $newUser->password = Hash::make($data['password']);
        if($email){
            $emailContent = $email->trixRender('EmailTemplateContent');
            $dynamicData = [
                'user' => $newUser->name,
                'email' => $newUser->email,
                'role' => 'Admin',
            ];
            $status = $this->sendMail($emailContent, $email, $dynamicData);
            if($status){
                $newUser->save();
                return $newUser;
            }else{
                return null;
            }
        }else{
            toastr()->error(CONSTANTS::EMAIL_NOT_FOUND);
        }


    }

    private function sendMail($emailContent, $newUserEmail, $dynamicData)
    {
       return $this->MAIL->sendMail($emailContent, $newUserEmail, $dynamicData);
    }
}
