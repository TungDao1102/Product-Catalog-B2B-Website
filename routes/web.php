<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ProjectController;

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('san-pham', [ProductController::class, 'index'])->name('products.index');
Route::get('san-pham/{slug}', [ProductController::class, 'show'])->name('products.show');

Route::get('danh-muc/{slug}', [CategoryController::class, 'show'])->name('categories.show');

Route::get('tin-tuc', [PostController::class, 'index'])->name('posts.index');
Route::get('tin-tuc/{slug}', [PostController::class, 'show'])->name('posts.show');

Route::get('du-an', [ProjectController::class, 'index'])->name('projects.index');
Route::get('du-an/{slug}', [ProjectController::class, 'show'])->name('projects.show');

Route::view('lien-he', 'contact')->name('contact');
