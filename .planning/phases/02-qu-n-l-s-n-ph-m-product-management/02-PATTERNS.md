# Phase 02: Quản lý sản phẩm (Product Management) - Pattern Map

**Mapped:** 2026-06-27
**Files analyzed:** 27 (19 new + 8 modify)
**Analogs found:** 23 / 27

## File Classification

| New/Modified File | Role | Data Flow | Closest Analog | Match Quality |
|---|---|---|---|---|
| `app/Models/Category.php` | model | CRUD | `app/Models/User.php` | role-match (same framework) |
| `app/Models/Brand.php` | model | CRUD | `app/Models/User.php` | role-match |
| `app/Models/Product.php` | model | CRUD | `app/Models/User.php` | role-match |
| `app/Filament/Resources/CategoryResource.php` | resource | CRUD | No existing Filament resource | no-analog (use RESEARCH.md) |
| `app/Filament/Resources/BrandResource.php` | resource | CRUD | No existing Filament resource | no-analog (use RESEARCH.md) |
| `app/Filament/Resources/ProductResource.php` | resource | CRUD | No existing Filament resource | no-analog (use RESEARCH.md) |
| `database/seeders/CategorySeeder.php` | seeder | batch | `database/seeders/DatabaseSeeder.php` | role-match |
| `database/seeders/BrandSeeder.php` | seeder | batch | `database/seeders/DatabaseSeeder.php` | role-match |
| `database/seeders/ProductSeeder.php` | seeder | batch | `database/seeders/DatabaseSeeder.php` | role-match |
| `database/seeders/DatabaseSeeder.php` | seeder | batch | `database/seeders/DatabaseSeeder.php` | self (MODIFY) |
| `resources/views/products/show.blade.php` | component | request-response | Already exists (MODIFY) | self |
| `composer.json` | config | — | Already exists (MODIFY) | self |
| `app/Providers/Filament/AdminPanelProvider.php` | config | — | Already exists (no change needed) | self |
| `tests/Feature/Filament/CategoryResourceTest.php` | test | CRUD | `tests/Feature/ExampleTest.php` | role-match |
| `tests/Feature/Filament/BrandResourceTest.php` | test | CRUD | `tests/Feature/ExampleTest.php` | role-match |
| `tests/Feature/Filament/ProductResourceTest.php` | test | CRUD | `tests/Feature/ExampleTest.php` | role-match |
| `tests/Feature/Filament/ResourceNavigationTest.php` | test | CRUD | `tests/Feature/ExampleTest.php` | role-match |
| `tests/Feature/Http/HomePageTest.php` | test | request-response | `tests/Feature/ExampleTest.php` | exact |
| `tests/Feature/Http/CategoryPageTest.php` | test | request-response | `tests/Feature/ExampleTest.php` | exact |
| `tests/Feature/Http/ProductDetailTest.php` | test | request-response | `tests/Feature/ExampleTest.php` | exact |
| `tests/Feature/Http/ProductSearchTest.php` | test | request-response | `tests/Feature/ExampleTest.php` | exact |
| `tests/Feature/Http/ProductCardRenderingTest.php` | test | request-response | `tests/Feature/ExampleTest.php` | exact |

---

## Pattern Assignments

### `app/Models/Category.php` (model, CRUD)

**Analog:** `app/Models/User.php` (lines 1-32)

**Laravel model pattern (lines 1-32):**
```php
<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['name', 'email', 'password'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}
```

**Key takeaway for Phase 2 models:** The project uses **PHP 8 attributes** (`#[Fillable]`) for mass-assignment protection, NOT the traditional `$fillable` property. Copy this pattern. The `casts()` method uses the modern `protected function` style (not `protected $casts` property).

**What Category model needs:**
- `#[Fillable([...])]` attribute with all category columns
- `casts()` method returning `['is_active' => 'boolean']`
- `parent()`: BelongsTo self-referencing relationship
- `children()`: HasMany self-referencing relationship
- `products()`: HasMany relationship to Product

**Eloquent self-referencing relationships pattern** (from RESEARCH.md lines 491-523):
```php
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

public function parent(): BelongsTo
{
    return $this->belongsTo(Category::class, 'parent_id');
}

public function children(): HasMany
{
    return $this->hasMany(Category::class, 'parent_id')
        ->orderBy('sort_order');
}

public function products(): HasMany
{
    return $this->hasMany(Product::class);
}
```

---

### `app/Models/Brand.php` (model, CRUD)

**Analog:** `app/Models/User.php` (same base pattern as Category)

**Key differences:**
- `#[Fillable(['name', 'slug', 'description', 'logo', 'website', 'is_active', 'sort_order'])]`
- `casts()` with `'is_active' => 'boolean'`
- `products()`: HasMany relationship
- No self-referencing needed

---

### `app/Models/Product.php` (model, CRUD)

**Analog:** `app/Models/User.php` (same base pattern)

**Key additions from RESEARCH.md (lines 528-561):**
```php
#[Fillable([
    'category_id', 'brand_id', 'name', 'slug', 'sku',
    'short_description', 'description', 'technical_specs',
    'unit', 'price', 'min_order_qty', 'images',
    'brochure', 'is_featured', 'is_active', 'sort_order',
    'meta_title', 'meta_description',
])]
class Product extends Model
{
    protected function casts(): array
    {
        return [
            'images' => 'array',
            'technical_specs' => 'array',
            'is_featured' => 'boolean',
            'is_active' => 'boolean',
            'price' => 'decimal:2',
        ];
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class);
    }
}
```

**CRITICAL:** The `images` and `technical_specs` JSON columns need `'array'` casts so Laravel auto-serializes/deserializes them. Without these casts, Filament Repeater and FileUpload won't work correctly.

---

### `app/Filament/Resources/CategoryResource.php` (resource, CRUD)

**Analog:** No existing Filament resource in codebase.

**Pattern source:** RESEARCH.md lines 600-623 and Filament v5 conventions.

**Imports pattern (Filament v5):**
```php
<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CategoryResource\Pages;
use App\Models\Category;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;
```

**Resource structure pattern:**
```php
class CategoryResource extends Resource
{
    protected static ?string $model = Category::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('name')
                ->required()
                ->live(true)
                ->afterStateUpdated(fn (string $operation, $state, Forms\Set $set) =>
                    $operation === 'create' ? $set('slug', Str::slug($state)) : null
                ),
            Forms\Components\TextInput::make('slug')->required()->unique(ignoreRecord: true),
            Forms\Components\Select::make('parent_id')
                ->label('Danh mục cha')
                ->relationship('parent', 'name', ignoreRecord: true)
                ->placeholder('— Cấp cao nhất —'),
            Forms\Components\Textarea::make('description'),
            Forms\Components\FileUpload::make('image')->image()->disk('public')->directory('categories'),
            Forms\Components\Toggle::make('is_active')->default(true),
            Forms\Components\TextInput::make('sort_order')->numeric()->default(0),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('parent.name')->label('Danh mục cha'),
                Tables\Columns\IconColumn::make('is_active')->boolean(),
                Tables\Columns\TextColumn::make('sort_order')->sortable(),
            ])
            ->defaultSort('sort_order');
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCategories::route('/'),
            'create' => Pages\CreateCategory::route('/create'),
            'edit' => Pages\EditCategory::route('/{record}/edit'),
        ];
    }
}
```

**NOTE:** Filament v5 uses `use Filament\Forms;` (global namespace import for all form components) instead of importing each component separately. The `->live()` method replaces the old `->reactive()` from v3/v4. The `unique(ignoreRecord: true)` allows the same slug to be valid on edit.

---

### `app/Filament/Resources/BrandResource.php` (resource, CRUD)

**Analog:** No existing Filament resource — same pattern as CategoryResource above.

**Key differences from CategoryResource:**
- `Brand::class` as model
- Simpler form: name, slug, description, logo (FileUpload), website (URL input), is_active, sort_order
- Table: name, logo (image column), website, is_active

**Form pattern:**
```php
Forms\Components\TextInput::make('name')->required(),
Forms\Components\TextInput::make('slug')->required()->unique(ignoreRecord: true),
Forms\Components\Textarea::make('description'),
Forms\Components\FileUpload::make('logo')->image()->disk('public')->directory('brands'),
Forms\Components\TextInput::make('website')->url()->suffixIcon('heroicon-m-globe-alt'),
Forms\Components\Toggle::make('is_active')->default(true),
Forms\Components\TextInput::make('sort_order')->numeric()->default(0),
```

---

### `app/Filament/Resources/ProductResource.php` (resource, CRUD)

**Analog:** No existing Filament resource.

**Pattern source:** RESEARCH.md lines 625-683 (complex form with cascading selects).

This is the most complex resource. Key pattern excerpts:

**Imports:**
```php
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
```

**Cascading category selects pattern (3-level)** — from RESEARCH.md lines 270-317:
```php
// Helper Select 1: Level 0 (Ngành) — parent_id IS NULL
Forms\Components\Select::make('category_level_0')
    ->label('Ngành')
    ->live()  // Filament v5 — was ->reactive() in v3/v4
    ->dehydrated(false)  // Don't save to DB (helper field only)
    ->options(fn (): array =>
        \App\Models\Category::whereNull('parent_id')
            ->orderBy('sort_order')
            ->pluck('name', 'id')
            ->toArray()
    )
    ->afterStateUpdated(fn (Forms\Components\Select $component) =>
        $component->getContainer()->getComponent('category_level_1')->state(null)
    ),

// Helper Select 2: Level 1 (Nhóm) — filtered by selected level 0
Forms\Components\Select::make('category_level_1')
    ->label('Nhóm')
    ->live()
    ->dehydrated(false)
    ->options(fn (Get $get): array =>
        \App\Models\Category::where('parent_id', $get('category_level_0'))
            ->orderBy('sort_order')
            ->pluck('name', 'id')
            ->toArray()
    )
    ->afterStateUpdated(fn (Forms\Components\Select $component) =>
        $component->getContainer()->getComponent('category_id')->state(null)
    ),

// Real Select: Level 2 (Loại) — the actual category_id that gets saved
Forms\Components\Select::make('category_id')
    ->label('Loại')
    ->required()
    ->options(fn (Get $get): array =>
        \App\Models\Category::where('parent_id', $get('category_level_1'))
            ->orderBy('sort_order')
            ->pluck('name', 'id')
            ->toArray()
    )
    ->disabled(fn (Get $get): bool => blank($get('category_level_1'))),
```

**Multiple image upload pattern** — from RESEARCH.md lines 328-348:
```php
Forms\Components\FileUpload::make('images')
    ->label('Hình ảnh sản phẩm')
    ->multiple()
    ->reorderable()
    ->appendFiles()
    ->image()
    ->disk('public')
    ->directory('products')
    ->visibility('public')
    ->maxFiles(10)
    ->columnSpanFull(),

Forms\Components\FileUpload::make('brochure')
    ->label('Brochure (PDF)')
    ->disk('public')
    ->directory('brochures')
    ->acceptedFileTypes(['application/pdf'])
    ->maxSize(10240)  // 10MB
    ->columnSpanFull(),
```

**Repeater for technical_specs pattern** — from RESEARCH.md lines 397-418:
```php
Forms\Components\Repeater::make('technical_specs')
    ->label('Thông số kỹ thuật')
    ->schema([
        Forms\Components\TextInput::make('attribute_name')
            ->label('Thông số')
            ->required()
            ->maxLength(255),
        Forms\Components\TextInput::make('attribute_value')
            ->label('Giá trị')
            ->required()
            ->maxLength(255),
    ])
    ->columns(2)
    ->defaultItems(0)
    ->collapsible()
    ->addActionLabel('Thêm thông số')
    ->columnSpanFull(),
```

**IMPORTANT:** The Repeater stores `[{"attribute_name": "...", "attribute_value": "..."}]` format. See Shared Patterns — Blade Update section for required view changes.

**Image resize via Intervention** — from RESEARCH.md lines 351-389:
Use `mutateFormDataBeforeSave` or `afterSave` hook in `CreateProduct.php` / `EditProduct.php`:
```php
// In app/Filament/Resources/ProductResource/Pages/CreateProduct.php or EditProduct.php:
protected function afterSave(): void
{
    $product = $this->getRecord();
    if (empty($product->images)) return;

    foreach ($product->images as $imagePath) {
        $fullPath = \Illuminate\Support\Facades\Storage::disk('public')->path($imagePath);
        if (file_exists($fullPath)) {
            $img = \Intervention\Image\Laravel\Facades\Image::decode($fullPath)
                ->resize(width: 600, height: 600)
                ->encodeUsingFileExtension(
                    pathinfo($imagePath, PATHINFO_EXTENSION),
                    quality: 85
                );
            \Illuminate\Support\Facades\Storage::disk('public')->put($imagePath, $img);
        }
    }
}
```

---

### `database/seeders/CategorySeeder.php` (seeder, batch)

**Analog:** `database/seeders/DatabaseSeeder.php` (lines 1-25)

**Seeder pattern (lines 1-25):**
```php
<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);
    }
}
```

**Category seeder hierarchy pattern** — from RESEARCH.md lines 568-598:
```php
class CategorySeeder extends Seeder
{
    public function run(): void
    {
        // Level 0 (Ngành)
        $nganhAnNinh = \App\Models\Category::create([
            'name' => 'Thiết bị an ninh soi chiếu',
            'slug' => 'thiet-bi-an-ninh-soi-chieu',
            'description' => 'Thiết bị an ninh, máy soi chiếu',
            'sort_order' => 1,
        ]);

        // Level 1 (Nhóm)
        $nhomMaySoi = \App\Models\Category::create([
            'name' => 'Máy soi chiếu',
            'slug' => 'may-soi-chieu',
            'parent_id' => $nganhAnNinh->id,
            'sort_order' => 1,
        ]);

        // Level 2 (Loại)
        \App\Models\Category::create([
            'name' => 'Máy soi chiếu X-Ray',
            'slug' => 'may-soi-chieu-xray',
            'parent_id' => $nhomMaySoi->id,
            'sort_order' => 1,
        ]);
    }
}
```

---

### `database/seeders/BrandSeeder.php` (seeder, batch)

**Analog:** Same as CategorySeeder but simple flat create calls.

```php
class BrandSeeder extends Seeder
{
    public function run(): void
    {
        \App\Models\Brand::create([
            'name' => 'Hikvision',
            'slug' => 'hikvision',
            'description' => 'Hikvision - Thương hiệu thiết bị an ninh hàng đầu',
            'website' => 'https://www.hikvision.com',
            'sort_order' => 1,
        ]);
        // ... more brands
    }
}
```

---

### `database/seeders/ProductSeeder.php` (seeder, batch)

**Analog:** Same seeder pattern, but with JSON array data for `images` and `technical_specs`.

```php
class ProductSeeder extends Seeder
{
    public function run(): void
    {
        \App\Models\Product::create([
            'category_id' => 1,
            'brand_id' => 1,
            'name' => 'Máy soi chiếu X-Ray 100/80',
            'slug' => 'may-soi-chieu-xray-100-80',
            'sku' => 'XRAY-10080',
            'short_description' => 'Máy soi chiếu hành lý công nghệ cao',
            'technical_specs' => [
                ['attribute_name' => 'Công suất', 'attribute_value' => '200W'],
                ['attribute_name' => 'Điện áp', 'attribute_value' => '220V'],
            ],
            'unit' => 'bộ',
            'images' => ['img/products/product-1.jpg'],
            'is_featured' => true,
            'is_active' => true,
        ]);
    }
}
```

**IMPORTANT:** The `technical_specs` must be stored as array-of-objects format `[{"attribute_name": "...", "attribute_value": "..."}]` to match Filament Repeater output. This is the format the Blade view must consume.

---

### `database/seeders/DatabaseSeeder.php` (MODIFY)

**Current pattern (lines 1-25):**
```php
class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);
    }
}
```

**Needs:**
```php
public function run(): void
{
    User::factory()->create([
        'name' => 'Test User',
        'email' => 'test@example.com',
    ]);

    $this->call([
        CategorySeeder::class,
        BrandSeeder::class,
        ProductSeeder::class,
    ]);
}
```

Also import the seeder classes:
```php
use Database\Seeders\CategorySeeder;
use Database\Seeders\BrandSeeder;
use Database\Seeders\ProductSeeder;
```

---

### `resources/views/products/show.blade.php` (MODIFY — technical_specs format)

**Current code (lines 129-134):** Iterates as flat key-value object:
```blade
@foreach($product->technical_specs as $spec => $value)
    <tr>
        <td>{{ $spec }}</td>
        <td>{{ $value }}</td>
    </tr>
@endforeach
```

**Required change** to match Filament Repeater array-of-objects format:
```blade
@if($product->technical_specs && is_array($product->technical_specs))
    @foreach($product->technical_specs as $spec)
        <tr>
            <td>{{ $spec['attribute_name'] }}</td>
            <td>{{ $spec['attribute_value'] }}</td>
        </tr>
    @endforeach
@endif
```

---

### `tests/Feature/*.php` (All test files)

**Analog:** `tests/Feature/ExampleTest.php` (lines 1-19)

**Test pattern:**
```php
<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    public function test_the_application_returns_a_successful_response(): void
    {
        $response = $this->get('/');
        $response->assertStatus(200);
    }
}
```

**For Filament resource tests**, use `RefreshDatabase` trait:
```php
<?php

namespace Tests\Feature\Filament;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CategoryResourceTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        // Create and authenticate admin user
        $this->actingAs(User::factory()->create([
            'email' => 'admin@example.com',
        ]));
    }

    public function test_can_list_categories(): void
    {
        $response = $this->get('/admin/categories');
        $response->assertStatus(200);
    }
}
```

**For HTTP page tests** (Home, Category, Product pages):
```php
class HomePageTest extends TestCase
{
    use RefreshDatabase;

    public function test_homepage_shows_products(): void
    {
        // Seed some products first
        $this->seed([
            \Database\Seeders\CategorySeeder::class,
            \Database\Seeders\BrandSeeder::class,
            \Database\Seeders\ProductSeeder::class,
        ]);

        $response = $this->get(route('home'));
        $response->assertStatus(200);
        $response->assertSee('Sản phẩm tiêu biểu');
    }
}
```

---

### `composer.json` (MODIFY)

**Current pattern (lines 8-13):**
```json
"require": {
    "php": "^8.3",
    "filament/filament": "^5.6",
    "laravel/framework": "^13.8",
    "laravel/tinker": "^3.0"
},
```

**Add:**
```json
"intervention/image-laravel": "^1.0"
```

The `filament/filament` is already installed at `^5.6` — note that `->live()` (not `->reactive()`) is the Filament v5 syntax for dependent fields.

---

## Shared Patterns

### Model Pattern (PHP 8 Attributes)
**Source:** `app/Models/User.php` (lines 13-15)
**Apply to:** All 3 Phase 2 models (Category, Brand, Product)

```php
use Illuminate\Database\Eloquent\Attributes\Fillable;

#[Fillable(['col1', 'col2', ...])]
class MyModel extends Model
{
    protected function casts(): array
    {
        return ['col' => 'type'];
    }
}
```

The project uses the modern `#[Fillable([...])]` PHP 8 attribute pattern, NOT the traditional `protected $fillable = [...]` property. All new models must follow this.

### Filament Resource Discovery Pattern
**Source:** `app/Providers/Filament/AdminPanelProvider.php` (line 34)
**Apply to:** All Filament resources

```php
->discoverResources(in: app_path('Filament/Resources'), for: 'App\Filament\Resources')
```

Filament auto-discovers resources placed in `app/Filament/Resources/`. No manual registration needed.

### Filament v5 Key API Differences
**Source:** RESEARCH.md (Pitfall 1, lines 461-465)
**Apply to:** All Filament resources

| Old (v3/v4) | New (v5) |
|---|---|
| `->reactive()` | `->live()` |
| `use Filament\Forms\Components\Select;` | `use Filament\Forms;` then `Forms\Components\Select::make(...)` |

### Intervention Image Resize via afterSave Hook
**Source:** RESEARCH.md lines 371-389
**Apply to:** `ProductResource/Pages/CreateProduct.php`, `EditProduct.php`

```php
protected function afterSave(): void
{
    $product = $this->getRecord();
    $images = $product->images ?? [];
    foreach ($images as $index => $imagePath) {
        $fullPath = Storage::disk('public')->path($imagePath);
        if (file_exists($fullPath)) {
            $img = Image::decode($fullPath)
                ->resize(width: 600, height: 600)
                ->encodeUsingFileExtension(
                    pathinfo($imagePath, PATHINFO_EXTENSION),
                    quality: 85
                );
            Storage::disk('public')->put($imagePath, $img);
        }
    }
}
```

### Unique Filename for Uploaded Images
**Source:** RESEARCH.md lines 474-476
**Apply to:** Product image FileUpload

```php
->getUploadedFileNameForStorageUsing(fn (TemporaryUploadedFile $file): string =>
    (string) str($file->getClientOriginalName())->prepend((string) \Illuminate\Support\Str::uuid() . '_')
)
```

### Storage Symlink
**Source:** RESEARCH.md lines 479-481
**Apply to:** Deployment / setup checklist

`php artisan storage:link` must be run for uploaded images to be accessible.

### DatabaseSeeder Call Chain Pattern
**Source:** `database/seeders/DatabaseSeeder.php`
**Apply to:** The seeder file's `run()` method

```php
$this->call([
    CategorySeeder::class,
    BrandSeeder::class,
    ProductSeeder::class,
]);
```

### Eloquent Search Pattern (MySQL LIKE)
**Source:** `app/Http/Controllers/ProductController.php` (lines 25-31)
**Apply to:** Any search implementation context

```php
if ($search = request('search')) {
    $query->where(function ($q) use ($search) {
        $q->where('name', 'like', "%{$search}%")
          ->orWhere('sku', 'like', "%{$search}%")
          ->orWhere('short_description', 'like', "%{$search}%");
    });
}
```

### Frontend Route Pattern
**Source:** `routes/web.php` (lines 1-15)
**Apply to:** No route changes needed for Phase 2 (all already registered)

```
GET  /                          → HomeController@index
GET  /san-pham                  → ProductController@index
GET  /san-pham/{slug}           → ProductController@show
GET  /danh-muc/{slug}           → CategoryController@show
```

---

## No Analog Found

Files with no close match in the codebase (planner should use RESEARCH.md patterns instead + Filament docs):

| File | Role | Data Flow | Reason |
|------|------|-----------|--------|
| `app/Filament/Resources/CategoryResource.php` | resource | CRUD | No existing Filament resource in codebase |
| `app/Filament/Resources/BrandResource.php` | resource | CRUD | No existing Filament resource in codebase |
| `app/Filament/Resources/ProductResource.php` | resource | CRUD | No existing Filament resource in codebase |
| All pages under `app/Filament/Resources/*/Pages/` | page | CRUD | Generated by `php artisan make:filament-resource` |

**Guidance for Filament Resources:**
- Use `php artisan make:filament-resource Category --generate` to scaffold all 3 resources (generates Resource + Pages)
- After scaffolding, manually add the form schema, table schema, cascading selects, FileUpload configs, and Repeater
- The generated Page files (List, Create, Edit) need the `afterSave()` / `mutateFormDataBeforeSave()` hooks added for Intervention Image resize

---

## Metadata

**Analog search scope:**
- `app/Models/*.php` — User is only filled model (pattern source)
- `app/Http/Controllers/*.php` — Controller patterns for Eloquent queries
- `app/Providers/Filament/AdminPanelProvider.php` — Filament config
- `database/seeders/DatabaseSeeder.php` — Seeder pattern
- `database/migrations/*.php` — DB schema reference
- `resources/views/**/*.blade.php` — Blade template patterns
- `routes/web.php` — Route patterns
- `tests/Feature/ExampleTest.php` — Test patterns
- `composer.json` — Dependency config

**Files scanned:** 28
**Pattern extraction date:** 2026-06-27
