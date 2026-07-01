<?php

namespace App\Filament\Resources\About\Schemas;

use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class AboutForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->schema([
                Section::make(__('Nội dung'))
                    ->schema([
                        RichEditor::make('content')
                            ->label(__('Nội dung giới thiệu'))
                            ->columnSpanFull(),
                    ]),
                Section::make(__('Sứ mệnh & Tầm nhìn'))
                    ->schema([
                        RichEditor::make('mission')
                            ->label(__('Sứ mệnh')),
                        RichEditor::make('vision')
                            ->label(__('Tầm nhìn')),
                    ]),
                Section::make(__('Giá trị cốt lõi'))
                    ->schema([
                        RichEditor::make('values')
                            ->label(__('Giá trị cốt lõi')),
                    ]),
                Section::make(__('Lịch sử'))
                    ->schema([
                        RichEditor::make('history')
                            ->label(__('Lịch sử hình thành')),
                    ]),
                Section::make(__('Trạng thái'))
                    ->schema([
                        Toggle::make('is_active')
                            ->label(__('Hiển thị trang Giới thiệu')),
                    ]),
            ]);
    }
}
