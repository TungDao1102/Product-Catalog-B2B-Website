<?php

namespace App\Filament\Resources\About\Pages;

use App\Filament\Resources\About\AboutResource;
use App\Models\About;
use Filament\Resources\Pages\EditRecord;
use LaraZeus\SpatieTranslatable\Actions\LocaleSwitcher;
use LaraZeus\SpatieTranslatable\Resources\Pages\EditRecord\Concerns\Translatable;

class EditAbout extends EditRecord
{
    use Translatable;

    protected static string $resource = AboutResource::class;

    protected function resolveRecord(int|string $key): About
    {
        return About::firstOrCreate([], [
            'content' => ['vi' => '', 'en' => ''],
            'is_active' => true,
        ]);
    }

    public function getRecord(): About
    {
        return About::firstOrCreate([], [
            'content' => ['vi' => '', 'en' => ''],
            'is_active' => true,
        ]);
    }

    protected function getHeaderActions(): array
    {
        return [
            LocaleSwitcher::make(),
        ];
    }
}
