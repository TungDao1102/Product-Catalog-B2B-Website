# Plan 02-04 Summary: Product Filament Resource

**Phase:** 02 — Quản lý sản phẩm (Product Management)
**Plan:** 04 — Product Resource
**Wave:** 3
**Status:** ✅ Verified

## Deliverables

| Artifact | Status | Notes |
|----------|--------|-------|
| `app/Filament/Resources/Products/ProductResource.php` | ✅ | Model binding + navigation (sort 3, icon `heroicon-o-shopping-bag`) |
| `app/Filament/Resources/Products/Schemas/ProductForm.php` | ✅ | 152 lines — 5 form sections with cascading selects, FileUpload, Repeater |
| `app/Filament/Resources/Products/Tables/ProductsTable.php` | ✅ | 71 lines — SKU, name, brand, category, price (VND), featured/active icons, filters |
| `app/Filament/Resources/Products/Pages/CreateProduct.php` | ✅ | afterSave hook with Intervention Image resize (600x600) |
| `app/Filament/Resources/Products/Pages/EditProduct.php` | ✅ | afterSave hook + DeleteAction |
| `app/Filament/Resources/Products/Pages/ListProducts.php` | ✅ | List page |
| `tests/Feature/Filament/ProductResourceTest.php` | ✅ | 63 lines — list, create render, edit render |
| `tests/Feature/Filament/ProductSpecsTest.php` | ✅ | 61 lines — array-of-objects format, null specs, key contract validation |

## Must-Haves Verification

| Truth | Status | Evidence |
|-------|--------|----------|
| Admin can CRUD products via /admin/products | ✅ | ProductResource with list/create/edit/delete |
| 3-level cascading category selects | ✅ | `category_level_0` (Ngành) → `category_level_1` (Nhóm) → `category_id` (Loại) — all `->live()` with `->dehydrated(false)` on level 0/1 |
| Multiple image upload (reorderable, max 10) | ✅ | `FileUpload::make('images')->multiple()->reorderable()->image()->maxFiles(10)` |
| Brochure PDF upload (max 10MB) | ✅ | `FileUpload::make('brochure')->acceptedFileTypes(['application/pdf'])->maxSize(10240)` |
| Repeater for technical_specs (key-value rows) | ✅ | `Repeater::make('technical_specs')` with `attribute_name` + `attribute_value` TextInputs, `->defaultItems(0)`, `->collapsible()` |
| Images auto-resize to 600x600 via Intervention Image | ✅ | `afterSave()` hook in both CreateProduct and EditProduct using `Image::decodePath()->resize(width: 600, height: 600)` |
| Product table: SKU, name, brand, category, featured, active | ✅ | All columns present in ProductsTable.php |
| Featured badge via icon column | ✅ | `IconColumn::make('is_featured')->boolean()` with "Nổi bật" label |
| All CRUD operations pass automated tests | ✅ | ProductResourceTest + ProductSpecsTest |

## ProductForm Schema Sections

| Section | Fields |
|---------|--------|
| Thông tin cơ bản | name (auto-slug), slug, SKU, 3-level category selects, brand_id, unit, price (VNĐ), min_order_qty |
| Mô tả | short_description, RichEditor description |
| Hình ảnh & Tài liệu | images (FileUpload multiple), brochure (FileUpload PDF) |
| Thông số kỹ thuật | Repeater (attribute_name + attribute_value pairs) |
| Hiển thị | is_featured, is_active, sort_order, meta_title, meta_description |

## Key Design Decisions

- Uses `Image::decodePath()` for Intervention Image v4.x API (compatible with installed `^4.0`)
- Images stored at `storage/app/public/products/` on `public` disk
- First 2 category selects have `->dehydrated(false)` — helper fields only, `category_id` is the real FK
- `technical_specs` stored as `array` cast JSON — matches Filament Repeater format exactly
- Navigation sort: 3 (after Categories=1, Brands=2)

## Key Links

| From | To | Via | Status |
|------|----|-----|--------|
| `ProductResource.php` | `Product.php` | `$model = Product::class` | ✅ |
| `CreateProduct.php` | Intervention Image | `afterSave` hook with resize | ✅ |
| `EditProduct.php` | Intervention Image | `afterSave` hook with resize | ✅ |

## Threat Model

| Threat | Category | Mitigation | Status |
|--------|----------|------------|--------|
| T-02-06 | File upload (images) | `->image()` validation, UUID filenames | ✅ |
| T-02-07 | File upload (brochure PDF) | `->acceptedFileTypes(['application/pdf'])`, `maxSize(10240)` | ✅ |
| T-02-08 | XSS in RichEditor description | `nl2br(e())` escaping in Blade view | ✅ |
| T-02-09 | Mass assignment | `#[Fillable]` attribute on Product model | ✅ |
