# Phase 2: Quản lý sản phẩm (Product Management) - Discussion Log

> **Audit trail only.** Do not use as input to planning, research, or execution agents.
> Decisions are captured in CONTEXT.md — this log preserves the alternatives considered.

**Date:** 2026-06-27
**Phase:** 2-Quản lý sản phẩm
**Areas discussed:** Category hierarchy depth, Product images, Tech specs, Product search

---

## 1. Category Hierarchy Depth

| Option | Description | Selected |
|--------|-------------|----------|
| 3 levels max | Ngành → Nhóm → Loại. Ví dụ: Thiết bị y tế → Máy X-quang → Máy X-quang di động | ✓ |
| Unlimited nesting | Không giới hạn — user tự tạo cây danh mục | |
| Flat (1 level only) | Danh mục phẳng, 1 cấp | |

**User's choice:** 3 levels max
**Notes:** Chọn 3 levels để đơn giản, dễ quản lý cho người không chuyên. Đủ cho cấu trúc ngành hàng hiện tại.

**Follow-up — Category Picker UI:**

| Option | Description | Selected |
|--------|-------------|----------|
| 3 cascading dropdowns | Dropdown 1: Ngành → Dropdown 2: Nhóm (lọc theo Ngành) → Dropdown 3: Loại (lọc theo Nhóm) | ✓ |
| Tree select | Hiển thị cây expandable trong 1 component | |

**User's choice:** 3 cascading dropdowns
**Notes:** Trực quan cho người không chuyên, Filament có sẵn Select với dependent options pattern.

---

## 2. Product Images

| Option | Description | Selected |
|--------|-------------|----------|
| Multiple images + local storage | Nhiều ảnh/sản phẩm, 1 ảnh đại diện + gallery, local filesystem | ✓ |
| Single image per product | 1 ảnh đại diện duy nhất | |
| Multiple images + cloud storage | Nhiều ảnh, lưu cloud (S3, Cloudinary) | |

**User's choice:** Multiple images + local storage
**Notes:** Phù hợp shared hosting, không phụ thuộc dịch vụ cloud.

**Follow-up — Image resize:**

| Option | Description | Selected |
|--------|-------------|----------|
| Keep originals | Giữ nguyên ảnh gốc, CSS resize hiển thị | |
| Auto-resize to 600x600 | Dùng Intervention Image, resize khi upload | ✓ |

**User's choice:** Auto-resize to 600x600
**Notes:** Giúp ảnh loading nhanh hơn, đồng nhất kích thước hiển thị.

---

## 3. Tech Specs Format

| Option | Description | Selected |
|--------|-------------|----------|
| Free-form KVP | Admin tự gõ tên thuộc tính + giá trị. Filament Repeater | ✓ |
| Structured per-category | Mỗi danh mục có danh sách thuộc tính cố định | |
| Free-form now, upgrade later | Free-form trước, thêm CategoryAttribute sau | |

**User's choice:** Free-form key-value pairs
**Notes:** Đơn giản, migration product_specifications đã có sẵn. Đủ dùng cho <50 sản phẩm.

---

## 4. Product Search

| Option | Description | Selected |
|--------|-------------|----------|
| MySQL LIKE, submit-based | WHERE LIKE trên tên, mã, hãng. Gõ → Enter → kết quả | ✓ |
| Laravel Scout, live search | Scout với database driver, search khi gõ | |
| LIKE + AJAX live search | LIKE query + JavaScript debounce + AJAX | |

**User's choice:** MySQL LIKE, submit-based
**Notes:** Đơn giản, không cần package thêm. Có thể nâng cấp sau nếu catalog phát triển.

---

## the agent's Discretion

- Loading skeleton / spinner design
- Pagination count per page
- Category tree expand/collapse behavior trên frontend
- Error/empty state handling
- Layout spacing và typography chi tiết

## Deferred Ideas

- Structured per-category attributes (CategoryAttribute model) — nâng cấp từ free-form KVP
- AJAX live search — nếu catalog > 100 sản phẩm
- Cloud image storage (S3) — nếu chuyển lên cloud hosting
