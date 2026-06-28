<?php

namespace App\Filament\Resources\Contacts\Schemas;

use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ContactForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Thông tin liên hệ')
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
