<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Spatie\Sitemap\Contracts\Sitemapable;
use Spatie\Sitemap\Tags\Url;
use Spatie\Translatable\HasTranslations;

#[Fillable(['title', 'slug', 'description', 'content', 'images', 'is_active', 'meta_title', 'meta_description'])]
class Project extends Model implements Sitemapable
{
    use HasTranslations;

    public array $translatable = ['title', 'content', 'description', 'meta_title', 'meta_description'];
    protected function casts(): array
    {
        return [
            'images' => 'array',
            'is_active' => 'boolean',
        ];
    }

    public function toSitemapTag(): Url|string|array
    {
        return Url::create(route('projects.show', ['locale' => 'vi', 'slug' => $this->slug]))
            ->setLastModificationDate($this->updated_at ?? now())
            ->setChangeFrequency(Url::CHANGE_FREQUENCY_MONTHLY)
            ->setPriority(0.6)
            ->addAlternate('vi', route('projects.show', ['locale' => 'vi', 'slug' => $this->slug]))
            ->addAlternate('en', route('projects.show', ['locale' => 'en', 'slug' => $this->slug]));
    }
}
