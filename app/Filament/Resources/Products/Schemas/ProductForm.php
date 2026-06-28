<?php

namespace App\Filament\Resources\Products\Schemas;

use App\Models\Category;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\RichEditor;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class ProductForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Thông tin cơ bản')
                    ->columns(2)
                    ->schema([
                        TextInput::make('name')
                            ->required()
                            ->label('Tên sản phẩm')
                            ->live(true)
                            ->afterStateUpdated(function (string $operation, $state, Set $set) {
                                if ($operation === 'create') {
                                    $set('slug', Str::slug($state));
                                }
                            }),
                        TextInput::make('slug')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->label('Slug'),
                        TextInput::make('sku')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->label('Mã sản phẩm'),
                        Select::make('category_level_0')
                            ->label('Ngành')
                            ->dehydrated(false)
                            ->live()
                            ->options(fn () => Category::whereNull('parent_id')->pluck('name', 'id')),
                        Select::make('category_level_1')
                            ->label('Nhóm')
                            ->dehydrated(false)
                            ->live()
                            ->options(fn (Get $get) => $get('category_level_0')
                                ? Category::where('parent_id', $get('category_level_0'))->pluck('name', 'id')
                                : []
                            ),
                        Select::make('category_id')
                            ->label('Loại')
                            ->required()
                            ->options(fn (Get $get) => $get('category_level_1')
                                ? Category::where('parent_id', $get('category_level_1'))->pluck('name', 'id')
                                : []
                            )
                            ->disabled(fn (Get $get) => blank($get('category_level_1'))),
                        Select::make('brand_id')
                            ->relationship('brand', 'name')
                            ->searchable()
                            ->preload()
                            ->label('Hãng sản xuất'),
                        TextInput::make('unit')
                            ->required()
                            ->label('Đơn vị tính'),
                        TextInput::make('price')
                            ->numeric()
                            ->prefix('VNĐ')
                            ->label('Giá'),
                        TextInput::make('min_order_qty')
                            ->numeric()
                            ->default(1)
                            ->label('Số lượng đặt tối thiểu'),
                    ]),
                Section::make('Mô tả')
                    ->schema([
                        Textarea::make('short_description')
                            ->label('Mô tả ngắn')
                            ->columnSpanFull(),
                        RichEditor::make('description')
                            ->label('Mô tả chi tiết')
                            ->columnSpanFull(),
                    ]),
                Section::make('Hình ảnh & Tài liệu')
                    ->schema([
                        FileUpload::make('images')
                            ->multiple()
                            ->reorderable()
                            ->image()
                            ->disk('public')
                            ->directory('products')
                            ->visibility('public')
                            ->maxFiles(10)
                            ->columnSpanFull()
                            ->label('Hình ảnh sản phẩm'),
                        FileUpload::make('brochure')
                            ->disk('public')
                            ->directory('brochures')
                            ->acceptedFileTypes(['application/pdf'])
                            ->maxSize(10240)
                            ->columnSpanFull()
                            ->label('Brochure (PDF)'),
                    ]),
                Section::make('Thông số kỹ thuật')
                    ->schema([
                        Repeater::make('technical_specs')
                            ->label('Thông số kỹ thuật')
                            ->schema([
                                TextInput::make('attribute_name')
                                    ->label('Thông số')
                                    ->required()
                                    ->maxLength(255),
                                TextInput::make('attribute_value')
                                    ->label('Giá trị')
                                    ->required()
                                    ->maxLength(255),
                            ])
                            ->columns(2)
                            ->defaultItems(0)
                            ->collapsible()
                            ->addActionLabel('Thêm thông số')
                            ->columnSpanFull(),
                    ]),
                Section::make('Hiển thị')
                    ->columns(2)
                    ->schema([
                        Toggle::make('is_featured')
                            ->label('Sản phẩm nổi bật'),
                        Toggle::make('is_active')
                            ->label('Kích hoạt')
                            ->default(true),
                        TextInput::make('sort_order')
                            ->numeric()
                            ->default(0)
                            ->label('Thứ tự sắp xếp'),
                        TextInput::make('meta_title')
                            ->label('Meta Title'),
                        Textarea::make('meta_description')
                            ->label('Meta Description')
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}
