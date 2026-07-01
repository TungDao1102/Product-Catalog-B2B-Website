# Hướng dẫn Quản trị Hệ thống

## Đăng nhập

1. Truy cập `https://yourdomain.com/admin`
2. Đăng nhập bằng email và mật khẩu quản trị viên

## Quản lý Danh mục Sản phẩm

**Vào:** Admin → Danh mục

- **Thêm mới:** Nhấn "Tạo mới" → nhập tên, chọn danh mục cha (nếu có), tải ảnh đại diện
- **Chỉnh sửa:** Nhấn vào tên danh mục → sửa thông tin
- **Xóa:** Nhấn icon thùng rác (chỉ xóa được nếu không có sản phẩm con)

> Danh mục hỗ trợ 3 cấp: Cấp 1 (VD: Camera) → Cấp 2 (VD: Camera IP) → Cấp 3 (VD: Camera IP ngoài trời)

## Quản lý Hãng sản xuất

**Vào:** Admin → Hãng

- Thêm hãng: Tên, logo, website
- Hỗ trợ song ngữ Việt-Anh

## Quản lý Sản phẩm

**Vào:** Admin → Sản phẩm

- **Thêm mới:** Nhấn "Tạo mới"
  - Nhập tên (Việt + Anh), mã SP, chọn danh mục + hãng
  - Mô tả ngắn (Việt + Anh)
  - Nội dung chi tiết (Rich Editor, hỗ trợ song ngữ)
  - Hình ảnh: chọn nhiều ảnh (ảnh đầu tiên là ảnh đại diện)
  - Thông số kỹ thuật: nhấn "Thêm" để thêm từng thông số (key-value)
  - File brochure: tải lên PDF
  - Đánh dấu "Nổi bật" nếu muốn hiển thị trên trang chủ

## Quản lý Tin tức

**Vào:** Admin → Tin tức

- Viết bài với Rich Editor (hỗ trợ hình ảnh, định dạng văn bản)
- Hỗ trợ song ngữ Việt-Anh
- Chỉ bài viết có trạng thái "Đã xuất bản" mới hiển thị ngoài frontend

## Quản lý Dự án

**Vào:** Admin → Dự án

- Thêm dự án với hình ảnh gallery (nhiều ảnh)
- Hỗ trợ song ngữ Việt-Anh

## Xem Yêu cầu báo giá

**Vào:** Admin → Yêu cầu báo giá

- Danh sách yêu cầu từ khách hàng
- Click vào để xem chi tiết (tự động đánh dấu "Đã đọc")
- Có thể xóa yêu cầu khi đã xử lý

## Xem Liên hệ

**Vào:** Admin → Liên hệ

- Danh sách liên hệ từ khách hàng
- Click vào để xem chi tiết (tự động đánh dấu "Đã đọc")

## Quản lý Trang Giới thiệu

**Vào:** Admin → Giới thiệu

- Chỉnh sửa nội dung trang Giới thiệu (song ngữ)
- Các mục: Nội dung giới thiệu, Sứ mệnh, Tầm nhìn, Giá trị cốt lõi, Lịch sử
- Toggle "Hiển thị" để bật/tắt trang

## Quản lý Người dùng

**Vào:** Admin → Users

- Thêm/Sửa/Xóa tài khoản quản trị viên

## Đa ngôn ngữ

Hệ thống hỗ trợ **Tiếng Việt** và **Tiếng Anh**:

- **Admin:** Tab chuyển ngữ ở đầu form nhập liệu (chỉ áp dụng cho các trường có biểu tượng globe)
- **Frontend:** Bộ chuyển ngữ ở góc phải navbar

## Cache

Khi cập nhật nội dung, nếu thay đổi chưa hiển thị ngay:

```bash
# Xóa toàn bộ cache
php artisan optimize:clear
# Hoặc chỉ xóa cache cụ thể
php artisan cache:clear
```

## Sitemap

Sitemap tự động cập nhật qua cron job hàng ngày.
Để tạo lại thủ công:

```bash
php artisan sitemap:generate
```

## Email

Hệ thống gửi email khi:
1. Khách hàng gửi yêu cầu báo giá → thông báo admin + xác nhận khách hàng
2. Khách hàng gửi liên hệ → thông báo admin + xác nhận khách hàng

Nếu email không gửi được, kiểm tra cấu hình MAIL_* trong file `.env`.
