<?php

namespace App\Filament\Resources\Projects\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class ProjectsTable
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
                TextColumn::make('description')
                    ->limit(80)
                    ->label('Mô tả ngắn'),
                ToggleColumn::make('is_active')
                    ->label('Kích hoạt'),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->label('Ngày tạo'),
            ])
            ->filters([
                TernaryFilter::make('is_active')
                    ->label('Kích hoạt'),
            ])
            ->defaultSort('created_at', 'desc')
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
