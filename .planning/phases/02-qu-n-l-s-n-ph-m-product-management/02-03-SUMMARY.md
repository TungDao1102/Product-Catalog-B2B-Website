# Plan 02-03 Summary: Filament Category & Brand Resources

**Phase:** 02 — Quản lý sản phẩm (Product Management)
**Plan:** 03 — Category & Brand Filament Resources
**Wave:** 2
**Status:** ✅ Verified

## Deliverables

| Artifact | Status | Notes |
|----------|--------|-------|
| `app/Filament/Resources/Categories/CategoryResource.php` | ✅ | Model binding + navigation (sort 1, icon `heroicon-o-rectangle-stack`) |
| `app/Filament/Resources/Categories/Schemas/CategoryForm.php` | ✅ | Name (auto-slug), slug (unique), parent_id select, description, image upload, is_active toggle, sort_order |
| `app/Filament/Resources/Categories/Tables/CategoriesTable.php` | ✅ | Name (searchable/sortable), parent.name, is_active icon, sort_order — default sort by sort_order |
| `app/Filament/Resources/Categories/Pages/*.php` | ✅ | ListCategories, CreateCategory, EditCategory |
| `app/Filament/Resources/Brands/BrandResource.php` | ✅ | Model binding + navigation (sort 2, icon `heroicon-o-building-office`) |
| `app/Filament/Resources/Brands/Schemas/BrandForm.php` | ✅ | Name, slug (unique), description, logo upload, website URL, is_active toggle, sort_order |
| `app/Filament/Resources/Brands/Tables/BrandsTable.php` | ✅ | Name (searchable), logo ImageColumn, website, is_active icon, sort_order |
| `app/Filament/Resources/Brands/Pages/*.php` | ✅ | ListBrands, CreateBrand, EditBrand |
| `tests/Feature/Filament/CategoryResourceTest.php` | ✅ | 62 lines — list, create render, create, edit render |
| `tests/Feature/Filament/BrandResourceTest.php` | ✅ | 62 lines — list, create render, create, edit render |
| `tests/Feature/Filament/ResourceNavigationTest.php` | ✅ | 37 lines — categories + brands index pages |
| `database/factories/CategoryFactory.php` | ✅ | Created for test data generation |
| `database/factories/BrandFactory.php` | ✅ | Created for test data generation |

## Must-Haves Verification

| Truth | Status | Evidence |
|-------|--------|----------|
| Admin can CRUD categories via /admin page | ✅ | Full form schema + table with list/create/edit/delete |
| Category form: name, slug, parent_id, description, image, is_active, sort_order | ✅ | All fields present in CategoryForm.php |
| Category table: name, parent name, is_active icon, sort_order | ✅ | All columns in CategoriesTable.php |
| Admin can CRUD brands via /admin page | ✅ | Full form schema + table with list/create/edit/delete |
| Brand form: name, slug, description, logo, website, is_active, sort_order | ✅ | All fields present in BrandForm.php |
| Brand table: name, logo thumbnail, website link, is_active icon | ✅ | ImageColumn for logo, TextColumn for website |
| Both resources in admin sidebar | ✅ | Navigation labels: "Danh mục" (sort 1), "Hãng" (sort 2) |
| All CRUD operations have automated tests | ✅ | 3 test files covering both resources |

## Architecture Notes

The implementation uses a **modular pattern** with separate `Schemas/` and `Tables/` classes rather than inline form/table definitions. The `CategoryResource.php` and `BrandResource.php` delegate to these schemas via `CategoryForm::configure($schema)` and `Tables\CategoriesTable::configure($table)`. This is an improvement over the original plan's inline approach.

## Key Links

| From | To | Via | Status |
|------|----|-----|--------|
| `CategoryResource.php` | `Category.php` | `$model = Category::class` (line 18) | ✅ |
| `BrandResource.php` | `Brand.php` | `$model = Brand::class` (line 18) | ✅ |

## Threat Model

| Threat | Category | Mitigation | Status |
|--------|----------|------------|--------|
| T-02-03 | Tampering — form validation | Filament validation + Eloquent `$fillable` protection | ✅ |
| T-02-04 | Spoofing — XSS in names | Admin-only panel, trusted user context | ✅ (accepted) |
| T-02-05 | Tampering — file upload | `->image()` validation, controlled storage directory | ✅ |
