<?php

namespace App\Filament\Resources\Products\Pages;

use App\Filament\Resources\Products\ProductResource;
use App\Models\Category;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Laravel\Facades\Image;
use LaraZeus\SpatieTranslatable\Actions\LocaleSwitcher;
use LaraZeus\SpatieTranslatable\Resources\Pages\EditRecord\Concerns\Translatable;

class EditProduct extends EditRecord
{
    use Translatable;

    protected static string $resource = ProductResource::class;

    protected function getHeaderActions(): array
    {
        return [
            LocaleSwitcher::make(),
            DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        if ($this->record && $this->record->category_id) {
            $ids = [];
            $cat = $this->record->category;
            while ($cat) {
                array_unshift($ids, $cat->id);
                $cat = $cat->parent;
            }
            $data['category_level_0'] = $ids[0] ?? null;
            $data['category_level_1'] = $ids[1] ?? null;
        }

        return $data;
    }

    protected function afterSave(): void
    {
        $product = $this->getRecord();
        $images = $product->images ?? [];

        Cache::forget('home.featured_products');
        Cache::forget('home.latest_products');

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
