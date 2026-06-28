<?php

namespace App\Filament\Resources\Products\Pages;

use App\Filament\Resources\Products\ProductResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Laravel\Facades\Image;
use LaraZeus\SpatieTranslatable\Actions\LocaleSwitcher;
use LaraZeus\SpatieTranslatable\Resources\Pages\CreateRecord\Concerns\Translatable;

class CreateProduct extends CreateRecord
{
    use Translatable;

    protected static string $resource = ProductResource::class;

    protected function getHeaderActions(): array
    {
        return [
            LocaleSwitcher::make(),
        ];
    }

    protected function afterSave(): void
    {
        $product = $this->getRecord();
        $images = $product->images ?? [];

        foreach ($images as $imagePath) {
            $fullPath = Storage::disk('public')->path($imagePath);
            if (file_exists($fullPath)) {
                try {
                    $encoded = Image::decodePath($fullPath)
                        ->resize(width: 600, height: 600)
                        ->encodeUsingFileExtension(
                            pathinfo($imagePath, PATHINFO_EXTENSION),
                            quality: 85
                        );
                    Storage::disk('public')->put($imagePath, $encoded);
                } catch (\Exception $e) {
                    report($e);
                }
            }
        }
    }
}
