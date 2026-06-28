<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;

class ProductController extends Controller
{
    public function index()
    {
        $query = Product::with(['category', 'brand'])
            ->where('is_active', true);

        // Filter by category
        if ($categorySlug = request('category')) {
            $category = Category::where('slug', $categorySlug)->first();
            if ($category) {
                $query->where('category_id', $category->id);
            }
        }

        // Search
        if ($search = request('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name->vi', 'like', "%{$search}%")
                  ->orWhere('name->en', 'like', "%{$search}%")
                  ->orWhere('sku', 'like', "%{$search}%")
                  ->orWhere('short_description->vi', 'like', "%{$search}%")
                  ->orWhere('short_description->en', 'like', "%{$search}%");
            });
        }

        // Sort
        switch (request('sort')) {
            case 'name_asc':
                $query->orderBy('name->vi');
                break;
            case 'name_desc':
                $query->orderBy('name->vi', 'desc');
                break;
            case 'newest':
                $query->orderBy('created_at', 'desc');
                break;
            case 'oldest':
                $query->orderBy('created_at');
                break;
            default:
                $query->orderBy('sort_order')->orderBy('created_at', 'desc');
        }

        $products = $query->paginate(9)->withQueryString();
        $categories = Category::with('children')
            ->whereNull('parent_id')
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get();
        $brands = Brand::where('is_active', true)->get();

        $seo = [
            'title' => __('products.page_title'),
            'description' => __('products.page_description'),
            'image' => asset('img/og-default.jpg'),
            'type' => 'website',
        ];

        return view('products.index', compact('products', 'categories', 'brands', 'seo'));
    }

    public function show($slug)
    {
        $product = Product::with(['category', 'brand'])
            ->where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        $relatedProducts = Product::with(['category', 'brand'])
            ->where('is_active', true)
            ->where('id', '!=', $product->id)
            ->where(function ($q) use ($product) {
                $q->where('category_id', $product->category_id);
                if ($product->brand_id) {
                    $q->orWhere('brand_id', $product->brand_id);
                }
            })
            ->take(4)
            ->get();

        $seo = [
            'title' => $product->getTranslation('name', app()->getLocale()),
            'description' => strip_tags($product->getTranslation('short_description', app()->getLocale()) ?? ''),
            'image' => isset($product->images[0]) ? asset('storage/' . $product->images[0]) : asset('img/og-default.jpg'),
            'type' => 'product',
        ];

        return view('products.show', compact('product', 'relatedProducts', 'seo'));
    }
}
