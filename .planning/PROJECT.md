# QTM Việt

## What This Is

Website giới thiệu doanh nghiệp và danh mục sản phẩm đa ngành hàng (thiết bị an ninh soi chiếu, máy móc xây dựng, thiết bị y tế). Cho phép khách hàng xem thông tin sản phẩm, thông số kỹ thuật, và gửi yêu cầu báo giá — không có chức năng bán hàng trực tuyến. Chủ doanh nghiệp tự quản lý nội dung qua admin panel thân thiện.

## Core Value

Sản phẩm được hiển thị rõ ràng, đầy đủ thông tin kỹ thuật — khách hàng xem được và gửi được yêu cầu báo giá.

## Requirements

### Validated

(Inferred from existing code in `template-frontend/`)

- ✓ Static HTML template với cấu trúc trang chủ, danh mục, chi tiết sản phẩm — existing
- ✓ Bootstrap 5 responsive framework + SCSS tùy chỉnh — existing
- ✓ Carousel/slider (Owl Carousel) cho banner và hình ảnh — existing
- ✓ Multi-page navigation (header/footer trên mọi trang) — existing
- ✓ Layout cho các trang: Home, About, Products/Blog list, Chi tiết sản phẩm, Liên hệ — existing
- ✓ Color scheme và typography cơ bản (theme xanh lá - cam) — existing

**Delivered in Phase 2:**

- ✓ **Quản lý danh mục (3 cấp)**: Category model + Filament CategoryResource + CategorySeeder với 3-level hierarchy (22 categories) — Phase 2
- ✓ **Quản lý sản phẩm (CRUD)**: Product model + Filament ProductResource với cascading selects, image upload, brochure, specs Repeater — Phase 2
- ✓ **Hãng sản xuất**: Brand model + Filament BrandResource + BrandSeeder (5 hãng) — Phase 2
- ✓ **Thông số kỹ thuật**: Dynamic attribute-value pairs via Filament Repeater + `technical_specs` JSON column with `array` cast — Phase 2
- ✓ **Trang danh mục sản phẩm**: `products.index` với search/sort/filter + category tree sidebar — Phase 2
- ✓ **Trang chi tiết sản phẩm**: `products.show` với gallery, specs table, brochure download, related products, quote button — Phase 2
- ✓ **Tìm kiếm sản phẩm**: MySQL LIKE trên name/SKU/short_description via ProductController — Phase 2
- ✓ **Frontend tests**: 5 HTTP test files (homepage, category, product detail, search, card rendering) — Phase 2
- ✓ **Filament CRUD tests**: 4 test files (Category, Brand, Product resources + specs format) — Phase 2

### Active

- [ ] **Admin panel**: Quản lý tin tức, dự án, yêu cầu báo giá (Category/Brand/Product done in Phase 2)
- [ ] **Trang chủ động**: Banner, dự án, tin tức, form liên hệ (featured products done in Phase 2)
- [ ] **Yêu cầu báo giá**: Form gửi từ trang chi tiết sản phẩm — lưu DB + email admin + email xác nhận
- [ ] **Tin tức / Blog**: Danh sách + chi tiết bài viết
- [ ] **Dự án**: Hình ảnh, mô tả, thiết bị đã cung cấp
- [ ] **Trang Liên hệ**: Thông tin công ty + bản đồ + form liên hệ (tĩnh hiện tại, cần backend)
- [ ] **Đa ngôn ngữ**: Tiếng Việt trước, kiến trúc hỗ trợ thêm tiếng Anh sau
- [ ] **SEO cơ bản**: Meta tags, URL thân thiện, sitemap
- [ ] **Responsive**: Desktop / Tablet / Mobile

### Out of Scope

- Bán hàng trực tuyến (giỏ hàng, thanh toán) — không phải e-commerce
- Tài khoản khách hàng / đăng nhập người dùng — không cần
- Hệ thống thuộc tính động phức tạp — thông số nhập dạng đơn giản
- OAuth / social login — admin đăng nhập cơ bản là đủ
- App mobile — web-first, tiếp cận sau
- Real-time chat — độ phức tạp cao, không cốt lõi

## Context

**Codebase hiện tại:**
- `template-frontend/` — Static HTML template (Tea House từ HTML Codex), đã xây lại một phần thành Blade views
- `general-requirement.md` — Yêu cầu chi tiết dự án
- `.planning/codebase/` — Codebase map đã phân tích (7 documents)
- `.planning/phases/02/` — Phase 2 plans, summaries, research, patterns, UI-SPEC
- `scripts/` — Dev scripts (run.ps1, stop.ps1, verify-phase1.ps1)
- `app/Models/` — Category, Brand, Product models với #[Fillable], casts, relationships
- `app/Filament/Resources/` — Filament CRUD resources cho Categories, Brands, Products (modular Schema/Table pattern)
- `database/seeders/` — CategorySeeder (3-level), BrandSeeder (5 brands), ProductSeeder (10+ products)
- `resources/views/` — Blade views: products/index, products/show, components/category-tree, categories/show

**Quy mô ban đầu:** Dưới 50 sản phẩm, dự kiến mở rộng sau.

**Quản trị:** Chủ doanh nghiệp tự quản lý nội dung — cần admin panel trực quan, dễ dùng cho người không chuyên kỹ thuật.

**Giao diện:** Dựa trên template-frontend hiện có, xây lại với Laravel Blade. Theme xanh lá - cam.

## Constraints

- **Tech Stack**: Laravel + MySQL — như đã xác định trong requirements
- **Hosting**: Shared hosting — cần tối ưu hiệu năng cho môi trường shared hosting
- **Multi-language**: Kiến trúc DB và code phải hỗ trợ i18n ngay từ đầu (dù tiếng Việt ra mắt trước)
- **Quản trị viên**: Người không chuyên kỹ thuật — admin panel phải thân thiện, intuitive
- **No e-commerce**: Website chỉ hiển thị sản phẩm, không có chức năng mua bán

## Key Decisions

| Decision | Rationale | Outcome |
|----------|-----------|---------|
| MVP tập trung vào hiển thị danh mục sản phẩm | Ưu tiên cốt lõi: trưng bày sản phẩm là chức năng chính | ✓ Decided |
| Dùng lại template-frontend làm giao diện Blade | Tiết kiệm thời gian thiết kế, tận dụng layout có sẵn | ✓ Decided |
| Tiếng Việt trước, thêm tiếng Anh sau | Đơn giản hóa MVP, kiến trúc sẵn sàng cho i18n | ✓ Decided |
| Chủ doanh nghiệp tự quản trị | Admin panel thân thiện, không cần phân quyền phức tạp | ✓ Decided |
| Admin panel dùng Filament | Tăng tốc phát triển admin panel, UI chuyên nghiệp sẵn có | ✓ Decided |
| Cấu trúc Laravel mặc định (đơn giản) | Phù hợp quy mô <50 sản phẩm, không over-engineering | ✓ Decided |
| Dev scripts gom vào `scripts/` | Giữ root project sạch, dễ mở rộng thêm script sau này | ✓ Decided |
| Filament Resource modular pattern: tách Form/Table thành class riêng | Giảm độ phức tạp mỗi file, dễ maintain và test riêng biệt | ✓ Applied in Phase 2 |
| Thông số kỹ thuật lưu dạng JSON column (array-of-objects) | Tránh tạo table riêng, đơn giản cho MVP, Filament Repeater xử lý được | ✓ Applied in Phase 2 |
| 3-level cascading category selects: `dehydrated(false)` cho level 0/1 | Chỉ lưu FK `category_id` vào DB, 2 select đầu là helpers — clean data model | ✓ Applied in Phase 2 |

## Evolution

This document evolves at phase transitions and milestone boundaries.

**After each phase transition** (via `/gsd-transition`):
1. Requirements invalidated? → Move to Out of Scope with reason
2. Requirements validated? → Move to Validated with phase reference
3. New requirements emerged? → Add to Active
4. Decisions to log? → Add to Key Decisions
5. "What This Is" still accurate? → Update if drifted

**After each milestone** (via `/gsd-complete-milestone`):
1. Full review of all sections
2. Core Value check — still the right priority?
3. Audit Out of Scope — reasons still valid?
4. Update Context with current state

---
*Last updated: 2026-06-28 after Phase 2 completion*
