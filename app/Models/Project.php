<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

#[Fillable(['title', 'slug', 'description', 'content', 'images', 'is_active', 'meta_title', 'meta_description'])]
class Project extends Model
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
}
