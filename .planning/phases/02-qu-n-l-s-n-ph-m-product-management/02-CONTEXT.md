# Phase 2: Quản lý sản phẩm (Product Management) - Context

**Gathered:** 2026-06-27
**Status:** Ready for planning

<domain>
## Phase Boundary

CRUD danh mục/hãng/sản xuất và sản phẩm trong Filament admin panel, cùng frontend Blade views hiển thị động từ database (thay thế HTML tĩnh trong template-frontend).

**In scope:**
- Admin: CategoryResource (đa cấp), BrandResource, ProductResource, ProductSpecification
- Frontend: Trang chủ động, danh mục sản phẩm, chi tiết sản phẩm, tìm kiếm
- Tất cả dữ liệu từ DB, đọc qua Eloquent models

**Out of scope (phases sau):**
- Email gửi yêu cầu báo giá — Phase 3 (Content & Contact)
- Nội dung tin tức/dự án — Phase 3
- i18n / đa ngôn ngữ — Phase 4
- Tối ưu shared hosting — Phase 5
- Giỏ hàng/thanh toán — out of scope (dự án)
</domain>

<decisions>
## Implementation Decisions

### Category Hierarchy
- **D-01:** 3 levels max — Ngành (level 0) → Nhóm (level 1) → Loại (level 2)
- **D-02:** Admin chọn danh mục bằng 3 cascading dropdowns trên Product form: chọn Ngành → filter Nhóm → filter Loại
- **D-03:** Frontend sidebar hiển thị cây danh mục dạng expandable, mặc định collapsed ở level 2

### Product Images
- **D-04:** Multiple images per product — 1 ảnh thumbnail đại diện + gallery (các ảnh phụ)
- **D-05:** Lưu trên local filesystem (phù hợp shared hosting, không cần cloud)
- **D-06:** Auto-resize ảnh upload xuống 600x600 px dùng Intervention Image (cần cài `intervention/image-laravel`)

### Technical Specifications
- **D-07:** Free-form key-value pairs (attribute_name + attribute_value) — không cần predefined attributes per category
- **D-08:** Filament Repeater cho admin nhập thông số (thêm/xóa dòng động)
- **D-09:** (UPDATED from research) Dùng JSON column `products.technical_specs` có sẵn — không cần migration mới. ProductSpecification model/resource không cần tạo riêng. Blade views parse JSON trực tiếp.

### Product Search
- **D-10:** MySQL LIKE query trên tên, mã sản phẩm, tên hãng
- **D-11:** Submit-based search (gõ keyword → Enter → kết quả) — không live/AJAX
- **D-12:** Không dùng Laravel Scout hay search engine riêng

### the agent's Discretion
- Loading skeleton / spinner design trên frontend
- Số sản phẩm hiển thị mỗi trang (pagination count)
- Category tree expanded/collapsed state behavior
- Error/empty state handling
- Layout spacing và typography chi tiết

</decisions>

<specifics>
## Specific Ideas

- Frontend giao diện bám sát template-frontend hiện có (Bootstrap 5, xanh lá - cam)
- Admin panel dùng Filament built-in UI, không custom theme
- Sản phẩm có thể có hoặc không có brochure PDF (không bắt buộc)

</specifics>

<canonical_refs>
## Canonical References

**Downstream agents MUST read these before planning or implementing.**

### Phase Definition
- `.planning/ROADMAP.md` — Phase 2 deliverables, dependencies, verification criteria
- `.planning/STATE.md` — Current phase state, key decisions
- `.planning/PROJECT.md` — Project identity, requirements, constraints

### Codebase Context
- `.planning/codebase/STRUCTURE.md` — File and directory structure
- `.planning/codebase/CONVENTIONS.md` — Coding conventions, naming, patterns
- `.planning/codebase/STACK.md` — Technology stack

### Design Reference
- `template-frontend/` — Design reference for Blade views (product, store, category pages)
- `resources/views/layouts/app.blade.php` — Base Blade layout đã migrate từ template

### Existing Models & Migrations
- `database/migrations/` — All migration files created in Phase 1
- `app/Models/` — Eloquent models (Category, Brand, Product, ProductSpecification)

</canonical_refs>

<code_context>
## Existing Code Insights

### Reusable Assets
- `resources/views/layouts/app.blade.php` — Base layout với header, footer, navigation
- `app/Models/Category.php` — Category model (cần thêm relationships cho hierarchy)
- `app/Models/Brand.php` — Brand model
- `app/Models/Product.php` — Product model (cần thêm relationships cho specs, images)
- `app/Models/ProductSpecification.php` — Specification model
- `database/migrations/` — All migrations đã tạo (categories, brands, products, product_specifications)
- File upload và image handling helpers trong Laravel/Filament

### Established Patterns
- Laravel MVC: Route → Controller → Model → View (Blade)
- Filament Resource: Resource → Pages (List, Create, Edit) → Form schema → Table schema
- Eloquent relationships giữa các models (belongsTo, hasMany)
- Vite for asset bundling (Laravel mặc định)

### Integration Points
- `routes/web.php` — Frontend routes sẽ thêm
- `routes/filament.php` — Filament admin routes (đã có auth)
- `app/Providers/Filament/AdminPanelProvider.php` — Filament panel configuration
- `app/Http/Controllers/` — Controllers cần mở rộng cho frontend pages

</code_context>

<deferred>
## Deferred Ideas

- **Structured per-category attributes** (CategoryAttribute model) — nếu sau này cần thông số nhất quán theo từng danh mục, có thể nâng cấp từ free-form KVP
- **AJAX live search** — nếu catalog phát triển > 100 sản phẩm, nâng cấp từ submit-based
- **Cloud image storage (S3)** — nếu chuyển lên cloud hosting

</deferred>

---

*Phase: 02-qu-n-l-s-n-ph-m-product-management*
*Context gathered: 2026-06-27*
