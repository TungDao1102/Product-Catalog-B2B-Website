<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;

class CategoryController extends Controller
{
    public function show($slug)
    {
        $category = Category::with('children')
            ->where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        $subCategories = Category::where('parent_id', $category->id)
            ->where('is_active', true)
            ->get();

        $rootCategories = Category::with('children')
            ->whereNull('parent_id')
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        $query = Product::with(['category', 'brand'])
            ->where('is_active', true)
            ->where(function ($q) use ($category, $subCategories) {
                $q->where('category_id', $category->id);
                $subCategoryIds = $subCategories->pluck('id')->toArray();
                if (!empty($subCategoryIds)) {
                    $q->orWhereIn('category_id', $subCategoryIds);
                }
            });

        // Search
        if ($search = request('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('sku', 'like', "%{$search}%")
                  ->orWhere('short_description', 'like', "%{$search}%");
            });
        }

        // Sort
        switch (request('sort')) {
            case 'name_asc':
                $query->orderBy('name');
                break;
            case 'name_desc':
                $query->orderBy('name', 'desc');
                break;
            case 'newest':
                $query->orderBy('created_at', 'desc');
                break;
            default:
                $query->orderBy('sort_order')->orderBy('created_at', 'desc');
        }

        $products = $query->paginate(9)->withQueryString();

        return view('categories.show', compact('category', 'subCategories', 'rootCategories', 'products'));
    }
}
