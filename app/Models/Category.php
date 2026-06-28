<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Sitemap\Contracts\Sitemapable;
use Spatie\Sitemap\Tags\Url;
use Spatie\Translatable\HasTranslations;

#[Fillable(['name', 'slug', 'description', 'parent_id', 'image', 'is_active', 'sort_order'])]
class Category extends Model implements Sitemapable
{
    use HasFactory;
    use HasTranslations;

    public array $translatable = ['name', 'description'];
    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(Category::class, 'parent_id')->orderBy('sort_order');
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    public function toSitemapTag(): Url|string|array
    {
        return Url::create(route('categories.show', ['locale' => 'vi', 'slug' => $this->slug]))
            ->setLastModificationDate($this->updated_at ?? now())
            ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY)
            ->setPriority(0.7)
            ->addAlternate('vi', route('categories.show', ['locale' => 'vi', 'slug' => $this->slug]))
            ->addAlternate('en', route('categories.show', ['locale' => 'en', 'slug' => $this->slug]));
    }
}
