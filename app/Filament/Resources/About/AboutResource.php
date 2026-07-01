<?php

namespace App\Filament\Resources\About;

use App\Filament\Resources\About\Pages\EditAbout;
use App\Filament\Resources\About\Schemas\AboutForm;
use App\Models\About;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use LaraZeus\SpatieTranslatable\Resources\Concerns\Translatable;

class AboutResource extends Resource
{
    use Translatable;

    protected static ?string $model = About::class;

    protected static ?string $slug = 'about';

    public static function getTranslatableLocales(): array
    {
        return ['vi', 'en'];
    }

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-information-circle';

    protected static ?int $navigationSort = 8;

    protected static ?string $navigationLabel = 'Giới thiệu';

    protected static ?string $modelLabel = 'Giới thiệu';

    protected static ?string $pluralModelLabel = 'Giới thiệu';

    protected static ?string $recordTitleAttribute = 'id';

    public static function form(Schema $schema): Schema
    {
        return AboutForm::configure($schema);
    }

    public static function getPages(): array
    {
        return [
            'edit' => EditAbout::route('/{record}/edit'),
        ];
    }

    public static function getIndexUrl(array $parameters = [], bool $isAbsolute = true, ?string $panel = null, ?\Illuminate\Database\Eloquent\Model $tenant = null, bool $shouldGuessMissingParameters = false): string
    {
        $record = About::first();

        if (! $record) {
            $record = About::create(['is_active' => true]);
        }

        $parameters['record'] ??= $record->id;

        return static::getUrl('edit', $parameters, $isAbsolute, $panel, $tenant);
    }

    public static function getNavigationUrl(): string
    {
        /** @var About|null $record */
        $record = About::first();

        if (! $record) {
            $record = About::create(['is_active' => true]);
        }

        return static::getUrlForRecord($record, 'edit');
    }
}
