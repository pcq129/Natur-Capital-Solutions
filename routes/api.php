<?php

use App\Http\Controllers\API\Auth\LoginController;
use App\Http\Controllers\API\Auth\RegisterController;
use Illuminate\Support\Facades\Route;

Route::post('/user-login', [LoginController::class, 'login']);
Route::post('/user-register', [RegisterController::class, 'register']);
