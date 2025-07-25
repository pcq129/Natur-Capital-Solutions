<?php

use App\Http\Controllers\API\Auth\LoginController;
use App\HTTP\Controllers\API\EnquiryController;
use App\Http\Controllers\API\Auth\RegisterController;
use Illuminate\Support\Facades\Route;

Route::post('/user-login', [LoginController::class, 'login']);
Route::post('/user-register', [RegisterController::class, 'register']);


Route::group(['middleware' => 'auth:sanctum'], function () {
    Route::post('/product-enquiry', [EnquiryController::class, 'enquire']);
});
