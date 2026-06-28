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
