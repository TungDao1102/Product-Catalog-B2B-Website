<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

#[Fillable(['title', 'slug', 'excerpt', 'content', 'image', 'is_published', 'published_at', 'meta_title', 'meta_description'])]
class Post extends Model
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
}
