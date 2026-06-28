<?php

namespace Database\Seeders;

use App\Models\Post;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class PostSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $posts = [
            [
                'title' => 'Giới thiệu dòng máy soi chiếu X-Ray thế hệ mới',
                'slug' => Str::slug('Giới thiệu dòng máy soi chiếu X-Ray thế hệ mới'),
                'excerpt' => 'Chúng tôi hân hạnh giới thiệu dòng máy soi chiếu tia X thế hệ mới với công nghệ AI phát hiện vật cấm thông minh, nâng cao hiệu quả kiểm tra an ninh.',
                'content' => '<p>Trong bối cảnh nhu cầu an ninh ngày càng cao, việc đầu tư vào thiết bị soi chiếu hiện đại là yếu tố then chốt. Dòng máy soi chiếu X-Ray thế hệ mới của chúng tôi tích hợp trí tuệ nhân tạo (AI) giúp tự động phát hiện và phân loại các vật thể nguy hiểm.</p><p>Các tính năng nổi bật bao gồm khả năng phân tích hình ảnh theo thời gian thực, cảnh báo thông minh khi phát hiện vật cấm, và khả năng kết nối với hệ thống quản lý trung tâm. Đây là giải pháp tối ưu cho các sân bay, nhà ga, trung tâm thương mại và tòa nhà chính phủ.</p><p>Hãy liên hệ với chúng tôi để được tư vấn chi tiết về sản phẩm phù hợp với nhu cầu của quý khách.</p>',
                'image' => 'posts/sample-1.jpg',
                'is_published' => true,
                'published_at' => now()->subDays(3),
            ],
            [
                'title' => 'Hệ thống camera an ninh AI: Xu hướng công nghệ 2026',
                'slug' => Str::slug('Hệ thống camera an ninh AI: Xu hướng công nghệ 2026'),
                'excerpt' => 'Công nghệ AI đang thay đổi cách chúng ta tiếp cận an ninh giám sát. Tìm hiểu về các tính năng thông minh mới nhất trên hệ thống camera hiện nay.',
                'content' => '<p>Năm 2026 đánh dấu bước tiến vượt bậc của công nghệ camera an ninh với trí tuệ nhân tạo. Các tính năng như nhận dạng khuôn mặt, phát hiện hành vi bất thường, và phân tích đám đông đang trở thành tiêu chuẩn trong các hệ thống giám sát chuyên nghiệp.</p><p>Camera AI hiện nay có khả năng phân biệt giữa người, phương tiện và động vật, giảm thiểu đáng kể các cảnh báo sai. Công nghệ này đặc biệt hữu ích cho các khu công nghiệp, tòa nhà văn phòng và khu dân cư cao cấp.</p><p>Với sự phát triển của điện toán biên (edge computing), các phân tích AI được xử lý ngay trên camera, giảm tải băng thông và tăng tốc độ phản hồi.</p>',
                'image' => 'posts/sample-2.jpg',
                'is_published' => true,
                'published_at' => now()->subDays(7),
            ],
            [
                'title' => 'Giải pháp phòng cháy chữa cháy toàn diện cho tòa nhà văn phòng',
                'slug' => Str::slug('Giải pháp phòng cháy chữa cháy toàn diện cho tòa nhà văn phòng'),
                'excerpt' => 'Bài viết tổng quan về các giải pháp PCCC cho tòa nhà văn phòng từ cơ bản đến nâng cao, bao gồm hệ thống báo cháy, chữa cháy và thoát hiểm.',
                'content' => '<p>An toàn phòng cháy chữa cháy là yếu tố quan trọng hàng đầu đối với mọi tòa nhà văn phòng. Một hệ thống PCCC toàn diện bao gồm ba thành phần chính: hệ thống phát hiện (đầu báo khói, nhiệt), hệ thống thông báo (còi, đèn) và hệ thống chữa cháy (bình chữa cháy, sprinkler).</p><p>Việc lựa chọn thiết bị PCCC phù hợp phụ thuộc vào đặc thù của từng khu vực. Khu vực bếp và nhà xe nên sử dụng đầu báo nhiệt thay vì đầu báo khói để tránh báo động giả. Phòng máy tính và trung tâm dữ liệu nên trang bị bình chữa cháy CO2 để bảo vệ thiết bị điện tử.</p><p>Định kỳ kiểm tra và bảo trì hệ thống PCCC là yêu cầu bắt buộc theo quy định của pháp luật, đảm bảo hệ thống luôn sẵn sàng hoạt động khi có sự cố.</p>',
                'image' => 'posts/sample-3.jpg',
                'is_published' => true,
                'published_at' => now()->subDays(14),
            ],
            [
                'title' => '5 lưu ý khi lựa chọn thiết bị kiểm soát ra vào cho doanh nghiệp',
                'slug' => Str::slug('5 lưu ý khi lựa chọn thiết bị kiểm soát ra vào cho doanh nghiệp'),
                'excerpt' => 'Việc lựa chọn hệ thống kiểm soát ra vào phù hợp có thể ảnh hưởng lớn đến an ninh và hiệu quả vận hành của doanh nghiệp bạn.',
                'content' => '<p>Hệ thống kiểm soát ra vào không chỉ đảm bảo an ninh mà còn giúp quản lý nhân sự và khách ra vào hiệu quả. Dưới đây là 5 lưu ý quan trọng khi lựa chọn thiết bị:</p><p><strong>1. Xác định nhu cầu bảo mật:</strong> Đánh giá mức độ an ninh yêu cầu cho từng khu vực. Khu vực công cộng có thể chỉ cần đầu đọc thẻ cơ bản, trong khi khu vực nhạy cảm cần xác thực đa yếu tố.</p><p><strong>2. Khả năng mở rộng:</strong> Chọn hệ thống có khả năng mở rộng khi doanh nghiệp phát triển thêm quy mô và số lượng nhân viên.</p><p><strong>3. Tích hợp với hệ thống hiện có:</strong> Đảm bảo thiết bị kiểm soát ra vào có thể tích hợp với camera an ninh và hệ thống quản lý tòa nhà.</p><p><strong>4. Độ bền và môi trường lắp đặt:</strong> Thiết bị lắp ngoài trời cần có chuẩn chống nước IP65 trở lên và chịu được nhiệt độ khắc nghiệt.</p><p><strong>5. Hỗ trợ kỹ thuật và bảo hành:</strong> Lựa chọn nhà cung cấp có dịch vụ hỗ trợ kỹ thuật tốt và chế độ bảo hành rõ ràng.</p>',
                'image' => 'posts/sample-4.jpg',
                'is_published' => true,
                'published_at' => now()->subDays(21),
            ],
            [
                'title' => 'Bảo trì hệ thống camera giám sát: Những điều cần biết',
                'slug' => Str::slug('Bảo trì hệ thống camera giám sát: Những điều cần biết'),
                'excerpt' => 'Bảo trì định kỳ giúp hệ thống camera giám sát hoạt động ổn định và kéo dài tuổi thọ thiết bị. Tìm hiểu các bước bảo trì cơ bản.',
                'content' => '<p>Hệ thống camera giám sát cần được bảo trì định kỳ để đảm bảo hoạt động ổn định và chất lượng hình ảnh tốt nhất. Dưới đây là các hạng mục bảo trì quan trọng:</p><p><strong>Vệ sinh ống kính:</strong> Bụi bẩn trên ống kính là nguyên nhân phổ biến khiến hình ảnh mờ, đặc biệt với camera ngoài trời. Nên vệ sinh ống kính ít nhất 3 tháng/lần.</p><p><strong>Kiểm tra kết nối mạng:</strong> Đảm bảo tất cả camera đều kết nối ổn định với đầu ghi hoặc hệ thống quản lý trung tâm. Kiểm tra cáp mạng và nguồn điện định kỳ.</p><p><strong>Cập nhật firmware:</strong> Các nhà sản xuất thường xuyên phát hành bản cập nhật firmware để vá lỗi bảo mật và cải thiện hiệu năng. Luôn cập nhật firmware mới nhất cho thiết bị.</p><p><strong>Kiểm tra lưu trữ:</strong> Đảm bảo ổ cứng đầu ghi còn dung lượng và hoạt động tốt. Nên kiểm tra và sao lưu dữ liệu quan trọng định kỳ.</p>',
                'image' => 'posts/sample-5.jpg',
                'is_published' => true,
                'published_at' => now()->subDays(30),
            ],
        ];

        foreach ($posts as $post) {
            Post::create($post);
        }
    }
}
