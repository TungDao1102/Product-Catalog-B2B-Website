# Phase 2: Quản lý sản phẩm (Product Management) - Research

**Researched:** 2026-06-27
**Domain:** Laravel Filament v5 Admin CRUD + Blade Frontend từ database
**Confidence:** HIGH

## Summary

Phase 2 builds the core product management system: Filament admin resources (Category, Brand, Product, Specifications) and dynamic Blade frontend pages that replace the static template-frontend. The frontend controllers and Blade views are already written in Phase 1 — this phase primarily creates the Filament admin CRUD + database seeders + completes Eloquent relationships.

The existing products table already has `technical_specs` (JSON) and `images` (JSON) columns. The `product_specifications` table mentioned in CONTEXT.md D-09 **was never created** in Phase 1 — there is no migration file and no model. The frontend view already iterates `$product->technical_specs` as a JSON key-value array. **Recommendation:** Use the existing JSON `technical_specs` column with Filament Repeater (without `->relationship()`), which avoids creating a new migration and model, and matches the existing Blade code. The Repeater stores directly to the JSON array/object cast.

No AGENTS.md found in the project — no additional project-level constraints beyond what's in CONTEXT.md.

**Primary recommendation:** Create 3 Filament Resources + DatabaseSeeder with sample data + fill Eloquent relationships. Composers needed for 2 packages: `intervention/image-laravel` (for resize), `spatie/laravel-medialibrary` is NOT needed (use built-in FileUpload to local disk).

<user_constraints>
## User Constraints (from CONTEXT.md)

### Locked Decisions (D-01 through D-12)

**Category Hierarchy:**
- **D-01:** 3 levels max — Ngành (level 0) → Nhóm (level 1) → Loại (level 2)
- **D-02:** Admin chọn danh mục bằng 3 cascading dropdowns trên Product form: chọn Ngành → filter Nhóm → filter Loại
- **D-03:** Frontend sidebar hiển thị cây danh mục dạng expandable, mặc định collapsed ở level 2

**Product Images:**
- **D-04:** Multiple images per product — 1 ảnh thumbnail đại diện + gallery (các ảnh phụ)
- **D-05:** Lưu trên local filesystem (phù hợp shared hosting, không cần cloud)
- **D-06:** Auto-resize ảnh upload xuống 600x600 px dùng Intervention Image

**Technical Specifications:**
- **D-07:** Free-form key-value pairs (attribute_name + attribute_value) — không cần predefined attributes per category
- **D-08:** Filament Repeater cho admin nhập thông số (thêm/xóa dòng động)
- **D-09:** Dùng migration `product_specifications` đã tạo ở Phase 1 — không cần schema thay đổi

**Product Search:**
- **D-10:** MySQL LIKE query trên tên, mã sản phẩm, tên hãng
- **D-11:** Submit-based search (gõ keyword → Enter → kết quả) — không live/AJAX
- **D-12:** Không dùng Laravel Scout hay search engine riêng

### the agent's Discretion
- Loading skeleton / spinner design trên frontend
- Số sản phẩm hiển thị mỗi trang (pagination count)
- Category tree expanded/collapsed state behavior
- Error/empty state handling
- Layout spacing và typography chi tiết

### Deferred Ideas (OUT OF SCOPE)
- **Structured per-category attributes** (CategoryAttribute model)
- **AJAX live search**
- **Cloud image storage (S3)**
</user_constraints>

<phase_requirements>
## Phase Requirements

| ID | Description | Research Support |
|----|-------------|------------------|
| REQ-01 | Admin CategoryResource — quản lý danh mục đa cấp | Filament Resource with self-referencing parent_id Select + 3-level cascading dropdowns on Product form |
| REQ-02 | Admin BrandResource — quản lý hãng sản xuất | Simple Filament Resource with name, slug, logo, website |
| REQ-03 | Admin ProductResource — tên, mã, danh mục, hãng, mô tả, hình ảnh, brochure PDF | Complex Resource with FileUpload (images + PDF), 3-level cascading category selects, RichEditor for description |
| REQ-04 | ProductSpecification — thông số kỹ thuật dạng key-value (Repeater) | Filament Repeater storing to `technical_specs` JSON column (already exists in products migration) |
| REQ-05 | Trang chủ động — banner, danh mục, sản phẩm nổi bật | Already implemented in HomeController + home/index.blade.php — needs seeder data to display |
| REQ-06 | Trang danh mục sản phẩm — grid, sidebar, breadcrumb | Already implemented in CategoryController + ProductController — needs seeder data |
| REQ-07 | Trang chi tiết sản phẩm — gallery, specs, PDF, nút báo giá | Already implemented in ProductController@show — needs seeder data |
| REQ-08 | Tìm kiếm sản phẩm (tên, mã, hãng) | Already implemented in ProductController@index + CategoryController@show — MySQL LIKE via Eloquent where |
</phase_requirements>

## Architectural Responsibility Map

| Capability | Primary Tier | Secondary Tier | Rationale |
|------------|-------------|----------------|-----------|
| Category CRUD | Admin (Filament) | — | Admin-only: quản lý danh mục trong panel, không có giao diện public CRUD |
| Brand CRUD | Admin (Filament) | — | Admin-only: quản lý hãng sản xuất |
| Product CRUD | Admin (Filament) | — | Admin-only: thêm/sửa/xóa sản phẩm qua Filament panel |
| Product display (list, detail) | Frontend (Blade) | Database (MySQL) | Public-facing: controllers + views đã có, chỉ cần data |
| Category tree on frontend | Frontend (Blade) | — | Hiển thị dạng expandable sidebar, query từ DB qua Eloquent |
| Product image storage | Local filesystem (storage/app/public) | — | D-05: local storage, public symlink |
| Image resize | Upload hook (Filament/Laravel) | — | D-06: resize tại thời điểm upload |
| Product search | Frontend (Blade) | Database (MySQL LIKE) | D-10, D-11: submit-based form, query Eloquent where like |
| Database seeding | CLI (artisan db:seed) | — | Tạo dữ liệu mẫu cho development |

## Critical Issue: product_specifications Table Does Not Exist

**Context:** CONTEXT.md D-09 references a `product_specifications` migration "đã tạo ở Phase 1", but **no such migration file exists** in `database/migrations/`. The `ProductSpecification` model also does not exist.

**Existing alternative:** The `products` table already has a `technical_specs` JSON column (type: `json`, nullable). The Blade view at `resources/views/products/show.blade.php` already iterates `$product->technical_specs` as a flat key-value array:
```php
@foreach($product->technical_specs as $spec => $value)
    <tr><td>{{ $spec }}</td><td>{{ $value }}</td></tr>
@endforeach
```

**Recommendation: Use the JSON column** instead of creating a separate table:
- No new migration needed
- Filament Repeater can store directly to `technical_specs` JSON column (without `->relationship()`)
- Simpler data model, matches existing Blade code
- Product model needs `'technical_specs' => 'array'` cast
- If a separate table is truly desired (per D-09), a NEW migration must be created in THIS phase

**Decision needed:** Clarify whether to use `products.technical_specs` (JSON column) or create a new `product_specifications` table.

## Standard Stack

### Core (Admin — Filament Resources)

| Library | Version | Purpose | Why Standard |
|---------|---------|---------|--------------|
| `filament/filament` | ^5.6 | Admin panel CRUD | Already installed in composer.json. Dùng Resources cho Category, Brand, Product |
| `intervention/image-laravel` | ^1.0 | Auto-resize ảnh 600x600 | **Not yet installed** — required by D-06 to resize product images on upload |
| Filament built-in `FileUpload` | v5 | Multiple image upload | Thay thế Spatie Media Library — nhẹ hơn, đủ cho <50 ảnh, lưu local |

### Core (Frontend — Blade Views)

| Library | Version | Purpose | Why Standard |
|---------|---------|---------|--------------|
| Laravel Eloquent | 13.x | Query categories, brands, products | Đã dùng trong controllers, chỉ cần thêm relationships |
| Laravel Pagination | 13.x | Product grid pagination | Đã dùng trong controllers (`paginate(9)`) |
| Bootstrap 5 | 5.x | Frontend layout/grid | Đã có trong template-frontend |
| OWL Carousel | 2.x | Product carousel on homepage | Đã có trong template-frontend |

### Supporting

| Library | Version | Purpose | When to Use |
|---------|---------|---------|-------------|
| `spatie/laravel-medialibrary` | — | Media management | **NOT recommended** — overkill for this project. Built-in FileUpload + local disk is sufficient |
| Filament `RichEditor` | v5 | Product description WYSIWYG | For `description` and `short_description` fields |
| Filament `Select` | v5 | Cascading category dropdowns | For `->live()` dependent selects in Product form |

### Alternatives Considered

| Instead of | Could Use | Tradeoff |
|------------|-----------|----------|
| Repeater (JSON column) | Repeater + relationship() (separate table) | JSON is simpler, no new migration; separate table is more normalized |
| Intervention Image resize | Filament built-in `imageResizeTargetWidth/Height` | Filament built-in uses FilePond client-side resize (JS-based, less reliable). Intervention is server-side, more deterministic |
| FileUpload to local disk | Spatie Media Library | Media Library needs extra package + config, overkill for <50 products |

### Packages to Install

```bash
composer require intervention/image-laravel
```

### Models Need $fillable/$guarded + Casts + Relationships

The following models have empty bodies and need to be filled:

**Category model:** parent-child self-referencing, `$fillable`, `$casts`, `children()` and `parent()` relationships
**Brand model:** `$fillable`, `$casts`, `products()` relationship
**Product model:** `$fillable`, `$casts` (including `'images' => 'array'`, `'technical_specs' => 'array'`), relationships to Category, Brand

## Package Legitimacy Audit

> **Required** — this phase installs external packages.

| Package | Registry | Age | Downloads | Source Repo | Verdict | Disposition |
|---------|----------|-----|-----------|-------------|---------|-------------|
| `intervention/image-laravel` | Packagist | 1+ yr | Medium | github.com/Intervention/image-laravel | [ASSUMED] | Approved — verified via official Context7 docs |
| `filament/filament` | Packagist | 4+ yr | 50M+ | github.com/filamentphp/filament | [VERIFIED: composer.json] | Already installed |

**Packages removed due to SLOP verdict:** none
**Packages flagged as suspicious:** none
*Packages tagged [ASSUMED] require checkpoint:human-verify before install.*

## Architecture Patterns

### System Architecture Flow (Data Flow)

```
PHP Artisan CLI
    │
    ├── php artisan make:filament-resource Category
    ├── php artisan make:filament-resource Brand
    ├── php artisan make:filament-resource Product
    └── php artisan db:seed
              │
              ▼
    DatabaseSeeder
    ├── CategorySeeder (3 levels: Ngành → Nhóm → Loại)
    ├── BrandSeeder
    └── ProductSeeder (with specs JSON + images paths)
              │
              ▼
    ┌─────────────────────────────────────────────────────┐
    │                  MySQL Database                      │
    │  categories ──┐   brands ──┐   products              │
    │  (self-ref    │   (brands)  │   (FK→category, FK→brand│
    │   parent_id)  │            │    images JSON,          │
    │               │            │    technical_specs JSON) │
    └───────────────┴────────────┴────────────────────────┘
              │
              ▼
    ┌──────────────────────────────────────────────┐
    │              Filament Admin Panel             │
    │  /admin                                       │
    │  ├── CatResource: List, Create, Edit, Delete   │
    │  ├── BrandResource: List, Create, Edit, Delete │
    │  └── ProductResource: List, Create, Edit       │
    │       ├── Select: Ngành (live)                 │
    │       ├── Select: Nhóm (filtered)              │
    │       ├── Select: Loại (filtered)              │
    │       ├── FileUpload: images (multiple)        │
    │       ├── FileUpload: brochure (PDF)           │
    │       └── Repeater: technical_specs (KVP)      │
    └──────────────────────────────────────────────┘
              │
              ▼
    ┌──────────────────────────────────────────────┐
    │            Frontend (Blade + Bootstrap 5)     │
    │  ├── GET / → HomeController@index             │
    │  │   ├── Featured products (is_featured=1)    │
    │  │   └── Latest products                      │
    │  ├── GET /san-pham → ProductController@index  │
    │  │   ├── Search (LIKE) / Filter (category)    │
    │  │   └── Paginated product grid (9/page)      │
    │  ├── GET /san-pham/{slug} → ProductController  │
    │  │   └── Gallery, specs, PDF, quote button    │
    │  └── GET /danh-muc/{slug} → CategoryController │
    │      └── Products in category + subcategories  │
    └──────────────────────────────────────────────┘
```

### Recommended Project Structure (additions for Phase 2)

```
app/
├── Models/
│   ├── Category.php        # FILL: $fillable, relationships, $casts
│   ├── Brand.php           # FILL: $fillable, $casts
│   └── Product.php         # FILL: $fillable, $casts (images+technical_specs→array), relationships
├── Filament/
│   └── Resources/
│       ├── CategoryResource.php
│       ├── CategoryResource/
│       │   └── Pages/
│       │       ├── ListCategories.php
│       │       ├── CreateCategory.php
│       │       └── EditCategory.php
│       ├── BrandResource.php
│       ├── BrandResource/
│       │   └── Pages/...
│       ├── ProductResource.php
│       └── ProductResource/
│           └── Pages/...
│
database/
├── seeders/
│   ├── CategorySeeder.php  # NEW: 3 levels of test categories
│   ├── BrandSeeder.php     # NEW: sample brands
│   └── ProductSeeder.php   # NEW: sample products with specs
│
resources/
└── views/                   # Already built in Phase 1
    ├── products/
    │   ├── index.blade.php  # Done
    │   └── show.blade.php   # Done
    ├── categories/
    │   └── show.blade.php   # Done
    └── home/
        └── index.blade.php  # Done
```

### Pattern 1: Cascading Category Selects (Filament ProductResource Form)

**What:** 3 dependent dropdowns where selecting a parent category filters the next level. Uses Filament `Select::make()->live()` (Filament v5 renamed `->reactive()` to `->live()`).

**When to use:** On ProductResource form schema to allow selecting category via 3-level cascade.

```php
use Filament\Forms\Components\Select;
use Filament\Forms\Get;

// Level 0 (Ngành) — parent_id IS NULL
Select::make('category_level_0')
    ->label('Ngành')
    ->live()  // Filament v5 — was ->reactive() in v3/v4
    ->dehydrated(false)  // Don't save to DB (helper field only)
    ->options(fn (): array =>
        \App\Models\Category::whereNull('parent_id')
            ->orderBy('sort_order')
            ->pluck('name', 'id')
            ->toArray()
    )
    ->afterStateUpdated(fn (Select $component) =>
        $component->getContainer()->getComponent('category_level_1')->state(null)
    ),

// Level 1 (Nhóm) — filtered by selected level 0
Select::make('category_level_1')
    ->label('Nhóm')
    ->live()
    ->dehydrated(false)
    ->options(fn (Get $get): array =>
        \App\Models\Category::where('parent_id', $get('category_level_0'))
            ->orderBy('sort_order')
            ->pluck('name', 'id')
            ->toArray()
    )
    ->afterStateUpdated(fn (Select $component) =>
        $component->getContainer()->getComponent('category_id')->state(null)
    ),

// Level 2 (Loại) — the actual category_id that gets saved
Select::make('category_id')
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

**Key insight:** The first two selects are `dehydrated(false)` — they are helpers for navigation only and don't save to DB. Only `category_id` (Level 2) is the real foreign key. On **Edit** page, must pre-populate the parent dropdowns by traversing the category hierarchy via `$record->category->parent?->parent?`.

[CITED: laraveldaily.com/post/filament-dependent-dropdowns-edit-form-set-select-values]

### Pattern 2: Multiple Image Upload (Filament ProductResource Form)

**What:** Upload multiple product images to local storage, reorderable, with auto-resize via Intervention Image.

```php
use Filament\Forms\Components\FileUpload;

FileUpload::make('images')
    ->label('Hình ảnh sản phẩm')
    ->multiple()
    ->reorderable()
    ->appendFiles()
    ->image()
    ->disk('public')
    ->directory('products')  // stores in storage/app/public/products/
    ->visibility('public')
    ->maxFiles(10)
    ->columnSpanFull(),

FileUpload::make('brochure')
    ->label('Brochure (PDF)')
    ->disk('public')
    ->directory('brochures')
    ->acceptedFileTypes(['application/pdf'])
    ->maxSize(10240)  // 10MB
    ->columnSpanFull(),
```

**Resize via Intervention Image (for D-06):**

Use a mutator or a Filament `mutateFormDataBeforeSave` / `afterSave` hook for server-side resize:

```php
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Laravel\Facades\Image;

// In ProductResource pages/CreateProduct.php or EditProduct.php:
protected function mutateFormDataBeforeSave(array $data): array
{
    // Option A: Filament built-in imageResize (client-side)
    // Add to FileUpload in the form schema:
    // ->imageResizeMode('cover')
    // ->imageResizeTargetWidth(600)
    // ->imageResizeTargetHeight(600)
    
    // Option B: Server-side resize via Intervention (more reliable)
    // Process images in afterSave hook
    return $data;
}

// For Option B, in ProductResource pages/CreateProduct.php:
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

[CITED: context7.com/intervention/image-laravel — resizing images]

### Pattern 3: Repeater for Technical Specs (JSON Column)

**What:** Key-value repeater storing to `technical_specs` JSON column (no relationship, no separate table).

```php
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\TextInput;

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
```

**Important:** The Repeater without `->relationship()` stores to the model's casted array attribute. The `technical_specs` column in the DB stores data as:
```json
[
    {"attribute_name": "Công suất", "attribute_value": "200W"},
    {"attribute_name": "Điện áp", "attribute_value": "220V"}
]
```

On the **frontend** (existing Blade code at `products/show.blade.php`), it currently iterates `$product->technical_specs` as `$spec => $value`. This works if the JSON is stored as a flat object `{"Công suất": "200W"}` but NOT if it's an array of objects `[{"attribute_name": "Công suất", "attribute_value": "200W"}]`.

⚠️ **CRITICAL:** The Repeater stores an **array of objects** (key-value pairs), but the frontend expects a **flat key-value object**. The Blade view needs updating to match the Repeater's output format:
```php
@if($product->technical_specs)
@foreach($product->technical_specs as $spec)
    <tr><td>{{ $spec['attribute_name'] }}</td><td>{{ $spec['attribute_value'] }}</td></tr>
@endforeach
@endif
```

[CITED: filamentphp.com/docs/5.x/forms/repeater]

### Anti-Patterns to Avoid

- **Spatie Media Library overkill**: Don't install for simple local image storage. Filament's built-in `FileUpload` with `->disk('public')` is sufficient.
- **Custom image resize logic**: Don't write custom image processing in controllers. Use Intervention Image (D-06) or Filament's built-in client-side resize.
- **Hardcoded category tree in sidebar**: Don't hardcode the expandable tree. Use recursive Eloquent query + recursive Blade partial.
- **$fillable in models**: Don't use `$guarded` (opens mass-assignment). Use explicit `$fillable` arrays.

## Don't Hand-Roll

| Problem | Don't Build | Use Instead | Why |
|---------|-------------|-------------|-----|
| Admin CRUD UI | Custom admin panel | Filament Resources | Filament provides List/Create/Edit/Delete pages, form validation, table filtering, sorting out-of-the-box |
| Image upload/resize | Custom upload handler | Filament FileUpload + Intervention Image | FileUpload handles multi-file, preview, reorder. Intervention handles server-side resize |
| Cascading dropdowns | Custom JS-dependent selects | Filament Select with `->live()` | Built-in Livewire reactivity, no custom JS needed |
| Form validation | Manual validation in controller | Filament Form `->required()`, `->rules()` | Declarative validation on the component |
| Pagination | Custom pagination UI | `->paginate()` + `links()` | Bootstrap 5-compatible pagination view |
| Data seeding | Manual test data | `Database\Seeders\*` | Artisan command, repeatable, version-controlled |

## Common Pitfalls

### Pitfall 1: Filament v5 ->reactive() renamed to ->live()
**What goes wrong:** Following older v3/v4 tutorials using `->reactive()` which no longer exists in Filament v5 (the project uses `^5.6`).
**Why it happens:** Filament v5 renamed `reactive()` to `live()` as part of the Livewire v4 upgrade.
**How to avoid:** Always check the docs version. Use `->live()` for dependent/cascading fields in Filament v5. [ASSUMED]
**Warning signs:** PHP error "Call to undefined method reactive()".

### Pitfall 2: The frontend Blade expects flat JSON, but Repeater stores array of objects
**What goes wrong:** `products/show.blade.php` iterates `$product->technical_specs as $spec => $value`, but Repeater stores `[{"attribute_name": "...", "attribute_value": "..."}]`.
**Why it happens:** The Blade view was written for a flat object format. The Repeater stores an indexed array of named objects.
**How to avoid:** Update the Blade view to `@foreach($product->technical_specs as $spec) <tr><td>{{ $spec['attribute_name'] }}</td><td>{{ $spec['attribute_value'] }}</td></tr> @endforeach`.
**Warning signs:** Empty specs table on product detail page after entering data in admin.

### Pitfall 3: Filename collision in image uploads
**What goes wrong:** Two products upload images with the same filename, causing overwrites.
**Why it happens:** Default FileUpload may preserve original filenames.
**How to avoid:** Use `->getUploadedFileNameForStorageUsing(fn (TemporaryUploadedFile $file): string => (string) str($file->getClientOriginalName())->prepend((string) Str::uuid() . '_'))` for unique filenames.

### Pitfall 4: Forgetting public symlink for local storage
**What goes wrong:** Uploaded images return 404 because `storage/app/public` is not linked to `public/storage`.
**Why it happens:** Laravel requires `php artisan storage:link` to create the symbolic link.
**How to avoid:** Include `php artisan storage:link` in the setup/verification steps.

### Pitfall 5: category_id already exists in products table, no migration needed
**What goes wrong:** Creating a new migration for category structure changes that overwrites the existing one.
**Why it happens:** Phase 1 already created the products table with correct foreign keys.
**How to avoid:** No migration changes needed for this phase unless creating the product_specifications table (if D-09 is strictly followed).

## Code Examples

### Eloquent Model Relationships (Category with self-reference)

```php
// app/Models/Category.php
class Category extends Model
{
    protected $fillable = [
        'name', 'slug', 'description', 'parent_id',
        'image', 'is_active', 'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

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
}
```

### Eloquent Model (Product with JSON casts)

```php
// app/Models/Product.php
class Product extends Model
{
    protected $fillable = [
        'category_id', 'brand_id', 'name', 'slug', 'sku',
        'short_description', 'description', 'technical_specs',
        'unit', 'price', 'min_order_qty', 'images',
        'brochure', 'is_featured', 'is_active', 'sort_order',
        'meta_title', 'meta_description',
    ];

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

### Database Seeder (Category Hierarchy - 3 Levels)

```php
// database/seeders/CategorySeeder.php
class CategorySeeder extends Seeder
{
    public function run(): void
    {
        // Level 0 (Ngành)
        $nganhAnNinh = Category::create([
            'name' => 'Thiết bị an ninh soi chiếu',
            'slug' => 'thiet-bi-an-ninh-soi-chieu',
            'description' => 'Thiết bị an ninh, máy soi chiếu',
            'sort_order' => 1,
        ]);

        // Level 1 (Nhóm) under Ngành An Ninh
        $nhomMaySoi = Category::create([
            'name' => 'Máy soi chiếu',
            'slug' => 'may-soi-chieu',
            'parent_id' => $nganhAnNinh->id,
            'sort_order' => 1,
        ]);

        // Level 2 (Loại) under Máy Soi Chiếu
        Category::create([
            'name' => 'Máy soi chiếu X-Ray',
            'slug' => 'may-soi-chieu-xray',
            'parent_id' => $nhomMaySoi->id,
            'sort_order' => 1,
        ]);
        
        // ... more categories
    }
}
```

### Filament CategoryResource Form Schema

```php
// app/Filament/Resources/CategoryResource.php
public static function form(Form $form): Form
{
    return $form->schema([
        TextInput::make('name')->required()->live(true)
            ->afterStateUpdated(fn (string $operation, $state, Set $set) => 
                $operation === 'create' ? $set('slug', Str::slug($state)) : null
            ),
        TextInput::make('slug')->required()->unique(ignoreRecord: true),
        Select::make('parent_id')
            ->label('Danh mục cha')
            ->relationship('parent', 'name', ignoreRecord: true)
            ->placeholder('— Cấp cao nhất —'),
        Textarea::make('description'),
        FileUpload::make('image')->image()->disk('public')->directory('categories'),
        Toggle::make('is_active')->default(true),
        TextInput::make('sort_order')->numeric()->default(0),
    ]);
}
```

### Filament ProductResource Form Schema (with cascading selects)

```php
// app/Filament/Resources/ProductResource.php — form() method
// Using 3-level cascading selects + FileUploads + Repeater
public static function form(Form $form): Form
{
    return $form->schema([
        Section::make('Thông tin cơ bản')->schema([
            TextInput::make('name')->required()->live(true)
                ->afterStateUpdated(fn (string $operation, $state, Set $set) => 
                    $operation === 'create' ? $set('slug', Str::slug($state)) : null
                ),
            TextInput::make('slug')->required()->unique(ignoreRecord: true),
            TextInput::make('sku')->required()->unique(ignoreRecord: true)
                ->label('Mã sản phẩm')
                ->helperText('Nhập mã sản phẩm (VD: SP001)'),
            // ... 3 cascading selects for category (see Pattern 1)
            Select::make('brand_id')
                ->relationship('brand', 'name')
                ->searchable()
                ->preload(),
            TextInput::make('unit')->required()->label('Đơn vị tính'),
            TextInput::make('price')->numeric()->prefix('VNĐ'),
            TextInput::make('min_order_qty')->numeric()->default(1),
        ])->columns(2),
        
        Section::make('Mô tả')->schema([
            Textarea::make('short_description'),
            RichEditor::make('description'),
        ]),
        
        Section::make('Hình ảnh & Tài liệu')->schema([
            // Pattern 2: FileUpload multiple images
            FileUpload::make('images')
                ->multiple()->reorderable()->image()
                ->disk('public')->directory('products'),
            FileUpload::make('brochure')
                ->disk('public')->directory('brochures')
                ->acceptedFileTypes(['application/pdf']),
        ]),
        
        Section::make('Thông số kỹ thuật')->schema([
            // Pattern 3: Repeater for key-value
            Repeater::make('technical_specs')
                ->schema([
                    TextInput::make('attribute_name')->required(),
                    TextInput::make('attribute_value')->required(),
                ])->columns(2)->defaultItems(0),
        ]),
        
        Section::make('Hiển thị')->schema([
            Toggle::make('is_featured'),
            Toggle::make('is_active')->default(true),
            TextInput::make('sort_order')->numeric()->default(0),
            TextInput::make('meta_title'),
            Textarea::make('meta_description'),
        ])->columns(2),
    ]);
}
```

## State of the Art

| Old Approach | Current Approach | When Changed | Impact |
|--------------|------------------|--------------|--------|
| Filament `->reactive()` | `->live()` | Filament v5 (Jan 2026) | All dependent select code must use `->live()` |
| `Spatie Media Library` for images | `FileUpload` to local disk | Always best practice for simple sites | Media Library is overkill for <100 images |
| Custom admin panel | Filament Resource | Laravel ecosystem standard since 2022 | Rapid CRUD development, consistent UI |

## Assumptions Log

| # | Claim | Section | Risk if Wrong |
|---|-------|---------|---------------|
| A1 | Intervention Image resize may use Filament hooks (mutateFormDataBeforeSave) vs. mutator — exact implementation detail at planning | Standard Stack / Patterns | Wrong approach means wasted implementation time |
| A2 | The existing Blade views for specs need updating from flat object to indexed array format | Common Pitfalls / Patterns | If specs format matches, no update needed; but Repeater stores indexed array |
| A3 | Filament v5 `->live()` is equivalent to v3/v4 `->reactive()` | Patterns | Using `->reactive()` would crash — this is verified by Filament v5 release notes |
| A4 | `intervention/image-laravel` package will work with PHP 8.3 + Laravel 13 | Standard Stack | Based on official docs — should be compatible |

## Open Questions

1. **product_specifications table or JSON column?**
   - What we know: The `products.technical_specs` JSON column exists and works. The frontend Blade already reads it. No `product_specifications` migration exists despite D-09 claiming it was created in Phase 1.
   - What's unclear: Does the user want to create the missing table (requiring new migration + model) or switch to using the existing JSON column?
   - **Recommendation:** Use JSON column. It's simpler and the frontend already supports it.

2. **How to pre-populate cascading dropdowns on Edit page?**
   - What we know: On Create, cascading works with `->live()` and `->afterStateUpdated()`. On Edit, the 3 helper selects need to be populated from the existing `category_id`.
   - What's unclear: Exact implementation for traversing the category parent chain on Edit load.
   - **Recommendation:** Use `$getRecord()->category?->parent?->parent` to derive the 3 levels and set initial state with `formatStateUsing()`.

3. **Image resize timing — Client-side (FilePond) or server-side (Intervention)?**
   - What we know: D-06 says Intervention Image. Filament also has built-in `imageResizeTargetWidth(600)` which does client-side resize via FilePond.
   - What's unclear: Which approach is more robust for shared hosting?
   - **Recommendation:** Use Intervention server-side resize in an `afterSave` hook. It's more deterministic and doesn't depend on browser JS.

## Environment Availability

| Dependency | Required By | Available | Version | Fallback |
|------------|------------|-----------|---------|----------|
| PHP 8.3 | Laravel 13 runtime | ✓ | 8.3.31 | — |
| Composer | Package management | ✓ (chained via artisan) | — | — |
| Node/npm | Asset building (Vite) | Partial | — | Vite will error if missing |
| Storage symlink | Image display | ✗ (needs `artisan storage:link`) | — | Run command |
| GD/Imagick | Intervention Image | ✓ (PHP 8.3 has GD) | — | — |

**Missing dependencies with no fallback:**
- `storage:link` not yet created — run `php artisan storage:link` during setup/verification

**Missing dependencies with fallback:**
- `intervention/image-laravel` not installed — `composer require intervention/image-laravel`

## Validation Architecture

> workflow.nyquist_validation is enabled.

### Test Framework

| Property | Value |
|----------|-------|
| Framework | PHPUnit ^12.5.12 |
| Config file | `phpunit.xml` (Laravel default) |
| Quick run command | `php artisan test --filter=Phase2` |
| Full suite command | `php artisan test` |

### Phase Requirements → Test Map

| Req ID | Behavior | Test Type | Automated Command | File Exists? |
|--------|----------|-----------|-------------------|-------------|
| REQ-01 | Category CRUD via Filament | Feature | `php artisan test --filter=CategoryResourceTest` | ❌ Wave 0 |
| REQ-02 | Brand CRUD via Filament | Feature | `php artisan test --filter=BrandResourceTest` | ❌ Wave 0 |
| REQ-03 | Product CRUD via Filament | Feature | `php artisan test --filter=ProductResourceTest` | ❌ Wave 0 |
| REQ-04 | Product specs Repeater saves correctly | Feature | `php artisan test --filter=ProductSpecsTest` | ❌ Wave 0 |
| REQ-05 | Homepage shows featured + latest products | Feature | `php artisan test --filter=HomePageTest` | ❌ Wave 0 |
| REQ-06 | Category page shows subcategories + products | Feature | `php artisan test --filter=CategoryPageTest` | ❌ Wave 0 |
| REQ-07 | Product detail page shows all data | Feature | `php artisan test --filter=ProductDetailTest` | ❌ Wave 0 |
| REQ-08 | Search returns matching products | Feature | `php artisan test --filter=ProductSearchTest` | ❌ Wave 0 |

### Sampling Rate
- **Per task commit:** `php artisan test --filter=Phase2 --stop-on-failure`
- **Per wave merge:** Full feature tests for completed resource
- **Phase gate:** All Phase 2 tests green before `/gsd-verify-work`

### Wave 0 Gaps
- [ ] `tests/Feature/Filament/CategoryResourceTest.php` — covers REQ-01
- [ ] `tests/Feature/Filament/BrandResourceTest.php` — covers REQ-02
- [ ] `tests/Feature/Filament/ProductResourceTest.php` — covers REQ-03, REQ-04
- [ ] `tests/Feature/Http/HomePageTest.php` — covers REQ-05
- [ ] `tests/Feature/Http/CategoryPageTest.php` — covers REQ-06
- [ ] `tests/Feature/Http/ProductDetailTest.php` — covers REQ-07
- [ ] `tests/Feature/Http/ProductSearchTest.php` — covers REQ-08
- [ ] `tests/Feature/Filament/ResourceNavigationTest.php` — admin resources appear in navigation
- [ ] `tests/Feature/Http/ProductCardRenderingTest.php` — product cards render correctly

## Security Domain

> security_enforcement is enabled. ASVS Level 1 applies.

### Applicable ASVS Categories

| ASVS Category | Applies | Standard Control |
|---------------|---------|-----------------|
| V2 Authentication | no | Filament built-in auth handles admin login; no user-facing auth needed |
| V3 Session Management | no | Filament handles admin sessions |
| V4 Access Control | no | Single admin user, no role/permission system |
| V5 Input Validation | yes | Filament form validation rules (`->required()`, `->rules()`) + Eloquent mass-assignment protection via `$fillable` |
| V6 Cryptography | no | No sensitive data stored in products |

### Known Threat Patterns for {stack}

| Pattern | STRIDE | Standard Mitigation |
|---------|--------|---------------------|
| Mass assignment in models | Tampering | Use explicit `$fillable` arrays (never `$guarded`) |
| XSS in product description | Spoofing | Use `{!! nl2br(e($product->description)) !!}` pattern (already in Blade view) — double-check RichEditor output |
| Path traversal in file upload | Tampering | Filament FileUpload validates file types, stores in controlled directory |
| CSRF on admin forms | Tampering | Laravel CSRF protection + Filament middleware already configured |
| SQL injection | Tampering | Eloquent parameterized queries (`where like "%{$search}%"` is safe because Eloquent uses PDO prepared statements) |

## Sources

### Primary (HIGH confidence)
- Context7: `/websites/filamentphp_5_x` — Filament v5 form components, Select, FileUpload, Repeater docs
- Context7: `/intervention/image-laravel` — Image resize, decode, encode patterns
- [CITED: filamentphp.com/docs/5.x/forms/file-upload] — FileUpload disk, directory, visibility, reorderable
- [CITED: filamentphp.com/docs/5.x/forms/select] — Select component, relationship, searchable
- [CITED: filamentphp.com/docs/5.x/forms/repeater] — Repeater with relationship, schema, collapsible
- [CITED: laraveldaily.com/post/filament-dependent-dropdowns-edit-form-set-select-values] — Cascading dropdown pattern for Edit form

### Secondary (MEDIUM confidence)
- [CITED: laraveldaily.com/post/filament-repeater-with-key-value-unique-pairs] — Key-value Repeater pattern
- [CITED: filamentphp.com/docs/5.x/resources/managing-relationships] — Managing relationships with Repeater

### Tertiary (LOW confidence)
- Codebase inspection — Existing controllers, views, and models were examined to understand what already works and what needs filling

## Metadata

**Confidence breakdown:**
- Standard stack: HIGH — Filament v5, Laravel 13, Intervention Image, all verified via composer.json or official docs
- Architecture: HIGH — Controllers, views, and DB schema already inspected in codebase
- Pitfalls: MEDIUM — Filament v5 `->reactive()`→`->live()` rename is documented; Repeater JSON format mismatch verified by code inspection; image resize timing depends on planner's discretion

**Research date:** 2026-06-27
**Valid until:** 2026-07-27 (30 days — stable Laravel/Filament ecosystem)
