# Plan 02-05 Summary: Frontend Views & HTTP Tests

**Phase:** 02 — Quản lý sản phẩm (Product Management)
**Plan:** 05 — Frontend & HTTP Tests
**Wave:** 3
**Status:** ✅ Verified

## Deliverables

| Artifact | Status | Notes |
|----------|--------|-------|
| `resources/views/products/show.blade.php` | ✅ | Fixed: tech specs as array-of-objects, image paths with `asset('storage/...')`, brochure download, gallery, related products, XSS protection |
| `resources/views/products/index.blade.php` | ✅ | Category tree sidebar, brand filter, search, sort, pagination, empty state |
| `resources/views/components/category-tree.blade.php` | ✅ | 57 lines — 3-level expandable tree with collapse/expand toggles, active state, folder icons |
| `tests/Feature/Http/HomePageTest.php` | ✅ | 40 lines — HTTP 200, featured section |
| `tests/Feature/Http/CategoryPageTest.php` | ✅ | 49 lines — HTTP 200, category name, pagination |
| `tests/Feature/Http/ProductDetailTest.php` | ✅ | 81 lines — HTTP 200, product name, specs, quote button, related products |
| `tests/Feature/Http/ProductSearchTest.php` | ✅ | 69 lines — search by name, SKU, empty state, category + search filter |
| `tests/Feature/Http/ProductCardRenderingTest.php` | ✅ | 48 lines — product cards, featured badge, pagination |

## Must-Haves Verification

| Truth | Status | Evidence |
|-------|--------|----------|
| Product detail shows specs as array-of-objects | ✅ | `$spec['attribute_name']` / `$spec['attribute_value']` with guard `$product->technical_specs && is_array(...) && count(...) > 0` |
| Product detail shows gallery, spec table, brochure, related products, quote button | ✅ | All sections present in show.blade.php |
| Homepage shows featured products | ✅ | `test_homepage_shows_featured_section()` passes |
| Category page shows products with pagination and search | ✅ | CategoryPageTest, ProductSearchTest cover all cases |
| Search by name/SKU returns matching results | ✅ | `ProductController::index()` uses MySQL LIKE on name, SKU, short_description |
| Brand filter via MySQL LIKE | ✅ | Route accepts `search` parameter; brand name search via relation |
| Search uses MySQL LIKE only (no Scout) | ✅ | No Laravel Scout dependency in composer.json; pure Eloquent `where('name', 'like', "%{$search}%")` |

## Frontend View Features

### products/show.blade.php
- **Gallery:** Main image + thumbnail strip with prev/next navigation via JavaScript
- **Tech specs:** Two-column table (Thông số / Giá trị) with null/empty guard
- **Brochure:** PDF download link with icon (conditional rendering)
- **Description:** `nl2br(e())` pattern for XSS-safe HTML
- **Related products:** 4 products from same category/brand
- **Quote button:** Links to `/contact?product={slug}`
- **Breadcrumbs:** Home → Products → Category → Product

### products/index.blade.php
- **Category tree:** Uses `<x-category-tree>` component
- **Brand filter:** Checkboxes with route params
- **Search:** Text input with submit button
- **Sort:** Dropdown with name_asc, name_desc, newest, oldest
- **Pagination:** `$products->appends(request()->query())->links()`
- **Empty state:** `bi-box-seam` icon + "Không tìm thấy sản phẩm nào."

### category-tree.blade.php
- 3-level rendering: Ngành → Nhóm → Loại
- Bootstrap collapse/expand toggles
- Active state highlighting via `activeCategorySlug` prop
- Folder icons via Bootstrap Icons

## Threat Model

| Threat | Category | Mitigation | Status |
|--------|----------|------------|--------|
| T-02-10 | XSS in product description | `{!! nl2br(e($product->description)) !!}` — `e()` escapes HTML | ✅ |
| T-02-11 | SQL injection in search | Eloquent parameterized prepared statements | ✅ (accepted) |
| T-02-12 | Information disclosure | All product data intended for public viewing | ✅ (accepted) |
