<?php

namespace App\Filament\Resources\Posts\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class PostsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')
                    ->searchable()
                    ->sortable()
                    ->limit(50)
                    ->label('Tiêu đề'),
                TextColumn::make('excerpt')
                    ->limit(60)
                    ->label('Mô tả ngắn'),
                ToggleColumn::make('is_published')
                    ->label('Đã xuất bản'),
                TextColumn::make('published_at')
                    ->dateTime()
                    ->sortable()
                    ->label('Ngày xuất bản'),
            ])
            ->filters([
                TernaryFilter::make('is_published')
                    ->label('Đã xuất bản'),
            ])
            ->defaultSort('published_at', 'desc')
            ->actions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
