<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;

class HomeController extends Controller
{
    public function index()
    {
        $featuredProducts = Product::with(['category', 'brand'])
            ->where('is_active', true)
            ->where('is_featured', true)
            ->orderBy('sort_order')
            ->take(8)
            ->get();

        if ($featuredProducts->isEmpty()) {
            $featuredProducts = Product::with(['category', 'brand'])
                ->where('is_active', true)
                ->orderBy('sort_order')
                ->take(8)
                ->get();
        }

        $latestProducts = Product::with(['category', 'brand'])
            ->where('is_active', true)
            ->orderBy('created_at', 'desc')
            ->take(6)
            ->get();

        return view('home.index', compact('featuredProducts', 'latestProducts'));
    }
}
