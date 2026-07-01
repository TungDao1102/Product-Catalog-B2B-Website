<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Sitemap\Contracts\Sitemapable;
use Spatie\Sitemap\Tags\Url;
use Spatie\Translatable\HasTranslations;

#[Fillable(['category_id', 'brand_id', 'name', 'slug', 'sku', 'short_description', 'description', 'technical_specs', 'unit', 'price', 'min_order_qty', 'images', 'brochure', 'is_featured', 'is_active', 'sort_order', 'meta_title', 'meta_description'])]
class Product extends Model implements Sitemapable
{
    use HasFactory;
    use HasTranslations;

    public array $translatable = ['name', 'description', 'short_description', 'meta_title', 'meta_description'];
    protected function casts(): array
    {
        return [
            'images' => 'array',
            'technical_specs' => 'array',
            'is_featured' => 'boolean',
            'is_active' => 'boolean',
            'price' => 'decimal:2',
        ];
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class);
    }

    /**
     * Get the URL for a product image by index.
     *
     * - Seeded images are stored as "img/products/xxx.jpg" (public/img/)
     * - Admin-uploaded images are stored as "products/xxx.jpg" (storage/app/public/products/)
     *
     * Always falls back to a placeholder if the file doesn't exist,
     * avoiding slow 404s through the full Laravel stack.
     */
    public function imageUrl(int $index = 0): string
    {
        $path = $this->images[$index] ?? null;

        if (! $path) {
            return asset('img/product-1.jpg');
        }

        $url = str_starts_with($path, 'img/')
            ? asset($path)
            : asset('storage/' . $path);

        // file_exists is fast (microseconds, OS-cached).
        // Without this check, php artisan serve forwards missing files
        // through the full Laravel bootstrap stack, taking 2-3s to 404.
        if (str_starts_with($path, 'img/') && ! file_exists(public_path($path))) {
            return asset('img/product-1.jpg');
        }

        if (! str_starts_with($path, 'img/') && ! file_exists(storage_path('app/public/' . $path))) {
            return asset('img/product-1.jpg');
        }

        return $url;
    }

    public function toSitemapTag(): Url|string|array
    {
        return Url::create(route('products.show', ['locale' => 'vi', 'slug' => $this->slug]))
            ->setLastModificationDate($this->updated_at ?? now())
            ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY)
            ->setPriority(0.8)
            ->addAlternate('vi', route('products.show', ['locale' => 'vi', 'slug' => $this->slug]))
            ->addAlternate('en', route('products.show', ['locale' => 'en', 'slug' => $this->slug]));
    }
}
