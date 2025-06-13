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
use App\Services\EmailTemplateService as MAIL;



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
        $email = EmailTemplate::where('name', 'User Registered')->first();
        $newUser = User::create([
            'role' => Role::ADMIN,
            'company' => CONSTANTS::COMPANY_NAME,
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        $emailContent = $email->trixRender('EmailTemplateContent');
        $dynamicData = [
            'user' => $newUser->name,
            'email' => $newUser->email,
            'role' => 'Admin',
        ];
        $this->MAIL::sendMail($emailContent, $newUser->email, $dynamicData);
        toastr()->success(CONSTANTS::REGISTRATION_SUCCESSFUL);
        return $newUser;
    }
}
