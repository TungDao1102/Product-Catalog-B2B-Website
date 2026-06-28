<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; margin: 0; padding: 0; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: #198754; color: #fff; padding: 20px; text-align: center; border-radius: 6px 6px 0 0; }
        .header h1 { margin: 0; font-size: 20px; }
        .body { padding: 20px; background: #f9f9f9; border: 1px solid #ddd; border-top: none; border-radius: 0 0 6px 6px; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        table td { padding: 10px 8px; border-bottom: 1px solid #eee; vertical-align: top; }
        table td:first-child { font-weight: bold; width: 120px; color: #555; }
        .message-box { background: #fff; padding: 15px; border-radius: 4px; border: 1px solid #ddd; margin-top: 15px; }
        .footer { margin-top: 20px; color: #999; font-size: 12px; text-align: center; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>{{ $type === 'inquiry' ? 'Yêu cầu báo giá mới' : 'Liên hệ mới' }}</h1>
        </div>
        <div class="body">
            <table>
                <tr>
                    <td>Họ tên:</td>
                    <td>{{ $submission->name }}</td>
                </tr>
                <tr>
                    <td>Email:</td>
                    <td>{{ $submission->email }}</td>
                </tr>
                <tr>
                    <td>Điện thoại:</td>
                    <td>{{ $submission->phone ?? '—' }}</td>
                </tr>
                <tr>
                    <td>Công ty:</td>
                    <td>{{ $submission->company ?? '—' }}</td>
                </tr>
                @if ($type === 'inquiry')
                <tr>
                    <td>Sản phẩm:</td>
                    <td>{{ $submission->product->name ?? '—' }}</td>
                </tr>
                <tr>
                    <td>Số lượng:</td>
                    <td>{{ $submission->quantity ?? '—' }}</td>
                </tr>
                @endif
            </table>

            <div class="message-box">
                <strong>Nội dung:</strong>
                <p style="margin-top: 8px;">{{ $submission->message }}</p>
            </div>
        </div>
        <div class="footer">
            <p>Email này được gửi tự động từ hệ thống quản lý website.</p>
        </div>
    </div>
</body>
</html>
