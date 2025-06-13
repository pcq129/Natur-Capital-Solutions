<?php

use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\BannerController;
use App\Http\Controllers\CmsPageController;
use App\Http\Controllers\BranchOfficeController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SocialLoginController;
use App\Http\Controllers\EmailTemplateController;



Route::post('/register', [RegisterController::class, 'register']);
Route::get('auth/{provider}/redirect', [SocialLoginController::class , 'redirect'])->name('auth.socialite.redirect');
Route::get('auth/{provider}/callback', [SocialLoginController::class , 'callback'])->name('auth.socialite.callback');
Auth::routes();

Route::middleware('auth')->group(function(){
    Route::get('/', [HomeController::class, 'index'])->name('home');
    Route::resource('/banners', BannerController::class);
    Route::resource('/branchoffices', BranchOfficeController::class);
    Route::resource('/cms-pages', CmsPageController::class);
    Route::resource('/email-templates', EmailTemplateController::class);
});


// EXPERIMENTING ROUTES
// Route::get('/mailable', function () {
//     return new App\Mail\EmailTemplate('main', 'subject');
// });
Route::get('/test-mail', [EmailTemplateController::class,'sendmail']);