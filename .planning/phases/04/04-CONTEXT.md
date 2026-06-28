# Phase 4: Đa ngôn ngữ & SEO (Multi-language & SEO) - Context

**Gathered:** 2026-06-28
**Status:** Ready for planning

<domain>
## Phase Boundary

Đa ngôn ngữ (tiếng Việt + tiếng Anh) và tối ưu SEO cơ bản.

**In scope:**
- **i18n:** spatie/laravel-translatable cho translatable models
- **Translatable models:** Product, Category, Brand, Post, Project
- **Translatable fields:** name, description, short_description, title, content, excerpt
- **Slug:** Dùng **slug chung, bằng tiếng Anh** (không phải tiếng Việt) — xuyên suốt các ngôn ngữ
- **URL structure:** Prefix-based (`/vi/...`, `/en/...`)
- **Language switcher UI:** Header — dropdown/links chuyển đổi giữa `vi` và `en`
- **UI strings:** `lang/` files — tiếng Việt là default locale
- **Admin (Filament):** Tabbed interface VI | EN cho translatable fields
- **SEO meta tags:** Auto-generated từ nội dung (meta title = name, meta description = excerpt/description)
- **Sitemap.xml:** Tự động — Products, Categories, Posts, Projects, Static pages
- **robots.txt:** Allow all
- **Open Graph tags cơ bản:** og:title, og:description, og:image, og:type

**Out of scope (phases sau):**
- Manual SEO meta per model (auto-generated là đủ cho MVP)
- Schema.org JSON-LD chi tiết (nếu cần thì để Phase 5)
- hreflang tags (chỉ 2 ngôn ngữ, không cần thiết)
- Subdomain-based i18n (prefix là đủ)
- Language negotiation (Accept-Language header) — đơn giản, dùng URL prefix là chính

</domain>

<decisions>
## Implementation Decisions

### i18n Architecture
- **D-01:** Dùng `spatie/laravel-translatable` package — quản lý translations qua relationship, không cần tạo translation tables thủ công
- **D-02:** Tiếng Việt là locale mặc định (`app.locale = vi`), tiếng Anh là locale phụ
- **D-03:** URL prefix-based routing — `Route::prefix('{locale?}')` — locale optional, mặc định redirect `/` → `/vi`
- **D-04:** Middleware `SetLocale` — đọc locale từ URL prefix, set `app()->setLocale()`, fallback về `vi`

### Translatable Models & Fields
- **D-05:** Translatable models:
  - `Category`: `name`, `description`, `meta_title`, `meta_description`
  - `Brand`: `name`, `description`, `meta_title`, `meta_description`
  - `Product`: `name`, `description`, `short_description`, `meta_title`, `meta_description`
  - `Post`: `title`, `content`, `excerpt`, `meta_title`, `meta_description`
  - `Project`: `title`, `content`, `description`, `meta_title`, `meta_description`
- **D-06:** Slug là **shared/common** — không translatable — sử dụng ký tự tiếng Anh (không dấu tiếng Việt). Ví dụ: `x-ray-machine` thay vì `máy-x-quang`
- **D-07:** Slug generation: tự động từ `name`/`title` tiếng Anh nếu có, fallback từ tiếng Việt đã transliterate (đ→d, ư→u, v.v.)

### UI Strings
- **D-08:** Dùng Laravel `lang/` files — tạo `lang/vi/` (default) và `lang/en/`
- **D-09:** Các UI string keys: navigation, common labels, buttons, placeholders, validation messages, page titles
- **D-10:** `__()` helper trong Blade views — thay thế text hard-coded

### URL Structure & Routing
- **D-11:** Route pattern: `/{locale?}/{slug}` — locale optional, mặc định là `vi`
- **D-12:** Web routes — wrap trong `Route::prefix('{locale?}')` → filter `LocaleMiddleware`
- **D-13:** Route `GET /` redirect → `GET /vi` (và `GET /en` tương ứng)
- **D-14:** Admin routes (`/admin`) không cần locale prefix — Filament mặc định English

### Language Switcher
- **D-15:** Đặt trong header, cạnh navigation links — dạng dropdown với 2 item: VI / EN
- **D-16:** Icon: chữ VI/EN (không cần cờ quốc gia) — giữ gọn gàng
- **D-17:** Link giữ nguyên path, chỉ đổi locale prefix — ví dụ: `/vi/san-pham` → `/en/san-pham`

### Filament Translation UX
- **D-18:** Dùng `filament/spatie-laravel-translatable-plugin` — tabbed interface VI | EN trên mỗi form
- **D-19:** Tất cả Filament resources có translatable fields: CategoryResource, BrandResource, ProductResource, PostResource, ProjectResource
- **D-20:** Non-translatable fields (SKU, price, images, is_published, v.v.) giữ nguyên — không nằm trong tabs

### SEO
- **D-21:** Meta tags auto-generated từ model content — meta title = model name/title, meta description = excerpt/description (truncated 160 ký tự)
- **D-22:** Implement trong `AppServiceProvider` hoặc base controller `share()` — tự động set cho mỗi trang
- **D-23:** Open Graph tags: `og:title`, `og:description`, `og:image` (nếu có), `og:type` (`website`/`article`/`product`)
- **D-24:** robots.txt: Allow all (không có nội dung cần ẩn)
- **D-25:** Sitemap: dùng `spatie/laravel-sitemap` package — generate theo schedule hoặc manual command

### Sitemap Configuration
- **D-26:** Models in sitemap:
  - `Product` (active) — priority: 0.8, changefreq: weekly
  - `Category` — priority: 0.7, changefreq: weekly
  - `Post` (published) — priority: 0.6, changefreq: monthly
  - `Project` (active) — priority: 0.6, changefreq: monthly
  - Static pages (home, about, contact) — priority: 1.0 (home) / 0.5, changefreq: monthly
- **D-27:** Alternate URLs cho mỗi locale trong sitemap (x-default)

### Slug Management
- **D-28:** Slug là column `slug` trên mỗi model — duy nhất (unique), không translatable
- **D-29:** Slug tự động generate khi tạo/cập nhật model — dùng `Str::slug()` + custom transliteration từ tiếng Việt → không dấu
- **D-30:** Filament forms: slug field hiển thị dạng text input (auto-generated nhưng có thể override)

### Language Files
- **D-31:** Tạo `lang/vi/` và `lang/en/`:
  - `navigation.php` — nav links (trang chủ, sản phẩm, tin tức, dự án, liên hệ)
  - `common.php` — buttons, labels (xem thêm, đọc tiếp, gửi, tải về)
  - `products.php` — product-specific (thông số kỹ thuật, sản phẩm liên quan, yêu cầu báo giá)
  - `validation.php` — override validation messages (mặc định tiếng Anh)
  - `pagination.php` — phân trang
  - `seo.php` — site-wide meta defaults (site name, tagline)

### Agent's Discretion
- Tên cụ thể của locale trong switcher ("VI", "EN" vs "Tiếng Việt", "English")
- Schedule cho sitemap generation (once daily via cron suggestion)
- CSS styling chi tiết cho language switcher (bootstrap dropdown phù hợp theme)
- Logic transliteration chi tiết (có thể dùng `@digilabs/laravel-utf8` hoặc tự xử lý)
- Exact middleware implementation (extend `Middleware` class, handle route params)
- Testing strategy: PHPUnit tests cho locale switching, sitemap generation, translatable models

</decisions>

<specifics>
## Specific Ideas

- Product slug hiện tại đã có `Str::slug($title)` — cần thay đổi logic slug thành tiếng Anh (transliterate)
- Route hiện tại: `/danh-muc/{slug}`, `/san-pham/{slug}` — cần thêm locale prefix: `/{locale}/danh-muc/{slug}`
- File `resources/views/layouts/app.blade.php` — thêm language switcher vào header
- Blade directives: `@lang('navigation.products')` thay cho text hardcoded
- Sử dụng `app()->getLocale()` trong controllers để lấy nội dung đúng ngôn ngữ
- spatie/laravel-translatable dùng JSON columns để lưu translations — không cần migration mới, chỉ cần thêm cột JSON `translations` trên các model
- `filament/spatie-laravel-translatable-plugin` cung cấp `Translatable` trait + `LocaleSwitcher` component cho tabbed interface

</specifics>

<canonical_refs>
## Canonical References

**Downstream agents MUST read these before planning or implementing.**

### Phase Definition
- `.planning/ROADMAP.md` — Phase 4 deliverables, dependencies, verification criteria
- `.planning/STATE.md` — Current phase state
- `.planning/PROJECT.md` — Project identity, requirements, constraints

### Prior Context
- `.planning/phases/03/03-CONTEXT.md` — Phase 3 decisions (Post, Project, Inquiry, Contact)
- `.planning/phases/02-qu-n-l-s-n-ph-m-product-management/02-CONTEXT.md` — Phase 2 decisions (Product, Category, Brand patterns)
- `.planning/PROJECT.md` — Key Decisions (Tiếng Việt trước, i18n architecture ready from start)

### Existing Models (cần update thêm Translatable trait)
- `app/Models/Category.php` — Thêm `HasTranslations` trait + `$translatable` array
- `app/Models/Brand.php` — Thêm `HasTranslations` trait + `$translatable` array
- `app/Models/Product.php` — Thêm `HasTranslations` trait + `$translatable` array
- `app/Models/Post.php` — Thêm `HasTranslations` trait + `$translatable` array
- `app/Models/Project.php` — Thêm `HasTranslations` trait + `$translatable` array

### Existing Filament Resources (cần update thêm LocaleSwitcher + tabbed forms)
- `app/Filament/Resources/CategoryResource/` — Existing Schemas/Forms/Tables
- `app/Filament/Resources/BrandResource/` — Existing Schemas/Forms/Tables
- `app/Filament/Resources/ProductResource/` — Existing Schemas/Forms/Tables

### New Resources (cần thêm translatable support)
- `app/Filament/Resources/PostResource/` — From Phase 3
- `app/Filament/Resources/ProjectResource/` — From Phase 3

### Route Files
- `routes/web.php` — Cần restructure với locale prefix
- `routes/admin.php` — Không cần locale prefix

### Design Reference
- `template-frontend/` — Base UI (cần wrap text với `@lang()`)
- `resources/views/layouts/app.blade.php` — Base layout (thêm language switcher)
- `resources/views/products/` — Product views (cần i18n)
- `resources/views/categories/` — Category views (cần i18n)
- `resources/views/posts/` — Post views (từ Phase 3, cần i18n)
- `resources/views/projects/` — Project views (từ Phase 3, cần i18n)

### Codebase Maps
- `.planning/codebase/ARCHITECTURE.md`
- `.planning/codebase/CONVENTIONS.md`
- `.planning/codebase/STACK.md`
- `.planning/codebase/STRUCTURE.md`

</canonical_refs>

<code_context>
## Existing Code Insights

### Current Slug Logic
- Product uses `Str::slug($title)` trong migration/observer — cần transliterate từ tiếng Việt → English
- Category và Brand cũng có slug generation tương tự

### Current Routes
- `web.php` dùng prefix `/danh-muc`, `/san-pham`, `/tin-tuc`, `/du-an` — cần locale wrapper
- Static routes: `/`, `/lien-he` — cần locale prefix

### Current Views
- Text hard-coded tiếng Việt trong Blade views — cần wrap với `@lang()` hoặc `__()`
- Navbar items, page titles, buttons, labels — tất cả cần i18n

### Current Layout
- Header in `app.blade.php` — thêm language switcher bên cạnh navigation
- Footer text cũng cần i18n

### Existing Model Structure
- Các models không có `HasTranslations` trait — cần thêm
- Chưa có JSON `translations` column — cần migration thêm column

### Integration Points
- Filament resources cần LocaleSwitcher + tabbed forms
- Routes cần locale middleware
- Controllers cần `app()->getLocale()` awareness
- Views cần `__()` helpers
- Sitemap generation command cần tạo

</code_context>

<deferred>
## Deferred Ideas

- **Hreflang tags** — Khi có nhiều hơn 2 ngôn ngữ, thêm `<link rel="alternate" hreflang="...">`
- **Schema.org structured data** — Phase 5 sẽ thêm Product/Organization schema nếu cần
- **Manual SEO meta per page** — Nếu sau này cần override auto-generated meta
- **Language detection (browser)** — Accept-Language header detection — không cần cho MVP
- **RTL support** — Không áp dụng (tiếng Việt và Anh đều LTR)
- **Static page translation** — About/Contact content — Phase 4 chỉ tập trung vào model content + UI strings

</deferred>

---

*Phase: 04-a-ng-ng-seo*
*Context gathered: 2026-06-28*
