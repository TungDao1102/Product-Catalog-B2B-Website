# Phase 5 Plan — Hoàn thiện & Triển khai

**Goal:** Hoàn thiện website, tối ưu performance, chuẩn bị shared hosting.

## Sub-Plans

### 05-01: Trang About động (Wave 1)
**Objective:** Tạo About model + migration + Filament resource + frontend controller/view
- Create `About` model with translatable fields (content, mission, vision, values)
- Create migration for `about` table (single-row pattern)
- Create `AboutResource` Filament resource (single-record, edit-only)
- Create `AboutController` + route `/{locale}/ve-chung-toi` + Blade view
- Add lang strings for About page
- Tests: AboutResourceTest + AboutFrontendTest

### 05-02: Error pages + polish (Wave 1)
**Objective:** Custom error pages (403, 404, 419, 500) matching site design
- Polish existing `404.blade.php` with i18n `__()` calls
- Create `403.blade.php`, `419.blade.php`, `500.blade.php` matching 404 layout
- Add lang strings for error pages

### 05-03: Image optimization (Wave 2)
**Objective:** Lazy loading + auto-resize on upload
- Add `loading="lazy"` to all `<img>` tags across all Blade views
- Ensure Intervention resize runs on all image uploads (Post, Project, Brand logos)

### 05-04: Caching layer (Wave 2)
**Objective:** Query caching + response caching for shared hosting
- Add `->remember()` or cache helper to slow queries (homepage, products.index, categories.show)
- Add cache headers middleware for static assets
- Document cache clear command for admin

### 05-05: Shared hosting optimization (Wave 3)
**Objective:** Tối ưu cho shared hosting environment
- Update `.htaccess` with shared hosting rules (www redirect, HTTPS, browser caching)
- Add `OPcache` config recommendations
- Queue config for shared hosting (database driver)

### 05-06: Deployment + Admin docs (Wave 3)
**Objective:** Documentation for deployment and admin
- Create `DEPLOY.md` — deployment checklist (DB migration, env, symlink, etc.)
- Create `ADMIN.md` — hướng dẫn quản lý nội dung bằng tiếng Việt

## Waves

| Wave | Plans | Dependencies |
|------|-------|-------------|
| 1 | 05-01 (About), 05-02 (Error pages) | None |
| 2 | 05-03 (Images), 05-04 (Cache) | Wave 1 |
| 3 | 05-05 (Hosting), 05-06 (Docs) | Wave 2 |

## Verification
- `php artisan test` — all existing + new tests pass
- Truy cập `/ve-chung-toi` — About page hiển thị nội dung động
- Truy cập `/nonexistent` — 404 page hiển thị đẹp, có i18n
- `<img loading="lazy">` present on all product/category/post images
- Admin user can edit About content in Filament
