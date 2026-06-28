<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Spatie\Sitemap\Contracts\Sitemapable;
use Spatie\Sitemap\Tags\Url;
use Spatie\Translatable\HasTranslations;

#[Fillable(['title', 'slug', 'excerpt', 'content', 'image', 'is_published', 'published_at', 'meta_title', 'meta_description'])]
class Post extends Model implements Sitemapable
{
    use HasTranslations;

    public array $translatable = ['title', 'content', 'excerpt', 'meta_title', 'meta_description'];
    protected function casts(): array
    {
        return [
            'is_published' => 'boolean',
            'published_at' => 'datetime',
        ];
    }

    public function toSitemapTag(): Url|string|array
    {
        return Url::create(route('posts.show', ['locale' => 'vi', 'slug' => $this->slug]))
            ->setLastModificationDate($this->updated_at ?? now())
            ->setChangeFrequency(Url::CHANGE_FREQUENCY_MONTHLY)
            ->setPriority(0.6)
            ->addAlternate('vi', route('posts.show', ['locale' => 'vi', 'slug' => $this->slug]))
            ->addAlternate('en', route('posts.show', ['locale' => 'en', 'slug' => $this->slug]));
    }
}
