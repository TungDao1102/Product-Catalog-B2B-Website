<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('san-pham', [ProductController::class, 'index'])->name('products.index');
Route::get('san-pham/{slug}', [ProductController::class, 'show'])->name('products.show');

Route::get('danh-muc/{slug}', [CategoryController::class, 'show'])->name('categories.show');

Route::view('lien-he', 'contact')->name('contact');
