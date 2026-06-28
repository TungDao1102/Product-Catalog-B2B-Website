<?php

namespace App\Filament\Resources\Inquiries\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class InquiriesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable()
                    ->sortable()
                    ->label('Họ tên'),
                TextColumn::make('email')
                    ->searchable()
                    ->label('Email'),
                TextColumn::make('phone')
                    ->label('Số điện thoại'),
                TextColumn::make('company')
                    ->label('Công ty'),
                TextColumn::make('product.name')
                    ->label('Sản phẩm'),
                IconColumn::make('is_read')
                    ->boolean()
                    ->label('Đã đọc'),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->label('Ngày gửi'),
            ])
            ->filters([
                TernaryFilter::make('is_read')
                    ->label('Đã đọc'),
            ])
            ->defaultSort('created_at', 'desc')
            ->actions([
                ViewAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
