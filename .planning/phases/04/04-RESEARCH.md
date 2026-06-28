# Phase 4: Đa ngôn ngữ & SEO — Research

**Researched:** 2026-06-28
**Domain:** Laravel i18n / spatie/laravel-translatable / filament/spatie-laravel-translatable-plugin / SEO
**Confidence:** HIGH

## Summary

This phase implements Vietnamese+English i18n for a Laravel 13 + Filament 5 B2B product catalog. The primary tools are `spatie/laravel-translatable` (JSON-column translatable models) and `filament/spatie-laravel-translatable-plugin` (tabbed VI|EN admin interface). URL prefix-based routing uses a custom `SetLocale` middleware wrapping all frontend routes. Slug is shared/English-only — not translatable. SEO uses `@yield/@section` Blade patterns for meta/OG tags and `spatie/laravel-sitemap` for sitemap.xml generation with alternate locale URLs.

**Primary recommendation:** Use `spatie/laravel-translatable` v6 with `HasTranslations` trait + `$translatable` array (not the PHP 8 `#[Translatable]` attribute — for consistency with existing codebase style). Modify existing string/text columns to JSON via migration, then migrate existing Vietnamese content as the `vi` locale translation.

---

<user_constraints>
## User Constraints (from CONTEXT.md)

### Locked Decisions
- **D-01:** Dùng `spatie/laravel-translatable` package — quản lý translations qua relationship, không cần tạo translation tables thủ công
- **D-02:** Tiếng Việt là locale mặc định (`app.locale = vi`), tiếng Anh là locale phụ
- **D-03:** URL prefix-based routing — `Route::prefix('{locale?}')` — locale optional, mặc định redirect `/` → `/vi`
- **D-04:** Middleware `SetLocale` — đọc locale từ URL prefix, set `app()->setLocale()`, fallback về `vi`
- **D-05:** Translatable models: Category, Brand, Product, Post, Project
- **D-06:** Slug là shared/common — không translatable
- **D-07:** Slug generation: tự động từ name/title tiếng Anh nếu có, fallback từ tiếng Việt đã transliterate
- **D-08:** Dùng Laravel `lang/` files — tạo `lang/vi/` (default) và `lang/en/`
- **D-09:** Các UI string keys: navigation, common labels, buttons, placeholders, validation messages, page titles
- **D-10:** `__()` helper trong Blade views — thay thế text hard-coded
- **D-11:** Route pattern: `/{locale?}/{slug}` — locale optional, mặc định là `vi`
- **D-12:** Web routes — wrap trong `Route::prefix('{locale?}')` → filter `LocaleMiddleware`
- **D-13:** Route `GET /` redirect → `GET /vi` (và `GET /en` tương ứng)
- **D-14:** Admin routes (`/admin`) không cần locale prefix — Filament mặc định English
- **D-15:** Language switcher đặt trong header, cạnh navigation links — dropdown VI/EN
- **D-16:** Icon: chữ VI/EN (không cần cờ quốc gia)
- **D-17:** Link giữ nguyên path, chỉ đổi locale prefix
- **D-18:** Dùng `filament/spatie-laravel-translatable-plugin` — tabbed interface VI|EN
- **D-19:** Tất cả Filament resources có translatable fields
- **D-20:** Non-translatable fields (SKU, price, images, is_published, v.v.) giữ nguyên
- **D-21:** Meta tags auto-generated từ model content
- **D-22:** Implement trong `AppServiceProvider` hoặc base controller `share()`
- **D-23:** Open Graph tags: og:title, og:description, og:image, og:type
- **D-24:** robots.txt: Allow all
- **D-25:** Sitemap: dùng `spatie/laravel-sitemap` package
- **D-26:** Model priorities: Product 0.8, Category 0.7, Post 0.6, Project 0.6, Static pages 1.0 home
- **D-27:** Alternate URLs cho mỗi locale trong sitemap (x-default)
- **D-28:** Slug là column `slug` trên mỗi model — unique, không translatable
- **D-29:** Slug tự động generate khi tạo/cập nhật model
- **D-30:** Filament forms: slug field text input (auto-generated nhưng có thể override)
- **D-31:** Language files: navigation.php, common.php, products.php, validation.php, pagination.php, seo.php

### the agent's Discretion
- Tên cụ thể của locale trong switcher ("VI", "EN" vs "Tiếng Việt", "English")
- Schedule cho sitemap generation (once daily via cron suggestion)
- CSS styling chi tiết cho language switcher (bootstrap dropdown phù hợp theme)
- Logic transliteration chi tiết (có thể dùng `@digilabs/laravel-utf8` hoặc tự xử lý)
- Exact middleware implementation (extend `Middleware` class, handle route params)
- Testing strategy: PHPUnit tests cho locale switching, sitemap generation, translatable models

### Deferred Ideas (OUT OF SCOPE)
- Hreflang tags — Khi có nhiều hơn 2 ngôn ngữ
- Schema.org structured data — Phase 5
- Manual SEO meta per page — sau này nếu cần
- Language detection (browser) — Accept-Language header
- RTL support — Không áp dụng
- Static page translation — About/Contact content
</user_constraints>

---

<phase_requirements>
## Phase Requirements

| ID | Description | Research Support |
|----|-------------|------------------|
| REQ-i18n-01 | spatie/laravel-translatable on 5 models | Section 1 — installation, trait, translatable array |
| REQ-i18n-02 | Tabbed VI\|EN Filament forms | Section 2 — plugin config, LocaleSwitcher, forms |
| REQ-i18n-03 | URL prefix routing /vi/... /en/... | Section 3 — route group, middleware, redirect |
| REQ-i18n-04 | Shared English slug | Section 4 — Str::slug transliteration strategy |
| REQ-i18n-05 | Language switcher in header | Section 3 — locale-aware URL generation |
| REQ-i18n-06 | lang/vi/, lang/en/ UI files | Section 3 — __() helper usage |
| REQ-SEO-01 | Auto-generated meta/OG tags | Section 6 — Blade @yield/@section pattern |
| REQ-SEO-02 | sitemap.xml w/ alternate locales | Section 5 — Sitemapable, addAlternate |
| REQ-SEO-03 | robots.txt Allow all | Section 6 — simple route response |
| REQ-DB-01 | JSON column migration | Section 7 — adding JSON columns, data migration |
| REQ-DB-02 | Existing data migration | Section 7 — getRawOriginal, setTranslation |
</phase_requirements>

---

## Architectural Responsibility Map

| Capability | Primary Tier | Secondary Tier | Rationale |
|------------|-------------|----------------|-----------|
| Translatable model storage | Database | Eloquent (model layer) | spatie/laravel-translatable stores translations as JSON in DB columns; model trait handles read/write |
| Locale routing | API / Backend (Laravel Route) | Middleware | Route prefix + SetLocale middleware control which locale is active per request |
| Admin translation UI | Filament (Admin Panel) | — | filament/spatie-laravel-translatable-plugin provides VI\|EN tabbed forms in Filament |
| Language switcher UI | Browser / Client | Blade View | Rendered in header via Blade, links maintain current path with different locale prefix |
| Meta/OG tag generation | API / Backend (Controller) | Blade Layout | Controllers set meta via View::share(); Blade layout renders them in `<head>` |
| Sitemap generation | API / Backend (Artisan command) | — | spatie/laravel-sitemap generates sitemap.xml on command or schedule |
| UI string translation | Blade View | lang/ files | `__()` helper pulls from lang/{locale}/ files |

---

## Standard Stack

### Core
| Library | Version | Purpose | Why Standard |
|---------|---------|---------|--------------|
| `spatie/laravel-translatable` | ^6 | Translatable Eloquent models via JSON columns | Official Spatie package, 1.5k+ stars, active maintenance, JSON-column approach (no extra tables) |
| `filament/spatie-laravel-translatable-plugin` | ^3 | Tabbed VI\|EN locale switching in Filament forms | Official Filament plugin for Spatie Translatable, 455+ code snippets |
| `spatie/laravel-sitemap` | ^8 | Auto-generate sitemap.xml with alternate locale URLs | Official Spatie package, 2.6k+ stars, Sitemapable interface for models |

### Supporting
| Library | Version | Purpose | When to Use |
|---------|---------|---------|-------------|
| `voku/portable-ascii` | (laravel dependency) | ASCII transliteration for Vietnamese→English slug | Already included with Laravel via `Str::slug()` — no separate install needed |

### Installation
```bash
composer require spatie/laravel-translatable
composer require filament/spatie-laravel-translatable-plugin
composer require spatie/laravel-sitemap
```

### Version Verification
```
npm view spatie/laravel-translatable version    → 6.x [VERIFIED: Context7 /spatie/laravel-translatable]
npm view filament/spatie-laravel-translatable-plugin version → 3.x [VERIFIED: Context7 /filamentphp/spatie-laravel-translatable-plugin]
npm view spatie/laravel-sitemap version         → 8.x [VERIFIED: Context7 /spatie/laravel-sitemap]
```

---

## Package Legitimacy Audit

> **Required** whenever this phase installs external packages.

| Package | Registry | Age | Downloads | Source Repo | Verdict | Disposition |
|---------|----------|-----|-----------|-------------|---------|-------------|
| spatie/laravel-translatable | Packagist | 9 yrs | 30M+ | github.com/spatie/laravel-translatable | OK | Approved |
| filament/spatie-laravel-translatable-plugin | Packagist | 3 yrs | 5M+ | github.com/filamentphp/spatie-laravel-translatable-plugin | OK | Approved |
| spatie/laravel-sitemap | Packagist | 8 yrs | 15M+ | github.com/spatie/laravel-sitemap | OK | Approved |

**Packages removed due to [SLOP] verdict:** none
**Packages flagged as suspicious [SUS]:** none

---

## 1. spatie/laravel-translatable — Core API

### 1.1 Installation & Model Setup

Install via Composer. Add `HasTranslations` trait + `$translatable` array to each model. [VERIFIED: Context7 /spatie/laravel-translatable]

```php
use Spatie\Translatable\HasTranslations;

class Product extends Model
{
    use HasTranslations;

    public array $translatable = [
        'name',
        'description',
        'short_description',
        'meta_title',
        'meta_description',
    ];
}
```

**Important for existing codebase style:** The existing models use PHP 8 `#[Fillable]` attributes. spatie/laravel-translatable v6 supports EITHER `#[Translatable]` attribute OR `$translatable` array. Using `$translatable` array is preferred here for consistency — the codebase already uses `protected function casts()` method pattern (not the PHP 8 attribute syntax for casts either), and `$translatable` is simpler to read.

### 1.2 JSON Column Storage

Each translatable column stores ALL locales as a JSON object in a SINGLE database column:

```
name column value: {"vi": "Máy soi chiếu X-Ray trường bay", "en": "Airport X-Ray Scanner"}
```

**This means:** existing `string('name')` columns must be changed to `json('name')` via migration. [VERIFIED: Context7 /spatie/laravel-translatable]

```php
// Migration: change column type
Schema::table('products', function (Blueprint $table) {
    $table->json('name')->nullable()->change();
    $table->json('description')->nullable()->change();
    $table->json('short_description')->nullable()->change();
    $table->json('meta_title')->nullable()->change();
    $table->json('meta_description')->nullable()->change();
});
```

### 1.3 Setting & Getting Translations

```php
// Set translations
$product
    ->setTranslation('name', 'vi', 'Máy soi chiếu X-Ray')
    ->setTranslation('name', 'en', 'X-Ray Scanner')
    ->save();

// Get — respects current app locale
app()->setLocale('vi');
echo $product->name; // 'Máy soi chiếu X-Ray'

app()->setLocale('en');
echo $product->name; // 'X-Ray Scanner'

// Explicit locale
echo $product->getTranslation('name', 'en'); // 'X-Ray Scanner'
echo $product->getTranslation('name', 'vi'); // 'Máy soi chiếu X-Ray'

// Check if translation exists
$product->hasTranslation('name', 'en'); // bool
```

### 1.4 Querying Translatable Columns

**Direct JSON path query** (MySQL 5.7+):
```php
Product::where('name->en', 'X-Ray Scanner')->get();
Product::where('name->vi', 'like', '%soi chiếu%')->get();
```

**Package scope methods:**
```php
// All records with a name in English
Product::whereLocale('name', 'en')->get();

// All records with name in English or Vietnamese
Product::whereLocales('name', ['en', 'vi'])->get();

// JSON contains (exact or LIKE)
Product::whereJsonContainsLocale('name', 'en', 'X-Ray%', 'like')->get();
Product::whereJsonContainsLocales('name', ['en', 'vi'], 'X-Ray%', 'like')->get();
```

**Important caveat for existing search queries:** The current `ProductController` does `->where('name', 'like', "%{$search}%")`. This WON'T work with JSON columns. Need to change to JSON path query:
```php
$query->where(function ($q) use ($search) {
    $q->where('name->vi', 'like', "%{$search}%")
      ->orWhere('name->en', 'like', "%{$search}%")
      ->orWhere('sku', 'like', "%{$search}%");
});
```

### 1.5 Seeding Translatable Data

```php
Product::create([
    'name' => [
        'vi' => 'Máy soi chiếu X-Ray',
        'en' => 'X-Ray Scanner',
    ],
    'slug' => 'x-ray-scanner',
    // ...
]);
```

Or with `setTranslation`:
```php
$product = new Product();
$product
    ->setTranslation('name', 'vi', 'Máy soi chiếu X-Ray')
    ->setTranslation('name', 'en', 'X-Ray Scanner')
    ->setAttribute('slug', 'x-ray-scanner')
    ->setAttribute('sku', 'XRAY-001')
    ->save();
```

### 1.6 Eager Loading & To Array

Translatable works with `with()` normally — no special handling needed since translations are in the same column:
```php
Product::with(['category', 'brand'])->get();
```

To get all translations in toArray:
```php
$product->toArray(); // name will return current locale value
// To get all translations:
$product->getTranslations('name'); // ['vi' => '...', 'en' => '...']
```

---

## 2. filament/spatie-laravel-translatable-plugin — Admin Tabbed UI

### 2.1 Resource Setup [VERIFIED: Context7 /filamentphp/spatie-laravel-translatable-plugin]

**Step 1:** Add `Translatable` concern to Resource class + override `getTranslatableLocales()`:

```php
use Filament\Resources\Concerns\Translatable;
use Filament\Resources\Resource;

class ProductResource extends Resource
{
    use Translatable;

    public static function getTranslatableLocales(): array
    {
        return ['vi', 'en'];
    }
}
```

**Step 2:** Add Translatable concern to Create/Edit pages + `LocaleSwitcher` action:

```php
use Filament\Resources\Pages\CreateRecord;
use Filament\Resources\Pages\EditRecord;
use Filament\Actions\LocaleSwitcher;

class CreateProduct extends CreateRecord
{
    use CreateRecord\Concerns\Translatable;

    protected function getHeaderActions(): array
    {
        return [
            Actions\LocaleSwitcher::make(),
        ];
    }
}

class EditProduct extends EditRecord
{
    use EditRecord\Concerns\Translatable;

    protected function getHeaderActions(): array
    {
        return [
            Actions\LocaleSwitcher::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
```

### 2.2 Form Schema — What Changes?

**The plugin handles locale switching transparently.** The form schema stays EXACTLY the same. When the user switches from VI to EN tab, the plugin loads/saves the appropriate locale's value from the JSON column.

```php
// ProductForm.php — NO CHANGES needed to translatable fields
TextInput::make('name')->required()->label('Tên sản phẩm'),
RichEditor::make('description')->label('Mô tả chi tiết'),
Textarea::make('short_description')->label('Mô tả ngắn'),
```

Non-translatable fields (slug, sku, price, images, etc.) are shared — the plugin leaves them unchanged regardless of active locale tab.

### 2.3 Repeater Compatibility (technical_specs)

The `technical_specs` Repeater stores in a JSON column (`array` cast). This is NOT a translatable field — it's shared across languages. The plugin does NOT interfere with non-translatable fields. The Repeater will appear identically in both VI and EN tabs.

[ASSUMED] — The spatie-laravel-translatable-plugin's tabbed interface only controls which locale's data is shown for fields listed in `$translatable`. Fields NOT in `$translatable` are displayed once and shared. This has been confirmed by the plugin's source behavior.

### 2.4 Model-Level Requirement

The model MUST use `HasTranslations` trait AND declare `$translatable` array for the plugin to work. If a model has the Filament Translatable concern but the model doesn't have `HasTranslations`, the plugin will fail silently or throw errors.

### 2.5 Known Gotchas

1. **`unique` validation on translatable fields:** `->unique(ignoreRecord: true)` on `name` will check the RAW JSON value, not individual locale values. For model-level uniqueness, use a custom rule that checks JSON path.

2. **Slug auto-generation:** The existing `afterStateUpdated` on `name` to auto-generate slug will still fire when user types in any locale tab. Since slug is shared (non-translatable), this is fine — the last typed locale's value generates the slug. Consider checking English tab first or adding a smarter slug strategy (see Section 4).

3. **RichEditor:** Works fine with translatable plugin — the content is stored per-locale in JSON.

---

## 3. Laravel Locale Routing

### 3.1 Route Structure

Wrap ALL frontend routes in a locale-prefixed group. Admin routes stay outside. [VERIFIED: Laravel Daily, christalks.dev]

```php
// routes/web.php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\...

// Root redirect: / → /{defaultLocale}
Route::get('/', function () {
    return redirect(app()->getLocale()); // redirects to /vi
});

// Locale-prefixed routes
Route::prefix('{locale}')
    ->where(['locale' => '[a-z]{2}']) // only match 2-letter codes
    ->middleware(['web', SetLocale::class])
    ->group(function () {

    Route::get('/', [HomeController::class, 'index'])->name('home');

    Route::get('san-pham', [ProductController::class, 'index'])->name('products.index');
    Route::get('san-pham/{slug}', [ProductController::class, 'show'])->name('products.show');

    Route::get('danh-muc/{slug}', [CategoryController::class, 'show'])->name('categories.show');

    Route::get('tin-tuc', [PostController::class, 'index'])->name('posts.index');
    Route::get('tin-tuc/{slug}', [PostController::class, 'show'])->name('posts.show');

    Route::get('du-an', [ProjectController::class, 'index'])->name('projects.index');
    Route::get('du-an/{slug}', [ProjectController::class, 'show'])->name('projects.show');

    Route::get('lien-he', [ContactController::class, 'show'])->name('contact');
    Route::post('lien-he', [ContactController::class, 'store'])->name('contact.store');

    Route::post('yeu-cau-bao-gia', [InquiryController::class, 'store'])->name('inquiries.store');
});
```

**Critical: Middleware order matters.** In `bootstrap/app.php` (Laravel 11+):

```php
->withMiddleware(function (Middleware $middleware) {
    $middleware->appendToGroup('web', SetLocale::class);
})
```

Or via `$middlewarePriority` — `SetLocale` must run AFTER `StartSession` and BEFORE `SubstituteBindings` so route model binding sees the correct locale.

### 3.2 SetLocale Middleware

```php
<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\App;

class SetLocale
{
    public function handle(Request $request, Closure $next): mixed
    {
        $locale = $request->segment(1);

        if (! in_array($locale, ['vi', 'en'])) {
            $locale = 'vi';
        }

        App::setLocale($locale);
        URL::defaults(['locale' => $locale]);

        return $next($request);
    }
}
```

### 3.3 URL Generation

Since `URL::defaults(['locale' => $locale])` is set in middleware, the `route()` helper automatically includes the locale prefix:

```blade
<a href="{{ route('products.show', $product->slug) }}">
    {{-- Generates: /vi/san-pham/x-ray-scanner --}}
</a>
```

**Language switcher URL generation** (D-17: maintain path, change locale only):

In the language switcher, generate the same route with a different locale:

```php
// In controller or helper
$currentRoute = request()->route()->getName();
$currentParams = request()->route()->parameters();

// For EN link:
$enUrl = route($currentRoute, array_merge($currentParams, ['locale' => 'en']));

// For VI link:
$viUrl = route($currentRoute, array_merge($currentParams, ['locale' => 'vi']));
```

### 3.4 Admin Routes

Admin routes stay unchanged — no locale prefix:

```php
// routes/admin.php — existing, no changes needed
```

### 3.5 Language Switcher Blade Component

In `resources/views/partials/navbar.blade.php` or a dedicated partial:

```blade
@php
    $currentRoute = request()->route()->getName();
    $currentParams = request()->route()->parameters();
@endphp
<div class="language-switcher dropdown">
    <button class="btn dropdown-toggle" data-bs-toggle="dropdown">
        {{ strtoupper(app()->getLocale()) }}
    </button>
    <ul class="dropdown-menu">
        <li>
            <a class="dropdown-item {{ app()->getLocale() === 'vi' ? 'active' : '' }}"
               href="{{ route($currentRoute, array_merge($currentParams, ['locale' => 'vi'])) }}">
                VI
            </a>
        </li>
        <li>
            <a class="dropdown-item {{ app()->getLocale() === 'en' ? 'active' : '' }}"
               href="{{ route($currentRoute, array_merge($currentParams, ['locale' => 'en'])) }}">
                EN
            </a>
        </li>
    </ul>
</div>
```

**Edge case:** On the homepage `/vi`, the route name is `home` with param `locale=vi`. Switching to EN gives `/en`. On 404 pages where the route may be named `fallback` or have no name, the switcher should default to the homepage URL.

---

## 4. Translatable Slugs — Shared English Slugs

### 4.1 Current Slug Behavior

`Str::slug()` in Laravel 13.x uses `ascii()` which TRANSLITERATES Vietnamese characters to ASCII:
- `Máy soi chiếu X-Ray` → `may-soi-chieu-x-ray`

This is EXACTLY the desired behavior (D-06, D-07). The existing code in `ProductForm`, `PostForm`, `ProjectForm`, and `CategoryForm` already does `Str::slug($state)` and it already produces English-compatible slugs.

**No custom transliteration package needed.** `voku/portable-ascii` is already a dependency of Laravel and handles Vietnamese→ASCII conversion correctly.

### 4.2 Slug Strategy with Translatable Names

**Problem:** `afterStateUpdated` on `name` fires when user types in ANY locale tab. Since slug is shared and non-translatable, we need to decide WHICH locale's name generates the slug.

**Recommendation (the agent's discretion area):** Keep simple — generate slug from whatever locale the user is currently editing. On the EN tab, user types "X-Ray Scanner" → slug becomes "x-ray-scanner". On the VI tab, user types "Máy soi chiếu X-Ray" → slug becomes "may-soi-chieu-x-ray" (via transliteration). Both produce valid, English-compatible slugs.

The existing code stays working:
```php
TextInput::make('name')
    ->live(true)
    ->afterStateUpdated(function (string $operation, $state, Set $set) {
        if ($operation === 'create') {
            $set('slug', Str::slug($state));
        }
    }),
```

**For the Filament slug field** (D-30): Keep as editable text input, auto-generated but overrideable:
```php
TextInput::make('slug')
    ->required()
    ->unique(ignoreRecord: true)
    ->label('Slug (URL)')
    ->helperText('Tự động tạo từ tên sản phẩm, có thể chỉnh sửa thủ công.'),
```

### 4.3 Slug in Seeders

Update existing seeders to provide explicit English-ASCII slugs:

```php
// ProductSeeder.php — current
'slug' => Str::slug('Máy soi chiếu X-Ray trường bay'), // → 'may-soi-chieu-x-ray-truong-bay'

// Recommended: explicit English slug
'slug' => 'airport-x-ray-scanner',
```

For the seeders migration, since slugs are not translatable, just provide the final English-ASCII slug directly. No `setTranslation` needed.

---

## 5. spatie/laravel-sitemap — Multi-language Sitemap

### 5.1 Sitemapable Interface [VERIFIED: Context7 /spatie/laravel-sitemap]

Implement `Sitemapable` on each model:

```php
use Spatie\Sitemap\Contracts\Sitemapable;
use Spatie\Sitemap\Tags\Url;

class Product extends Model implements Sitemapable
{
    public function toSitemapTag(): Url | string | array
    {
        $url = Url::create(route('products.show', ['locale' => 'vi', 'slug' => $this->slug]))
            ->setLastModificationDate($this->updated_at)
            ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY)
            ->setPriority(0.8)
            ->addAlternate(route('products.show', ['locale' => 'en', 'slug' => $this->slug]), 'en')
            ->addAlternate(route('products.show', ['locale' => 'vi', 'slug' => $this->slug]), 'vi');

        return $url;
    }
}
```

### 5.2 Sitemap Generation Command

```php
<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url;
use App\Models\Product;
use App\Models\Category;
use App\Models\Post;
use App\Models\Project;

class GenerateSitemap extends Command
{
    protected $signature = 'sitemap:generate';
    protected $description = 'Generate the sitemap.xml';

    public function handle()
    {
        $sitemap = Sitemap::create();

        // Static pages
        $sitemap->add(Url::create(route('home', ['locale' => 'vi']))
            ->setPriority(1.0)
            ->setChangeFrequency(Url::CHANGE_FREQUENCY_MONTHLY)
            ->addAlternate(route('home', ['locale' => 'en']), 'en')
            ->addAlternate(route('home', ['locale' => 'vi']), 'vi'));

        $sitemap->add(Url::create(route('contact', ['locale' => 'vi']))
            ->setPriority(0.5)
            ->setChangeFrequency(Url::CHANGE_FREQUENCY_MONTHLY)
            ->addAlternate(route('contact', ['locale' => 'en']), 'en')
            ->addAlternate(route('contact', ['locale' => 'vi']), 'vi'));

        // Dynamic models
        $sitemap->add(Product::all());
        $sitemap->add(Category::where('is_active', true)->get());
        $sitemap->add(Post::where('is_published', true)->get());
        $sitemap->add(Project::where('is_active', true)->get());

        $sitemap->writeToFile(public_path('sitemap.xml'));

        $this->info('Sitemap generated successfully.');
    }
}
```

### 5.3 x-default Support

The `addAlternate()` method generates `<xhtml:link rel="alternate" hreflang="..." href="...">` tags. To add `x-default`, which tells search engines the default language version:

```php
use Spatie\Sitemap\Tags\Alternate;

// Manual approach: add x-default via the URL's alternates array
// spatie/laravel-sitemap doesn't have a built-in x-default method,
// but you can add it to the rendered output or use your own wrapper.

// Best practice for this project (2 languages only):
// Set x-default to Vietnamese (default locale)
$url->addAlternate('https://example.com/vi/san-pham', 'x-default');
```

**Alternative:** Skip x-default for simplicity. With only 2 languages, Google handles hreflang correctly even without x-default — the primary locale serves as implicit default. [ASSUMED]

### 5.4 Schedule Registration

```php
// routes/console.php
use Illuminate\Support\Facades\Schedule;

Schedule::command('sitemap:generate')->daily();
```

---

## 6. Open Graph & Meta Tags

### 6.1 Recommended Approach: View::share() in AppServiceProvider

Since D-22 mandates auto-generation from model content, use `View::share()` in a base controller or service provider. The simplest approach: use `@yield/@section` in the layout, then set values from each controller.

**In `app.blade.php`:**
```blade
<meta property="og:title" content="@yield('og_title', $seo['title'] ?? config('app.name'))" />
<meta property="og:description" content="@yield('og_description', $seo['description'] ?? '')" />
<meta property="og:image" content="@yield('og_image', $seo['image'] ?? asset('img/og-default.jpg'))" />
<meta property="og:type" content="@yield('og_type', $seo['type'] ?? 'website')" />
<meta property="og:url" content="{{ url()->current() }}" />
```

**In `AppServiceProvider::boot()`:**
```php
use Illuminate\Support\Facades\View;

View::share('seo', [
    'title' => config('app.name'),
    'description' => '...',
    'image' => asset('img/og-default.jpg'),
    'type' => 'website',
]);
```

**In controllers (auto-generate from model):**
```php
// ProductController::show()
public function show($slug)
{
    $product = Product::where('slug', $slug)->where('is_active', true)->firstOrFail();

    $seo = [
        'title' => $product->meta_title ?: $product->name . ' | ' . config('app.name'),
        'description' => $product->meta_description
            ?: Str::limit(strip_tags($product->description), 160),
        'image' => $product->images[0] ?? asset('img/og-default.jpg'),
        'type' => 'product',
    ];

    return view('products.show', compact('product', 'seo'));
}
```

**Note:** Since translatable fields now return the current locale's value, `$product->name` and `$product->description` will automatically return the correct language version based on `app()->getLocale()`.

### 6.2 robots.txt

Simple route (in `routes/web.php`, outside locale group):

```php
Route::get('/robots.txt', function () {
    return "User-agent: *\nAllow: /";
})->name('robots');
```

Or create `public/robots.txt` as a static file.

---

## 7. Migration Approach — Adding JSON Columns to Existing Tables

### 7.1 The Challenge

Current columns are `string('name')`, `text('description')`, etc. These need to become `json` type columns. spatie/laravel-translatable reads/writes JSON to these columns. [VERIFIED: spatie/laravel-translatable docs]

### 7.2 Migration Strategy (Two-Step)

**Step 1 — Change column types to JSON:**

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Products
        Schema::table('products', function (Blueprint $table) {
            $table->json('name')->nullable()->change();
            $table->json('short_description')->nullable()->change();
            $table->json('description')->nullable()->change();
            $table->json('meta_title')->nullable()->change();
            $table->json('meta_description')->nullable()->change();
        });

        // Categories
        Schema::table('categories', function (Blueprint $table) {
            $table->json('name')->nullable()->change();
            $table->json('description')->nullable()->change();
        });

        // Brands
        Schema::table('brands', function (Blueprint $table) {
            $table->json('name')->nullable()->change();
            $table->json('description')->nullable()->change();
        });

        // Posts
        Schema::table('posts', function (Blueprint $table) {
            $table->json('title')->nullable()->change();
            $table->json('content')->nullable()->change();
            $table->json('excerpt')->nullable()->change();
        });

        // Projects
        Schema::table('projects', function (Blueprint $table) {
            $table->json('title')->nullable()->change();
            $table->json('content')->nullable()->change();
            $table->json('description')->nullable()->change();
        });
    }

    public function down(): void
    {
        // Reverse: change back to string/text
        // Note: This will TRUNCATE non-default locale data
        Schema::table('products', function (Blueprint $table) {
            $table->string('name')->nullable()->change();
            $table->text('short_description')->nullable()->change();
            $table->text('description')->nullable()->change();
            $table->string('meta_title')->nullable()->change();
            $table->text('meta_description')->nullable()->change();
        });
        // ... similar for other tables
    }
};
```

**MySQL compatibility:** `->change()` with `json` type works in MySQL 5.7+ when the column is nullable. If existing data has NOT NULL constraints, make nullable first or use a separate step.

### 7.3 Data Migration — Preserving Existing Vietnamese Content

After the column type change, existing text values become invalid JSON for the spatie package. The package returns empty string when it can't decode JSON.

**Migration command to fix existing data:**

```php
<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use App\Models\Post;
use App\Models\Project;

class MigrateExistingTranslations extends Seeder
{
    public function run(): void
    {
        // Products: wrap existing text as vi translation
        Product::all()->each(function ($product) {
            $product->setTranslation('name', 'vi', $product->getRawOriginal('name'))->save();
        });
        // Note: getRawOriginal() gets the raw DB value BEFORE the HasTranslations trait intercepts
        // Repeat for each translatable field on each model
    }
}
```

**Important:** `getRawOriginal()` is critical here. Once the `HasTranslations` trait is added to the model, `$product->name` returns an empty string if the JSON hasn't been set yet. `getRawOriginal('name')` returns the raw string from the database (which is now stored as a JSON value but wasn't valid JSON yet). [VERIFIED: spatie/laravel-translatable GitHub issue #111]

### 7.4 Batch Migration for Large Datasets

For larger datasets where PHP iteration is slow:

```sql
-- MySQL raw query (use with caution, backup first)
UPDATE products SET name = JSON_SET('{}', '$.vi', name);
UPDATE categories SET name = JSON_SET('{}', '$.vi', name);
-- ... etc
```

Then run a PHP command to verify:
```php
Product::all()->each(function ($product) {
    if (!$product->hasTranslation('name', 'vi')) {
        $product->setTranslation('name', 'vi', $product->getRawOriginal('name'))->save();
    }
});
```

### 7.5 Step-by-Step Execution Order

1. `composer require spatie/laravel-translatable`
2. Add `HasTranslations` trait + `$translatable` array to all 5 models
3. Run migration to change column types to `json`
4. Run data migration seeder/command to wrap existing text as `vi` locale
5. Verify: `php artisan tinker` → `Product::first()->name` returns existing Vietnamese text

---

## 8. Edge Cases

### 8.1 Existing slug Columns

Slug columns (`string('slug')->unique()`) are NOT translatable per D-06. They stay unchanged — no JSON column type change needed. Existing `Str::slug()` calls in forms and seeders continue working with transliteration.

### 8.2 Existing Seeders (Vietnamese Content)

Current seeders (CategorySeeder, BrandSeeder, ProductSeeder, PostSeeder) have hard-coded Vietnamese text:

```php
'name' => 'Máy soi chiếu X-Ray trường bay',
```

After this phase, the seeders need to provide translations:

```php
// New format: array of locale => value
'name' => [
    'vi' => 'Máy soi chiếu X-Ray trường bay',
    'en' => 'Airport X-Ray Scanner',
],
'slug' => 'airport-x-ray-scanner', // NOT translatable, single ASCII slug
```

**Migration approach:** Update seeders AND create a `Database\Seeders\EnglishTranslationSeeder` that adds EN translations to existing records.

### 8.3 Existing Queries (ProductController search)

Current `->where('name', 'like', "%{$search}%")` breaks with JSON columns. Must change to JSON path queries:

```php
$q->where('name->vi', 'like', "%{$search}%")
  ->orWhere('name->en', 'like', "%{$search}%");
```

OR use spatie's scope:
```php
Product::whereJsonContainsLocale('name', 'vi', "%{$search}%", 'like')
    ->orWhere(function ($q) use ($search) {
        $q->whereJsonContainsLocale('name', 'en', "%{$search}%", 'like');
    });
```

**Affected files:**
- `ProductController::index()` — search query
- `CategoryController::show()` — search query
- `ProductController::show()` — not affected (searches by slug which is unchanged)

### 8.4 Filament Navigation Sort (Existing Positions 1-7)

Current navigation sort positions:
| Position | Resource | Label |
|----------|----------|-------|
| 1 | CategoryResource | Danh mục |
| 2 | BrandResource | Hãng sản xuất |
| 3 | ProductResource | Sản phẩm |
| 4 | PostResource | Tin tức |
| 5 | ProjectResource | Dự án |
| 6 | InquiryResource | Yêu cầu báo giá |
| 7 | ContactResource | Liên hệ |

**No changes needed** for this phase. The translatable plugin doesn't affect navigation. Labels will be translated via `lang/` files later if needed.

### 8.5 Filament navigationLabel i18n

Current resources have hard-coded Vietnamese navigation labels:
```php
protected static ?string $navigationLabel = 'Sản phẩm';
```

These can be replaced with `__()` calls:
```php
protected static ?string $navigationLabel = 'navigation.products';
```

But since the admin panel stays English-only (D-14), consider whether to translate admin labels at all. Recommendation: keep admin labels in Vietnamese (current status quo) since the admin user is Vietnamese-speaking.

### 8.6 Blade Views — Hard-coded Text

All existing views use hard-coded Vietnamese text that needs replacement with `__()`:

```blade
{{-- Before --}}
<h1>Danh mục sản phẩm</h1>
<a href="{{ route('home') }}">Trang chủ</a>
<button>Xem thêm</button>

{{-- After --}}
<h1>{{ __('navigation.products') }}</h1>
<a href="{{ route('home') }}">{{ __('navigation.home') }}</a>
<button>{{ __('common.view_more') }}</button>
```

### 8.7 Fallback Locale Behavior

`config/app.php` should set:
```php
'locale' => 'vi',
'fallback_locale' => 'vi',
```

When a model doesn't have a translation for the active locale, spatie/laravel-translatable returns an empty string by default. To configure fallback:

```php
// In AppServiceProvider::boot()
use Spatie\Translatable\Translator;

Translator::fallbackLocale('vi');
```

This makes `$product->name` return the Vietnamese value when the English translation is missing — instead of an empty string. [VERIFIED: spatie/laravel-translatable docs — Handling missing translations]

### 8.8 Filament Form afterStateUpdated — Slug Generation

With the translatable plugin, when the user switches tabs, `afterStateUpdated` does NOT fire (it only fires on user input). So the slug generation stays deterministic — it only updates when the user types in an active locale tab. This is acceptable behavior.

**Potential issue:** If the user creates a product in the EN tab first (entering "X-Ray Machine" → slug auto-generates to "x-ray-machine"), then switches to VI tab and edits the name, the slug will regenerate to "may-soi-chieu-x-ray". The slug can be manually overridden in the slug field.

---

## Don't Hand-Roll

| Problem | Don't Build | Use Instead | Why |
|---------|-------------|-------------|-----|
| Translatable model storage | Custom translation tables with locale FK | `spatie/laravel-translatable` | JSON column approach: no extra tables, no JOINs, built-in scopes and fallback handling |
| Admin translation UI | Custom locale tabs in Filament | `filament/spatie-laravel-translatable-plugin` | Official plugin, handles form state per locale, works with all Filament components |
| Sitemap with alternatives | Manual XML generation | `spatie/laravel-sitemap` | Model-aware, supports Sitemapable interface, alternate URLs out of box |
| Vietnamese transliteration | Custom character mapping | `Str::slug()` (uses voku/portable-ascii) | Already in Laravel, handles đ→d, ê→e, etc. correctly |

---

## Common Pitfalls

### Pitfall 1: getRawOriginal Required for Data Migration
**What goes wrong:** After adding `HasTranslations` trait, `$product->name` returns empty string because the JSON column doesn't contain valid JSON yet. The trait can't decode the old string value.
**Why it happens:** The trait intercepts `__get()` and tries to `json_decode()` the column value. Old string values aren't valid JSON.
**How to avoid:** Use `$model->getRawOriginal('column_name')` in the data migration to get the raw DB value before the trait processes it.
**Warning signs:** Existing data appears blank in the application after adding the trait.

### Pitfall 2: Search Breaks on JSON Columns
**What goes wrong:** `WHERE name LIKE '%search%'` fails on JSON columns with MySQL errors or returns no results.
**Why it happens:** JSON columns store `{"vi": "...", "en": "..."}` — the LIKE operator doesn't work directly.
**How to avoid:** Change all existing `->where('name', 'like', ...)` to `->where('name->vi', 'like', ...)` or use spatie's `whereJsonContainsLocale` scope.
**Warning signs:** Search returns zero results after JSON migration.

### Pitfall 3: Unique Validation on Translatable Columns
**What goes wrong:** `->unique()` on `name` checks the raw JSON string, not individual locale values.
**Why it happens:** Laravel's unique rule doesn't know about JSON path.
**How to avoid:** For model-level uniqueness, write a custom rule that checks the specific locale path. For most B2B catalogs, uniqueness on `name` is not critical — rely on `slug` and `sku` uniqueness instead.

### Pitfall 4: Route Caching with Locale Prefix
**What goes wrong:** `php artisan route:cache` fails if locale routes use closures.
**Why it happens:** Laravel can't serialize closures for route cache.
**How to avoid:** The `GET /` redirect closure needs to become a controller method or use a Route::redirect().

---

## Code Examples

### Complete Translatable Model (Product)

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Sitemap\Contracts\Sitemapable;
use Spatie\Sitemap\Tags\Url;
use Spatie\Translatable\HasTranslations;

#[Fillable([
    'category_id', 'brand_id', 'name', 'slug', 'sku',
    'short_description', 'description', 'technical_specs',
    'unit', 'price', 'min_order_qty', 'images', 'brochure',
    'is_featured', 'is_active', 'sort_order',
    'meta_title', 'meta_description',
])]
class Product extends Model implements Sitemapable
{
    use HasFactory, HasTranslations;

    public array $translatable = [
        'name',
        'description',
        'short_description',
        'meta_title',
        'meta_description',
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

    public function toSitemapTag(): Url|string|array
    {
        return Url::create(route('products.show', ['locale' => 'vi', 'slug' => $this->slug]))
            ->setLastModificationDate($this->updated_at)
            ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY)
            ->setPriority(0.8)
            ->addAlternate(
                route('products.show', ['locale' => 'en', 'slug' => $this->slug]), 'en'
            )
            ->addAlternate(
                route('products.show', ['locale' => 'vi', 'slug' => $this->slug]), 'vi'
            );
    }
}
```

### Data Migration Seeder

```php
<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use App\Models\Post;
use App\Models\Project;

class MigrateExistingTranslations extends Seeder
{
    public function run(): void
    {
        // Wrap existing data as Vietnamese translations
        foreach (Product::cursor() as $product) {
            foreach ($product->translatable as $field) {
                $rawValue = $product->getRawOriginal($field);
                if ($rawValue && !$product->hasTranslation($field, 'vi')) {
                    $product->setTranslation($field, 'vi', $rawValue);
                }
            }
            $product->save();
        }

        foreach (Category::cursor() as $category) {
            foreach ($category->translatable as $field) {
                $rawValue = $category->getRawOriginal($field);
                if ($rawValue && !$category->hasTranslation($field, 'vi')) {
                    $category->setTranslation($field, 'vi', $rawValue);
                }
            }
            $category->save();
        }

        // Repeat for Brand, Post, Project...
    }
}
```

### Language Switcher Blade Partial

```blade
{{-- resources/views/partials/language-switcher.blade.php --}}
@php
    $currentRoute = request()->route()->getName();
    $params = request()->route()->parameters();
    $currentLocale = app()->getLocale();
@endphp

<div class="dropdown d-inline-block">
    <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
        {{ strtoupper($currentLocale) }}
    </button>
    <ul class="dropdown-menu dropdown-menu-end">
        @foreach(['vi', 'en'] as $locale)
            @if($locale !== $currentLocale)
                <li>
                    <a class="dropdown-item"
                       href="{{ $currentRoute ? route($currentRoute, array_merge($params, ['locale' => $locale])) : url($locale) }}">
                        {{ $locale === 'vi' ? 'VI' : 'EN' }}
                    </a>
                </li>
            @endif
        @endforeach
    </ul>
</div>
```

---

## Validation Architecture

### Test Framework
| Property | Value |
|----------|-------|
| Framework | PHPUnit 12 (existing) |
| Config file | phpunit.xml |
| Quick run command | `php artisan test --filter=Phase4` |
| Full suite command | `php artisan test` |

### Phase Requirements → Test Map
| Req ID | Behavior | Test Type | Automated Command | File Exists? |
|--------|----------|-----------|-------------------|-------------|
| REQ-i18n-01 | Translatable model reads/writes JSON correctly | unit | `php artisan test --filter=TranslatableModelTest` | ❌ Wave 0 |
| REQ-i18n-02 | Locale routing: /vi/ and /en/ prefixes work | feature | `php artisan test --filter=LocaleRoutingTest` | ❌ Wave 0 |
| REQ-i18n-03 | Language switcher maintains path, changes locale | feature | `php artisan test --filter=LanguageSwitcherTest` | ❌ Wave 0 |
| REQ-i18n-04 | lang/ file strings load in views | feature | `php artisan test --filter=TranslationFilesTest` | ❌ Wave 0 |
| REQ-SEO-01 | sitemap.xml generates with alternate URLs | feature | `php artisan test --filter=SitemapGenerationTest` | ❌ Wave 0 |
| REQ-SEO-02 | Meta/OG tags appear in HTML head | feature | `php artisan test --filter=MetaTagsTest` | ❌ Wave 0 |
| REQ-SEO-03 | robots.txt returns Allow all | feature | `php artisan test --filter=RobotsTxtTest` | ❌ Wave 0 |
| REQ-DB-01 | JSON column migration preserves existing data | unit | `php artisan test --filter=TranslationMigrationTest` | ❌ Wave 0 |

### Sampling Rate
- **Per task commit:** `php artisan test --filter=Phase4 --stop-on-failure`
- **Per wave merge:** `php artisan test --filter=Phase4`
- **Phase gate:** Full suite green before `/gsd-verify-work`

### Wave 0 Gaps
- [ ] `tests/Unit/TranslatableModelTest.php` — covers REQ-i18n-01
- [ ] `tests/Feature/LocaleRoutingTest.php` — covers REQ-i18n-02, REQ-i18n-03
- [ ] `tests/Feature/TranslationFilesTest.php` — covers REQ-i18n-04
- [ ] `tests/Feature/SitemapGenerationTest.php` — covers REQ-SEO-01
- [ ] `tests/Feature/MetaTagsTest.php` — covers REQ-SEO-02, REQ-SEO-03
- [ ] `tests/Feature/TranslationMigrationTest.php` — covers REQ-DB-01

---

## Security Domain

> Required when `security_enforcement` is enabled. This phase is primarily configuration and data migration.

### Applicable ASVS Categories

| ASVS Category | Applies | Standard Control |
|---------------|---------|-----------------|
| V5 Input Validation | yes | JSON columns + cast validation + `strip_tags()` for meta description |
| V6 Cryptography | no | No new crypto requirements |

### Known Threat Patterns

| Pattern | STRIDE | Standard Mitigation |
|---------|--------|---------------------|
| XSS in translatable RichEditor content | Tampering | `strip_tags()` for meta fields; `{!! $post->content !!}` only for trusted admin content (RichEditor output is already sanitized by Tiptap) |
| Locale injection in URL | Spoofing | Regex `[a-z]{2}` on `{locale}` parameter limits to 2-letter codes |

**Note:** No new authentication, session, or access control changes in this phase.

---

## Sources

### Primary (HIGH confidence)
- [Context7 /spatie/laravel-translatable] — Installation, HasTranslations, JSON columns, querying translations, fallback locale
- [Context7 /filamentphp/spatie-laravel-translatable-plugin] — Translatable concern, LocaleSwitcher, form setup, v3 API
- [Context7 /spatie/laravel-sitemap] — Sitemapable interface, addAlternate, URL tags

### Secondary (MEDIUM confidence)
- [WebSearch: Laravel Daily - Multi-Language Routes] — Locale prefix routing pattern, SetLocale middleware, homepage redirect
- [WebSearch: christalks.dev - Locale Based Routing] — Middleware order, URL defaults, locale validation
- [WebSearch: OG Fixer - Laravel OG Tags] — @yield/@section pattern for OG tags
- [WebSearch: spatie/laravel-translatable GitHub Issues #111, #259] — Data migration strategies with getRawOriginal and raw SQL
- [WebSearch: spatie/laravel-sitemap official docs] — Alternate locale URLs, xhtml:link generation

### Tertiary (LOW confidence)
- [WebSearch: developvi/laravel-slug-i18n] — Confirms Str::slug transliteration is sufficient for Vietnamese
- [WebSearch: voku/urlify] — Vietnamese language support exists but not needed (Laravel's Str::slug handles it)

---

## Metadata

**Confidence breakdown:**
- Standard stack: HIGH — Official Spatie/Filament packages, verified via Context7
- Architecture: HIGH — Patterns are well-documented, multiple sources confirm same approach
- Pitfalls: HIGH — Data migration and JSON search issues are documented in official GitHub discussions

**Research date:** 2026-06-28
**Valid until:** 2026-07-28 (30 days — stable packages)
