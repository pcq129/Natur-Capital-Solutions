<?php

use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Admin\BannerController;
use App\Http\Controllers\Admin\CmsPageController;
use App\Http\Controllers\Admin\BranchOfficeController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\SubCategoryController;
use App\Http\Controllers\Admin\HomeController;
use App\Http\Controllers\Admin\SocialLoginController;
use App\Http\Controllers\Admin\EmailTemplateController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;


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
    Route::resource('/categories', CategoryController::class);
    Route::put('/categories/{category}/validate', [CategoryController::class, 'validateCategoryUpdate'])->name('categories.validate');
    Route::put('/sub-categories/{sub_category}/validate', [SubCategoryController::class, 'validateSubCategoryUpdate'])->name('sub-categories.validate');
    Route::post('/categories/validate', [CategoryController::class, 'validateCategoryStore'])->name('categories.validateStore');
    Route::post('/sub-categories/validate', [SubCategoryController::class, 'validateSubCategoryStore'])->name('sub-categories.validateStore');

    Route::resource('/sub-categories', SubCategoryController::class);
});


// EXPERIMENTING ROUTES
// Route::get('/mailable', function () {
//     return new App\Mail\EmailTemplate('main', 'subject');
// });
