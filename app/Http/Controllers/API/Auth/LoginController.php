<?php

namespace App\Http\Controllers\API\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Response;
// use Illuminate\Foundation\Auth\AuthenticatesUsers;


class LoginController extends Controller
{
    // use AuthenticatesUsers;

    public function login(Request $request)
    {
        $validator = Validator::make($request->only('username', 'password'), [
            'username' => 'required|string',
            'password' => 'required|string|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors(),
            ], 422);
        }

        $credentials = $validator->validated();

        $user = User::where('email', $credentials['username'])->first();
        if ($user && Hash::check($credentials['password'], $user->password)) {
            $token = $user->createToken('auth_token')->plainTextToken;
            return response()->json([
                'status' => 'success',
                'message' => 'Login successful',
                'token' => $token,
            ], Response::HTTP_OK);
        }else{
            return response()->json([
                'status' => 'error',
                'message' => 'Invalid credentials',
            ], Response::HTTP_UNAUTHORIZED);
        }
    }

   public function register(Request $request){

   }
}
