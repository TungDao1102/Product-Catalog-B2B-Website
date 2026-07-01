<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Support\Facades\Cache;

class HomeController extends Controller
{
    public function index()
    {
        $featuredProductIds = Cache::remember('home.featured_product_ids', 3600, function () {
            return Product::where('is_active', true)
                ->where('is_featured', true)
                ->orderBy('sort_order')
                ->take(8)
                ->pluck('id')
                ->toArray();
        });

        $featuredProducts = Product::with(['category', 'brand'])
            ->whereIn('id', $featuredProductIds)
            ->orderByRaw('FIELD(id,' . implode(',', $featuredProductIds ?: [0]) . ')')
            ->get();

        if ($featuredProducts->isEmpty()) {
            $featuredProducts = Product::with(['category', 'brand'])
                ->where('is_active', true)
                ->orderBy('sort_order')
                ->take(8)
                ->get();
        }

        $latestProductIds = Cache::remember('home.latest_product_ids', 3600, function () {
            return Product::where('is_active', true)
                ->orderBy('created_at', 'desc')
                ->take(6)
                ->pluck('id')
                ->toArray();
        });

        $latestProducts = Product::with(['category', 'brand'])
            ->whereIn('id', $latestProductIds)
            ->orderByRaw('FIELD(id,' . implode(',', $latestProductIds ?: [0]) . ')')
            ->get();

        $seo = [
            'title' => __('seo.home_title'),
            'description' => __('seo.home_description'),
            'image' => asset('img/og-default.jpg'),
            'type' => 'website',
        ];

        return view('home.index', compact('featuredProducts', 'latestProducts', 'seo'));
    }
}
