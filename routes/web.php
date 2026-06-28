<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\InquiryController;
use App\Http\Middleware\SetLocale;

/*
|--------------------------------------------------------------------------
| Root Redirect
|--------------------------------------------------------------------------
|
| Redirect / to the user's preferred locale or default to Vietnamese.
|
*/

Route::get('/', function () {
    $locale = 'vi';

    if (request()->server('HTTP_ACCEPT_LANGUAGE') && preg_match('/^en\b/', request()->server('HTTP_ACCEPT_LANGUAGE'))) {
        $locale = 'en';
    }

    return redirect("/{$locale}");
});

/*
|--------------------------------------------------------------------------
| robots.txt
|--------------------------------------------------------------------------
*/

Route::get('/robots.txt', function () {
    return response("User-agent: *\nAllow: /\nSitemap: " . url('/sitemap.xml'))
        ->header('Content-Type', 'text/plain');
});

/*
|--------------------------------------------------------------------------
| Locale-prefixed Frontend Routes
|--------------------------------------------------------------------------
|
| All frontend routes are wrapped in a {locale} prefix group.
| The SetLocale middleware extracts the locale from the URL segment
| and sets the application locale accordingly.
|
*/

Route::prefix('{locale}')
    ->where(['locale' => '[a-z]{2}'])
    ->middleware([SetLocale::class])
    ->group(function () {

        Route::get('/', [HomeController::class, 'index'])->name('home');

        Route::get('san-pham', [ProductController::class, 'index'])->name('products.index');
        Route::get('san-pham/{slug}', [ProductController::class, 'show'])->name('products.show');

        Route::get('danh-muc/{slug}', [CategoryController::class, 'show'])->name('categories.show');

        Route::get('tin-tuc', [PostController::class, 'index'])->name('posts.index');
        Route::get('tin-tuc/{slug}', [PostController::class, 'show'])->name('posts.show');

        Route::get('du-an', [ProjectController::class, 'index'])->name('projects.index');
        Route::get('du-an/{slug}', [ProjectController::class, 'show'])->name('projects.show');

        Route::get('lien-he', [ContactController::class, 'show'])->name('contact');
        Route::post('lien-he', [ContactController::class, 'store'])->name('contact.store');

        Route::post('yeu-cau-bao-gia', [InquiryController::class, 'store'])->name('inquiries.store');

    });
