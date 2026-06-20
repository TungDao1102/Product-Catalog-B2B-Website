# ROADMAP.md

## Version 1.0 — Product Catalog MVP

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

### Phase 2: Nền tảng (Foundation)
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

### Phase 3: Quản lý sản phẩm (Product Management)
**Mục tiêu:** CRUD sản phẩm trong admin + frontend hiển thị sản phẩm.

**Deliverables:**
- **Admin (Filament):**
  - CategoryResource — quản lý danh mục đa cấp
  - BrandResource — quản lý hãng sản xuất
  - ProductResource — quản lý sản phẩm (tên, mã, danh mục, hãng, mô tả, hình ảnh, brochure PDF)
  - ProductSpecification — thông số kỹ thuật dạng thuộc tính- giá trị (repeater/has-many)
- **Frontend (Blade):**
  - Trang chủ động — banner slider, danh mục nổi bật, sản phẩm tiêu biểu, dự án, tin tức mới
  - Trang danh mục sản phẩm — product grid, sidebar danh mục, breadcrumb
  - Trang chi tiết sản phẩm — hình ảnh, thông số, PDF tải về, nút "Yêu cầu báo giá"
  - Tìm kiếm sản phẩm (tên, mã, hãng)

**Dependencies:** Phase 2

**Verification:**
- Admin: thêm/sửa/xóa danh mục, brand, sản phẩm thành công
- Frontend: danh mục hiển thị đúng cây, sản phẩm hiển thị đầy đủ thông tin
- Responsive: desktop/tablet/mobile

---

### Phase 4: Nội dung & Liên hệ (Content & Contact) (Content & Contact)
**Mục tiêu:** Tin tức, dự án, form liên hệ, yêu cầu báo giá.

**Deliverables:**
- **Admin (Filament):**
  - PostResource — quản lý tin tức/blog
  - ProjectResource — quản lý dự án tiêu biểu
  - InquiryResource — quản lý yêu cầu báo giá
  - ContactInquiryResource — quản lý liên hệ
- **Frontend (Blade):**
  - Trang tin tức — danh sách + chi tiết bài viết
  - Trang dự án — danh sách + chi tiết dự án
  - Trang liên hệ — thông tin công ty + form liên hệ (có bản đồ)
  - Form yêu cầu báo giá (từ trang chi tiết sản phẩm)
- **Email:**
  - Gửi email admin khi có yêu cầu báo giá / liên hệ mới
  - Gửi email xác nhận tự động cho khách hàng

**Dependencies:** Phase 2

**Verification:**
- Form liên hệ gửi được, admin nhận email
- Yêu cầu báo giá từ sản phẩm lưu DB + gửi email
- Tin tức, dự án hiển thị đúng frontend

---

### Phase 5: Đa ngôn ngữ & SEO (Multi-language & SEO)
**Mục tiêu:** Kiến trúc i18n + tối ưu SEO cơ bản.

**Deliverables:**
- **i18n:**
  - Cài spatie/laravel-translatable — translatable models
  - Language switcher (UI)
  - Translatable fields: category name, product name/description, brand name, post content
  - Language files (lang/) cho UI strings (tiếng Việt default)
  - Admin: Filament hỗ trợ nhập nội dung đa ngôn ngữ
- **SEO:**
  - Meta title, description, keywords cho từng trang
  - URL slug thân thiện cho category, product, post
  - Sitemap.xml tự động
  - robots.txt
  - Open Graph tags cơ bản

**Dependencies:** Phase 3, Phase 4

**Verification:**
- Chuyển đổi ngôn ngữ trên frontend hoạt động
- Nội dung song ngữ trong admin nhập được
- Sitemap.xml trả về danh sách URL hợp lệ

---

### Phase 6: Hoàn thiện & Triển khai (Polish & Deploy)
**Mục tiêu:** Hoàn thiện, tối ưu, chuẩn bị shared hosting.

**Deliverables:**
- Trang About (nội dung động từ admin)
- Trang 404, error pages
- Tối ưu hình ảnh (lazy loading, resize tự động)
- Cache: query caching, page caching (cho shared hosting)
- Tối ưu shared hosting: .htaccess, PHP OPcache, queue config
- Deployment checklist: DB migration, env config, public symlink
- Document: hướng dẫn đăng nhập admin + quản lý nội dung cơ bản

**Dependencies:** Phase 3, Phase 4, Phase 5

**Verification:**
- Lighthouse Desktop ≥ 80, Mobile ≥ 65
- Tất cả chức năng hoạt động trên môi trường shared hosting
- Admin có thể thêm/sửa nội dung thành công

---

## Milestone: v1.0

**Gồm:** Phase 0 → Phase 6

**Definition of Done:**
1. Tất cả phases hoàn thành
2. Website hiển thị đúng trên desktop/tablet/mobile
3. Admin panel hoạt động đầy đủ chức năng CRUD
4. Yêu cầu báo giá / liên hệ gửi được email
5. Triển khai được lên shared hosting
