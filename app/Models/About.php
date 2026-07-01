<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class About extends Model
{
    use HasTranslations;

    protected $table = 'about';

    protected $fillable = ['content', 'mission', 'vision', 'values', 'history', 'image', 'is_active'];

    public array $translatable = [
        'content',
        'mission',
        'vision',
        'values',
        'history',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }
}
