<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\InquiryController;

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
