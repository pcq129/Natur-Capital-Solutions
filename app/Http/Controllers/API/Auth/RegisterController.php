<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\RegisterUserRequest;
use App\Models\User;
use App\Enums\Status;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Response;
use App\Events\UserRegistered;


class RegisterController extends Controller
{
    public function register(RegisterUserRequest $request){
        $userData = $request->validated();

        $user = User::create([
            'name' => $userData['name'],
            'email' => $userData['email'],
            'phone' => $userData['phone'],
            'password' => bcrypt($userData['password']),
            'company' => $userData['company'],
            'city' => $userData['city'],
            'country' => $userData['country'], // Optional field
            'role' => '2', //user // Default role, can be changed later
            'status' => Status::ACTIVE, // Default status
        ]);

        event(new UserRegistered($user));

        return response()->json([
            'message' => 'User registered successfully',
            'user' => $user->only(['id', 'name', 'email', 'phone', 'company', 'city', 'country', 'role', 'status']),
        ], Response::HTTP_CREATED);
    }
}
