# ROADMAP.md

## Version 1.0 — QTM Việt MVP

### Phase 0: Hoàn thiện Frontend Template
**Mục tiêu:** Hoàn thiện giao diện tĩnh (HTML/CSS/JS) trong `template-frontend/` — build các trang và components còn thiếu cho catalog B2B.

**Deliverables:**
- **Product detail page (MỚI):**
  - Layout: hình ảnh sản phẩm (gallery), thông số kỹ thuật, mô tả, PDF tải về, nút yêu cầu báo giá
  - Breadcrumb navigation
  - Sidebar danh mục cùng cấp
  - Sản phẩm liên quan (gợi ý)
- **Trang danh mục sản phẩm (nâng cấp):**
  - Filter by category (sidebar hoặc dropdown)
  - Product grid với card layout
  - Phân trang static
  - Search box
- **Components chung:**
  - Product card component (tái sử dụng)
  - Section title component
  - Pagination component
- **Cải thiện layout hiện có:**
  - Header: dropdown menu đa cấp cho danh mục
  - Footer: sitemap links, social links
  - Homepage: sections có cấu trúc (banner, danh mục, sản phẩm, tin tức)

**Dependencies:** None

**Verification:**
- Mở `template-frontend/product-detail.html` — trang chi tiết sản phẩm render đúng layout
- Mở `template-frontend/category.html` — filter hoạt động, grid hiển thị
- Product card đồng nhất trên mọi trang
- Responsive: desktop/tablet/mobile

---

### Phase 1: Nền tảng (Foundation)
**Mục tiêu:** Dựng Laravel project, database, Filament admin, base layout Blade.

**Deliverables:**
- Laravel project initialized (Composer, env, app key)
- MySQL database with migrations: categories, brands, products, posts, projects, contacts, inquiries
- Filament admin panel installed & configured
- Base Blade layout từ template-frontend (header, footer, navigation)
- Admin user authentication (Filament built-in)
- Static pages route + controller (home, about, contact)

**Dependencies:** Phase 0

**Verification:**
- `php artisan serve` — trang web chạy được
- Truy cập `/admin` — login page hiển thị
- Blade layout render đúng cấu trúc từ template-frontend

---

### Phase 2: Quản lý sản phẩm (Product Management)
**Mục tiêu:** CRUD sản phẩm trong admin + frontend hiển thị sản phẩm.

**Deliverables:**
- **Admin (Filament):**
  - CategoryResource — quản lý danh mục đa cấp
  - BrandResource — quản lý hãng sản xuất
  - ProductResource — quản lý sản phẩm (tên, mã, danh mục, hãng, mô tả, hình ảnh, brochure PDF)
  - Thông số kỹ thuật dạng key-value via Repeater (JSON column)
- **Frontend (Blade):**
  - Trang chủ động — banner slider, danh mục nổi bật, sản phẩm tiêu biểu, dự án, tin tức mới
  - Trang danh mục sản phẩm — product grid, sidebar danh mục dạng cây, breadcrumb
  - Trang chi tiết sản phẩm — hình ảnh, thông số, PDF tải về, nút "Yêu cầu báo giá"
  - Tìm kiếm sản phẩm (tên, mã, hãng)
- **Tests:**
  - Filament CRUD tests: Category, Brand, Product + Product specs format
  - Frontend HTTP tests: Homepage, Category, ProductDetail, Search, CardRendering

**Dependencies:** Phase 1

**Plans:** 5 plans

| Plan | Wave | Type | Objective |
|------|------|------|-----------|
| 02-01 | 1 | execute | Foundation: install intervention/image-laravel, fill Category/Brand/Product models |
| 02-02 | 2 | execute | Database Seeders: CategorySeeder (3-level), BrandSeeder, ProductSeeder |
| 02-03 | 2 | execute | Category & Brand Filament Resources with CRUD tests |
| 02-04 | 3 | execute | Product Filament Resource with cascading selects, FileUpload, Repeater, Intervention resize |
| 02-05 | 3 | execute | Frontend fixes (specs format), category tree sidebar, 5 HTTP test files |

**Verification:**
- Admin: thêm/sửa/xóa danh mục, brand, sản phẩm thành công
- Frontend: danh mục hiển thị đúng cây, sản phẩm hiển thị đầy đủ thông tin
- Tất cả tests pass: `php artisan test --filter=Filament && php artisan test --filter='Tests\\Feature\\Http'`
- Responsive: desktop/tablet/mobile

---

### Phase 3: Nội dung & Liên hệ (Content & Contact)
**Mục tiêu:** Tin tức, dự án, form liên hệ, yêu cầu báo giá.

**Deliverables:**
- **Admin (Filament):**
  - PostResource — quản lý tin tức/blog
  - ProjectResource — quản lý dự án tiêu biểu
  - InquiryResource — quản lý yêu cầu báo giá
  - ContactResource — quản lý liên hệ
- **Frontend (Blade):**
  - Trang tin tức — danh sách + chi tiết bài viết
  - Trang dự án — danh sách + chi tiết dự án
  - Trang liên hệ — thông tin công ty + form liên hệ (có bản đồ)
  - Form yêu cầu báo giá (từ trang chi tiết sản phẩm)
- **Email:**
  - Gửi email admin khi có yêu cầu báo giá / liên hệ mới
  - Gửi email xác nhận tự động cho khách hàng

**Dependencies:** Phase 1

**Plans:** 5 plans

| Plan | Wave | Type | Objective |
|------|------|------|-----------|
| 03-01 | 1 | execute | Models, Mailables & Seeders — populate 4 Eloquent models, 2 Mailable classes, PostSeeder & ProjectSeeder |
| 03-02 | 2 | execute | Post & Project Filament Resources (CRUD) — PostResource (RichEditor), ProjectResource (FileUpload gallery) |
| 03-03 | 2 | execute | Inquiry & Contact Filament Resources (read-only) — ViewRecord + mark is_read + DeleteAction |
| 03-04 | 3 | execute | Post & Project Frontend — Controllers, Blade views (index + show), pagination, image gallery |
| 03-05 | 3 | execute | Contact form, Quote modal, Navbar, Email, Tests — 2 Controllers, 3 view updates, 6 test files |

**Verification:**
- Form liên hệ gửi được, admin nhận email
- Yêu cầu báo giá từ sản phẩm lưu DB + gửi email
- Tin tức, dự án hiển thị đúng frontend
- Tất cả tests pass: `php artisan test --filter=Phase3`

---

### Phase 4: Đa ngôn ngữ & SEO (Multi-language & SEO)
**Mục tiêu:** Kiến trúc i18n + tối ưu SEO cơ bản.

**Dependencies:** Phase 2, Phase 3

**Plans:** 7 plans

| Plan | Wave | Type | Objective |
|------|------|------|-----------|
| 04-01 | 1 | execute | Package Install + JSON Migration — composer require 3 packages, HasTranslations trait on 5 models, JSON column migration + data migration, fallback locale |
| 04-02 | 1 | execute | Locale Routing + Middleware — SetLocale middleware, prefix all frontend routes with `/{locale}`, root redirect, robots.txt |
| 04-03 | 2 | execute | Filament Translatable UI — Translatable concern on 5 Resources, LocaleSwitcher on Create/Edit pages |
| 04-04 | 2 | execute | lang/ Files + Language Switcher — 12 lang files (6×2 locales), language-switcher component, OG tag structure in layout |
| 04-07 | 2 | execute | Seeders Update + Search Query Fix — bilingual seeders with English, JSON path search queries `name->vi`/`name->en` |
| 04-05 | 3 | execute | i18n in Views — replace all hardcoded Vietnamese in Blade views with `__()` calls |
| 04-06 | 3 | execute | SEO: Sitemap + OG Tags — Sitemapable interface on 4 models, sitemap generation command, $seo data in controllers, OG tag wiring |

**Deliverables:**
- **i18n:**
  - Cài spatie/laravel-translatable — translatable models
  - Language switcher (UI)
  - Translatable fields: category name, product name/description, brand name, post content, meta fields
  - Language files (lang/) cho UI strings (tiếng Việt default)
  - Admin: Filament hỗ trợ nhập nội dung đa ngôn ngữ
- **SEO:**
  - Meta title, description, keywords cho từng trang
  - URL slug thân thiện cho category, product, post
  - Sitemap.xml tự động (daily schedule)
  - robots.txt
  - Open Graph tags cơ bản

**Wave 1** (parallel):
- 04-01: Install packages + JSON migration + HasTranslations trait
- 04-02: SetLocale middleware + route prefix + root redirect

**Wave 2** (parallel, after 04-01):
- 04-03: Filament tabbed translatable UI
- 04-04: Language files + switcher component
- 04-07: Bilingual seeders + search query fixes

**Wave 3** (parallel, after 04-02 + 04-04):
- 04-05: i18n view replacements
- 04-06: Sitemap + OG tags

**Verification:**
- Chuyển đổi ngôn ngữ trên frontend hoạt động
- Nội dung song ngữ trong admin nhập được
- Sitemap.xml trả về danh sách URL hợp lệ
- Search hoạt động với JSON column queries
- Tất cả view text được i18n hóa
- OG meta tags có trong HTML head

---

### Phase 5: Hoàn thiện & Triển khai (Polish & Deploy)
**Mục tiêu:** Hoàn thiện, tối ưu, chuẩn bị shared hosting.

**Deliverables:**
- Trang About (nội dung động từ admin)
- Trang 404, error pages
- Tối ưu hình ảnh (lazy loading, resize tự động)
- Cache: query caching, page caching (cho shared hosting)
- Tối ưu shared hosting: .htaccess, PHP OPcache, queue config
- Deployment checklist: DB migration, env config, public symlink
- Document: hướng dẫn đăng nhập admin + quản lý nội dung cơ bản

**Dependencies:** Phase 2, Phase 3, Phase 4

**Verification:**
- Lighthouse Desktop ≥ 80, Mobile ≥ 65
- Tất cả chức năng hoạt động trên môi trường shared hosting
- Admin có thể thêm/sửa nội dung thành công

---

## Milestone: v1.0

**Gồm:** Phase 0 → Phase 5

**Definition of Done:**
1. Tất cả phases hoàn thành
2. Website hiển thị đúng trên desktop/tablet/mobile
3. Admin panel hoạt động đầy đủ chức năng CRUD
4. Yêu cầu báo giá / liên hệ gửi được email
5. Triển khai được lên shared hosting
