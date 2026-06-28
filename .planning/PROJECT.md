# Product Catalog B2B

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

### Active

- [ ] **Laravel backend + MySQL**: Xây dựng ứng dụng Laravel với database MySQL
- [ ] **Admin panel**: Quản lý danh mục, sản phẩm, hãng sản xuất, tin tức, dự án, yêu cầu báo giá
- [ ] **Quản lý danh mục**: Hỗ trợ tối thiểu 3 cấp, không giới hạn ngành hàng
- [ ] **Quản lý sản phẩm**: Tên, mã, danh mục, hãng, mô tả, hình ảnh, brochure PDF
- [ ] **Thông số kỹ thuật**: Dạng thuộc tính - giá trị (dynamic)
- [ ] **Hãng sản xuất**: Danh sách hãng, mỗi sản phẩm thuộc một hãng
- [ ] **Trang chủ động**: Banner, danh mục, sản phẩm nổi bật, dự án, tin tức, form liên hệ
- [ ] **Trang danh mục sản phẩm**: Hiển thị sản phẩm theo danh mục với tìm kiếm/lọc cơ bản
- [ ] **Trang chi tiết sản phẩm**: Hình ảnh, mô tả, thông số kỹ thuật, PDF, nút yêu cầu báo giá
- [ ] **Yêu cầu báo giá**: Form gửi từ trang chi tiết sản phẩm — lưu DB + email admin + email xác nhận
- [ ] **Tìm kiếm**: Theo tên sản phẩm, mã sản phẩm, hãng sản xuất
- [ ] **Tin tức / Blog**: Danh sách + chi tiết bài viết
- [ ] **Dự án**: Hình ảnh, mô tả, thiết bị đã cung cấp
- [ ] **Trang Liên hệ**: Thông tin công ty + bản đồ + form liên hệ (tĩnh hiện tại, cần backend)
- [ ] **Giao diện người dùng**: Xây lại template-frontend thành Laravel Blade views
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
- `template-frontend/` — Static HTML template (Tea House từ HTML Codex), chưa tích hợp Laravel
- `general-requirement.md` — Yêu cầu chi tiết dự án
- `.planning/codebase/` — Codebase map đã phân tích (7 documents)
- `scripts/` — Dev scripts (run.ps1, stop.ps1, verify-phase1.ps1)

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
*Last updated: 2026-06-20 after initialization*
