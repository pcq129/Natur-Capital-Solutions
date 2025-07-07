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
use App\Http\Controllers\Admin\ProductController;


Route::post('/register', [RegisterController::class, 'register']);
Route::get('auth/{provider}/redirect', [SocialLoginController::class, 'redirect'])->name('auth.socialite.redirect');
Route::get('auth/{provider}/callback', [SocialLoginController::class, 'callback'])->name('auth.socialite.callback');
Auth::routes();

Route::middleware('auth')->group(function () {
    Route::get('/', [HomeController::class, 'index'])->name('home');
    Route::resource('/banners', BannerController::class);
    Route::resource('/branchoffices', BranchOfficeController::class);
    Route::resource('/cms-pages', CmsPageController::class);
    Route::resource('/email-templates', EmailTemplateController::class);
    Route::resource('/categories', CategoryController::class);
    Route::resource('/products', ProductController::class);
    Route::resource('/sub-categories', SubCategoryController::class);
    Route::get('/get-subcategories/{categoryId}', [ProductController::class, 'getSubcategories']);
    Route::get('/product/add-files-page', [ProductController::class, 'addFilesForm'])->name('products.add-files-page');
    Route::get('/product/add-images-page', [ProductController::class, 'addImagesForm'])->name('products.add-images-page');
    Route::post('/product/add-images/{product}', [ProductController::class, 'addImages'])->name('products.add-images');
    Route::post('/product/add-files/{product}', [ProductController::class, 'addFiles'])->name('products.add-files');
    Route::post('/product/validate', [ProductController::class, 'validateImages'])->name('product.validate');
    Route::post('/product/validatetext', [ProductController::class, 'validateText'])->name('product.validate');
    Route::get('/category/validate-delete/{category}', [CategoryController::class, 'destroy'])->name('category.validatedelete');
    Route::get('/sub-category/validate-delete/{subCategory}', [SubCategoryController::class, 'destroy'])->name('sub-category.validatedelete');




    // Data validation url
    Route::put('/sub-categories/{sub_category}/validate', [SubCategoryController::class, 'validateSubCategoryUpdate'])->name('sub-categories.validate');
    Route::put('/categories/{category}/validate', [CategoryController::class, 'validateCategoryUpdate'])->name('categories.validate');
    Route::post('/categories/validate', [CategoryController::class, 'validateCategoryStore'])->name('categories.validateStore');
    Route::post('/sub-categories/validate', [SubCategoryController::class, 'validateSubCategoryStore'])->name('sub-categories.validateStore');
});


// EXPERIMENTING ROUTES
// Route::get('/mailable', function () {
//     return new App\Mail\EmailTemplate('main', 'subject');
// });
