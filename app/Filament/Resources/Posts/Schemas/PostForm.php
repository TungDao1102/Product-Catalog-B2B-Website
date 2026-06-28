<?php

namespace App\Filament\Resources\Posts\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class PostForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Nội dung bài viết')
                    ->schema([
                        TextInput::make('title')
                            ->required()
                            ->live(true)
                            ->afterStateUpdated(fn (string $op, $state, Set $set) => $op === 'create' ? $set('slug', Str::slug($state)) : null),
                        TextInput::make('slug')
                            ->required()
                            ->unique(ignoreRecord: true),
                        Textarea::make('excerpt')
                            ->label('Mô tả ngắn')
                            ->columnSpanFull(),
                        RichEditor::make('content')
                            ->label('Nội dung')
                            ->fileAttachmentsDisk('public')
                            ->fileAttachmentsDirectory('posts')
                            ->columnSpanFull()
                            ->toolbarButtons([
                                'bold', 'italic', 'underline', 'strike',
                                'h2', 'h3',
                                'bulletList', 'orderedList', 'blockquote',
                                'link', 'image', 'undo', 'redo',
                            ]),
                        FileUpload::make('image')
                            ->image()
                            ->disk('public')
                            ->directory('posts/thumbnails')
                            ->visibility('public')
                            ->label('Ảnh đại diện'),
                        Toggle::make('is_published')
                            ->label('Đã xuất bản'),
                        DateTimePicker::make('published_at')
                            ->label('Ngày xuất bản'),
                    ]),
            ]);
    }
}
