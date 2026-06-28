<?php

namespace App\Filament\Resources\Inquiries\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class InquiryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Thông tin yêu cầu báo giá')
                    ->columns(2)
                    ->schema([
                        TextInput::make('name')
                            ->disabled()
                            ->dehydrated(false)
                            ->label('Họ tên'),
                        TextInput::make('email')
                            ->disabled()
                            ->dehydrated(false)
                            ->label('Email'),
                        TextInput::make('phone')
                            ->disabled()
                            ->dehydrated(false)
                            ->label('Số điện thoại'),
                        TextInput::make('company')
                            ->disabled()
                            ->dehydrated(false)
                            ->label('Công ty'),
                        TextInput::make('quantity')
                            ->disabled()
                            ->dehydrated(false)
                            ->numeric()
                            ->label('Số lượng'),
                        Select::make('product_id')
                            ->relationship('product', 'name')
                            ->disabled()
                            ->dehydrated(false)
                            ->label('Sản phẩm'),
                        Textarea::make('message')
                            ->disabled()
                            ->dehydrated(false)
                            ->label('Nội dung')
                            ->columnSpanFull(),
                        TextInput::make('is_read')
                            ->disabled()
                            ->dehydrated(false)
                            ->label('Đã đọc'),
                        TextInput::make('created_at')
                            ->disabled()
                            ->dehydrated(false)
                            ->label('Ngày gửi'),
                    ]),
            ]);
    }
}
