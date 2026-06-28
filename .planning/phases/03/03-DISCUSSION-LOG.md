# Phase 3: Nội dung & Liên hệ - Discussion Log

**Date:** 2026-06-28
**Mode:** discuss (default)

## Areas Discussed

### 1. News Categories
- **Options:** Không cần danh mục / Có danh mục
- **User selected:** Không cần danh mục — posts flat list
- **Notes:** Migration posts không có category_id, giữ nguyên

### 2. Content Editor
- **Options:** Filament RichEditor / Trix Editor
- **User selected:** Filament RichEditor (TinyMCE built-in)
- **Notes:** Dễ dùng cho người không chuyên, không cần cài thêm

### 3. Project Equipment List
- **Options:** Chỉ images + content / Có equipment_list JSON
- **User selected:** Chỉ images + content
- **Notes:** Migration hiện tại đủ

### 4. Quote Request Flow
- **Options:** Redirect sang contact / Modal popup
- **User selected:** Modal popup on product page
- **Notes:** Modal form lưu vào inquiries table với product_id

### 5. Contact Form Fields
- **Options:** Đồng bộ với DB / Giữ nguyên hiện tại
- **User selected:** Đồng bộ với DB — name, email, phone, company, message
- **Notes:** Xóa subject field khỏi form, thêm phone + company

### 6. Email Strategy
- **Options:** SMTP / Mailgun API / Queue + SMTP
- **User selected:** SMTP (synchronous)
- **Notes:** Phù hợp shared hosting, config trong .env

### 7. Seeders
- **Options:** Có seeders / Không cần
- **User selected:** Có seeders (PostSeeder + ProjectSeeder)

### 8. Quote Modal Fields
- **Options:** Có quantity / Không cần quantity/company
- **User selected:** Có quantity field

### 9. Admin Management
- **Options:** Có Filament Resources / Không cần admin
- **User selected:** Có Filament Resources cho Inquiry + Contact (xem, đánh dấu đã đọc, xóa)

## Deferred Ideas
- Post categories — future phase nếu cần
- Equipment list cho Projects — optional enhancement
- Mail queue — nếu shared hosting hỗ trợ
- Reply from admin — future enhancement

## the agent's Discretion
- Pagination count, seed data content, modal UI design, email template design
