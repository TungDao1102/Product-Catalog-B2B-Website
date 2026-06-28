# Phase 3: Nội dung & Liên hệ (Content & Contact) - Context

**Gathered:** 2026-06-28
**Status:** Ready for planning

<domain>
## Phase Boundary

Tin tức/blog, dự án tiêu biểu, form liên hệ, yêu cầu báo giá từ trang sản phẩm + gửi email cho admin và khách hàng.

**In scope:**
- Admin (Filament): PostResource, ProjectResource, InquiryResource, ContactResource
- Frontend: Trang tin tức (list + detail), Trang dự án (list + detail), Form liên hệ, Quote request modal
- Email: SMTP — thông báo admin khi có inquiry/contact mới + xác nhận cho khách hàng
- Seeders: PostSeeder (3-5 bài), ProjectSeeder (3-5 dự án)

**Out of scope (phases sau):**
- Danh mục cho posts — Phase 3 không cần, post flat list
- Equipment list cho projects — chỉ images + content
- i18n / đa ngôn ngữ — Phase 4
- Tối ưu shared hosting — Phase 5
</domain>

<decisions>
## Implementation Decisions

### Posts / Tin tức
- **D-01:** Posts là flat list — không có danh mục con
- **D-02:** Editor dùng Filament RichEditor (TinyMCE built-in) cho content — phù hợp người không chuyên
- **D-03:** Post có title, slug (unique), excerpt, content (RichEditor), image, is_published, published_at — migration đã có sẵn

### Projects / Dự án
- **D-04:** Project chỉ có images (JSON gallery) + content — không cần equipment_list riêng
- **D-05:** Project có title, slug (unique), description, content, images (JSON), is_active — migration đã có sẵn

### Yêu cầu báo giá (Quote Request)
- **D-06:** Modal popup ngay trên trang chi tiết sản phẩm (không redirect sang contact page)
- **D-07:** Modal form gồm: name, email, phone, company, quantity, message + product_id (hidden)
- **D-08:** Lưu vào inquiries table (đã có migration: product_id, name, email, phone, company, quantity, message)

### Liên hệ (Contact Form)
- **D-09:** Form trên contact page gồm: name, email, phone, company, message (xóa subject field, thêm phone/company match contacts table)
- **D-10:** Giữ nguyên Google Maps embed hiện tại trên contact page
- **D-11:** Lưu vào contacts table (đã có migration: name, email, phone, company, message)

### Admin (Filament)
- **D-12:** Tạo 4 Filament Resources: PostResource, ProjectResource, InquiryResource (quản lý yêu cầu báo giá), ContactResource (quản lý liên hệ)
- **D-13:** Navigation sort: Category=1, Brand=2, Product=3, Post=4, Project=5, Inquiry=6, Contact=7
- **D-14:** InquiryResource + ContactResource cho phép xem, đánh dấu đã đọc (is_read), xóa — không cần edit (ghi chép của khách)

### Email
- **D-15:** Dùng SMTP driver — cấu hình trong .env
- **D-16:** Gửi email synchronous (không queue) — đơn giản, phù hợp shared hosting
- **D-17:** 2 email templates: admin_notification (thông báo có inquiry/contact mới) + customer_confirmation (xác nhận đã nhận yêu cầu)
- **D-18:** Gửi email cho cả Inquiry (yêu cầu báo giá) và Contact (liên hệ)

### Seeders
- **D-19:** PostSeeder — 3-5 bài viết mẫu có title, slug, excerpt, content, image, published_at
- **D-20:** ProjectSeeder — 3-5 dự án mẫu có title, slug, description, content, images, is_active

### Frontend
- **D-21:** Bám sát template-frontend hiện có (Bootstrap 5, xanh lá - cam)
- **D-22:** Blade views mới: posts/index, posts/show, projects/index, projects/show
- **D-23:** Cập nhật navbar links cho Tin tức và Dự án
- **D-24:** Pagination cho danh sách posts/projects

### the agent's Discretion
- Số items mỗi trang (pagination count)
- Seed data content chi tiết (post titles, project names, v.v.)
- Layout chi tiết và responsive breakpoints
- Modal UI design cho quote request (bám sát Bootstrap 5 theme hiện có)
- Email template design (text-based hoặc HTML đơn giản)

</decisions>

<specifics>
## Specific Ideas

- Quote button trên product.show đã có sẵn (`route('contact', ['product' => ...])`) — cần sửa thành mở modal thay vì redirect
- Contact page đã có Blade view với Google Maps embed — cần update form fields và kết nối DB
- Giao diện bám sát template-frontend (blog.html, product-detail.html, contact.html)
- Admin panel giữ pattern modular như Phase 2: tách Form/Table thành class riêng

</specifics>

<canonical_refs>
## Canonical References

**Downstream agents MUST read these before planning or implementing.**

### Phase Definition
- `.planning/ROADMAP.md` — Phase 3 deliverables, dependencies, verification criteria
- `.planning/STATE.md` — Current phase state
- `.planning/PROJECT.md` — Project identity, requirements, constraints

### Existing Infrastructure
- `database/migrations/2026_06_20_165917_create_posts_table.php` — Posts table schema
- `database/migrations/2026_06_20_165918_create_contacts_table.php` — Contacts table schema
- `database/migrations/2026_06_20_165918_create_projects_table.php` — Projects table schema
- `database/migrations/2026_06_20_165919_create_inquiries_table.php` — Inquiries table schema
- `app/Models/Post.php` — Post model
- `app/Models/Project.php` — Project model
- `app/Models/Contact.php` — Contact model
- `app/Models/Inquiry.php` — Inquiry model

### Design Reference
- `template-frontend/blog.html` — News listing page design
- `template-frontend/contact.html` — Contact page design
- `template-frontend/product-detail.html` — Quote button reference
- `resources/views/contact.blade.php` — Existing contact Blade view (cần cập nhật)
- `resources/views/products/show.blade.php` — Product detail with existing quote button
- `resources/views/layouts/app.blade.php` — Base layout (navbar cần update links)

### Phase 2 Reference
- `.planning/phases/02-qu-n-l-s-n-ph-m-product-management/02-CONTEXT.md` — Prior decisions (modular pattern, design conventions)
- `app/Filament/Resources/` — Existing modular resource pattern (Schema/Table tách class)

</canonical_refs>

<code_context>
## Existing Code Insights

### Existing Models & Migrations (Phase 1)
- `app/Models/Post.php` — Post model (cần fillable + casts)
- `app/Models/Project.php` — Project model (cần fillable + casts cho images JSON)
- `app/Models/Contact.php` — Contact model (cần fillable)
- `app/Models/Inquiry.php` — Inquiry model (cần fillable + product relationship)

### Existing Views
- `resources/views/contact.blade.php` — Full contact page layout with form + map
- `resources/views/products/show.blade.php` — Has quote button cần sửa

### Established Patterns (Phase 2)
- Filament Resource modular: Resource → Schema/Form class → Table class
- Navigation sort ordering via `$_GET` parameter in Resource
- Bootstrap 5 + Owl Carousel + WOW.js cho frontend animations
- JSON columns cast as `array` (cho `projects.images`, không cần cho inquiries)

### Integration Points
- `routes/web.php` — Cần thêm routes cho posts, projects, contact submit, inquiry submit
- `app/Http/Controllers/` — Cần Controllers mới hoặc thêm methods
- `resources/views/layouts/app.blade.php` — Navbar cần thêm links
- `resources/views/products/show.blade.php` — Quote button → modal

</code_context>

<deferred>
## Deferred Ideas

- **Post categories** — Nếu sau này cần phân loại tin tức, thêm PostCategory model + category_id
- **Equipment list cho Projects** — Có thể thêm equipment_list JSON column nếu cần
- **Mail queue** — Nâng cấp lên queue job nếu shared hosting hỗ trợ
- **Reply from admin** — Trả lời inquiry/contact trực tiếp từ Filament (chỉ gửi email)

</deferred>

---

*Phase: 03-n-i-dung-li-n-h*
*Context gathered: 2026-06-28*
