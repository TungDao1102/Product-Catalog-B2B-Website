<?php

namespace App\Filament\Resources\Products\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class ProductsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('sku')
                    ->searchable()
                    ->sortable()
                    ->label('Mã SP'),
                TextColumn::make('name')
                    ->searchable()
                    ->sortable()
                    ->limit(40)
                    ->label('Tên sản phẩm'),
                TextColumn::make('brand.name')
                    ->searchable()
                    ->label('Hãng'),
                TextColumn::make('category.name')
                    ->label('Danh mục'),
                TextColumn::make('price')
                    ->money('VND')
                    ->sortable()
                    ->label('Giá'),
                IconColumn::make('is_featured')
                    ->boolean()
                    ->label('Nổi bật'),
                IconColumn::make('is_active')
                    ->boolean()
                    ->label('Kích hoạt'),
                TextColumn::make('sort_order')
                    ->numeric()
                    ->sortable()
                    ->label('Thứ tự'),
            ])
            ->filters([
                SelectFilter::make('category_id')
                    ->relationship('category', 'name')
                    ->label('Danh mục'),
                SelectFilter::make('brand_id')
                    ->relationship('brand', 'name')
                    ->label('Hãng'),
                TernaryFilter::make('is_featured')
                    ->label('Nổi bật'),
            ])
            ->defaultSort('sort_order')
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
