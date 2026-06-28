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
                'title' => ['vi' => 'Giới thiệu dòng máy soi chiếu X-Ray thế hệ mới', 'en' => 'Introducing the New Generation X-Ray Scanner Line'],
                'slug' => Str::slug('Giới thiệu dòng máy soi chiếu X-Ray thế hệ mới'),
                'excerpt' => [
                    'vi' => 'Chúng tôi hân hạnh giới thiệu dòng máy soi chiếu tia X thế hệ mới với công nghệ AI phát hiện vật cấm thông minh, nâng cao hiệu quả kiểm tra an ninh.',
                    'en' => 'We are pleased to introduce our new generation X-ray scanner line with AI-powered prohibited item detection technology, enhancing security screening efficiency.',
                ],
                'content' => [
                    'vi' => '<p>Trong bối cảnh nhu cầu an ninh ngày càng cao, việc đầu tư vào thiết bị soi chiếu hiện đại là yếu tố then chốt. Dòng máy soi chiếu X-Ray thế hệ mới của chúng tôi tích hợp trí tuệ nhân tạo (AI) giúp tự động phát hiện và phân loại các vật thể nguy hiểm.</p><p>Các tính năng nổi bật bao gồm khả năng phân tích hình ảnh theo thời gian thực, cảnh báo thông minh khi phát hiện vật cấm, và khả năng kết nối với hệ thống quản lý trung tâm. Đây là giải pháp tối ưu cho các sân bay, nhà ga, trung tâm thương mại và tòa nhà chính phủ.</p><p>Hãy liên hệ với chúng tôi để được tư vấn chi tiết về sản phẩm phù hợp với nhu cầu của quý khách.</p>',
                    'en' => '<p>As security demands continue to rise, investing in modern screening equipment is a key factor. Our new generation X-ray scanner line integrates artificial intelligence (AI) to automatically detect and classify dangerous objects.</p><p>Key features include real-time image analysis, intelligent alerts when prohibited items are detected, and connectivity with central management systems. This is the optimal solution for airports, train stations, shopping centers and government buildings.</p><p>Contact us for detailed consultation on products that suit your needs.</p>',
                ],
                'image' => 'posts/sample-1.jpg',
                'is_published' => true,
                'published_at' => now()->subDays(3),
            ],
            [
                'title' => ['vi' => 'Hệ thống camera an ninh AI: Xu hướng công nghệ 2026', 'en' => 'AI Security Camera Systems: Technology Trends 2026'],
                'slug' => Str::slug('Hệ thống camera an ninh AI: Xu hướng công nghệ 2026'),
                'excerpt' => [
                    'vi' => 'Công nghệ AI đang thay đổi cách chúng ta tiếp cận an ninh giám sát. Tìm hiểu về các tính năng thông minh mới nhất trên hệ thống camera hiện nay.',
                    'en' => 'AI technology is transforming how we approach security surveillance. Learn about the latest smart features in today\'s camera systems.',
                ],
                'content' => [
                    'vi' => '<p>Năm 2026 đánh dấu bước tiến vượt bậc của công nghệ camera an ninh với trí tuệ nhân tạo. Các tính năng như nhận dạng khuôn mặt, phát hiện hành vi bất thường, và phân tích đám đông đang trở thành tiêu chuẩn trong các hệ thống giám sát chuyên nghiệp.</p><p>Camera AI hiện nay có khả năng phân biệt giữa người, phương tiện và động vật, giảm thiểu đáng kể các cảnh báo sai. Công nghệ này đặc biệt hữu ích cho các khu công nghiệp, tòa nhà văn phòng và khu dân cư cao cấp.</p><p>Với sự phát triển của điện toán biên (edge computing), các phân tích AI được xử lý ngay trên camera, giảm tải băng thông và tăng tốc độ phản hồi.</p>',
                    'en' => '<p>2026 marks a significant leap forward in AI-powered security camera technology. Features such as facial recognition, abnormal behavior detection, and crowd analysis are becoming standard in professional surveillance systems.</p><p>AI cameras today can distinguish between people, vehicles and animals, significantly reducing false alarms. This technology is especially useful for industrial zones, office buildings and high-end residential areas.</p><p>With the advancement of edge computing, AI analytics are processed directly on the camera, reducing bandwidth and increasing response speed.</p>',
                ],
                'image' => 'posts/sample-2.jpg',
                'is_published' => true,
                'published_at' => now()->subDays(7),
            ],
            [
                'title' => ['vi' => 'Giải pháp phòng cháy chữa cháy toàn diện cho tòa nhà văn phòng', 'en' => 'Comprehensive Fire Protection Solutions for Office Buildings'],
                'slug' => Str::slug('Giải pháp phòng cháy chữa cháy toàn diện cho tòa nhà văn phòng'),
                'excerpt' => [
                    'vi' => 'Bài viết tổng quan về các giải pháp PCCC cho tòa nhà văn phòng từ cơ bản đến nâng cao, bao gồm hệ thống báo cháy, chữa cháy và thoát hiểm.',
                    'en' => 'An overview of fire protection solutions for office buildings from basic to advanced, including alarm systems, suppression and emergency evacuation.',
                ],
                'content' => [
                    'vi' => '<p>An toàn phòng cháy chữa cháy là yếu tố quan trọng hàng đầu đối với mọi tòa nhà văn phòng. Một hệ thống PCCC toàn diện bao gồm ba thành phần chính: hệ thống phát hiện (đầu báo khói, nhiệt), hệ thống thông báo (còi, đèn) và hệ thống chữa cháy (bình chữa cháy, sprinkler).</p><p>Việc lựa chọn thiết bị PCCC phù hợp phụ thuộc vào đặc thù của từng khu vực. Khu vực bếp và nhà xe nên sử dụng đầu báo nhiệt thay vì đầu báo khói để tránh báo động giả. Phòng máy tính và trung tâm dữ liệu nên trang bị bình chữa cháy CO2 để bảo vệ thiết bị điện tử.</p><p>Định kỳ kiểm tra và bảo trì hệ thống PCCC là yêu cầu bắt buộc theo quy định của pháp luật, đảm bảo hệ thống luôn sẵn sàng hoạt động khi có sự cố.</p>',
                    'en' => '<p>Fire safety is a top priority for every office building. A comprehensive fire protection system includes three main components: detection (smoke and heat detectors), notification (sirens, strobes) and suppression (fire extinguishers, sprinklers).</p><p>Choosing the right fire protection equipment depends on each area\'s characteristics. Kitchens and garages should use heat detectors instead of smoke detectors to avoid false alarms. Computer rooms and data centers should be equipped with CO2 extinguishers to protect electronic equipment.</p><p>Regular inspection and maintenance of fire protection systems is a legal requirement, ensuring the system is always ready to operate during emergencies.</p>',
                ],
                'image' => 'posts/sample-3.jpg',
                'is_published' => true,
                'published_at' => now()->subDays(14),
            ],
            [
                'title' => ['vi' => '5 lưu ý khi lựa chọn thiết bị kiểm soát ra vào cho doanh nghiệp', 'en' => '5 Key Considerations When Choosing Access Control Equipment for Your Business'],
                'slug' => Str::slug('5 lưu ý khi lựa chọn thiết bị kiểm soát ra vào cho doanh nghiệp'),
                'excerpt' => [
                    'vi' => 'Việc lựa chọn hệ thống kiểm soát ra vào phù hợp có thể ảnh hưởng lớn đến an ninh và hiệu quả vận hành của doanh nghiệp bạn.',
                    'en' => 'Choosing the right access control system can significantly impact your business security and operational efficiency.',
                ],
                'content' => [
                    'vi' => '<p>Hệ thống kiểm soát ra vào không chỉ đảm bảo an ninh mà còn giúp quản lý nhân sự và khách ra vào hiệu quả. Dưới đây là 5 lưu ý quan trọng khi lựa chọn thiết bị:</p><p><strong>1. Xác định nhu cầu bảo mật:</strong> Đánh giá mức độ an ninh yêu cầu cho từng khu vực. Khu vực công cộng có thể chỉ cần đầu đọc thẻ cơ bản, trong khi khu vực nhạy cảm cần xác thực đa yếu tố.</p><p><strong>2. Khả năng mở rộng:</strong> Chọn hệ thống có khả năng mở rộng khi doanh nghiệp phát triển thêm quy mô và số lượng nhân viên.</p><p><strong>3. Tích hợp với hệ thống hiện có:</strong> Đảm bảo thiết bị kiểm soát ra vào có thể tích hợp với camera an ninh và hệ thống quản lý tòa nhà.</p><p><strong>4. Độ bền và môi trường lắp đặt:</strong> Thiết bị lắp ngoài trời cần có chuẩn chống nước IP65 trở lên và chịu được nhiệt độ khắc nghiệt.</p><p><strong>5. Hỗ trợ kỹ thuật và bảo hành:</strong> Lựa chọn nhà cung cấp có dịch vụ hỗ trợ kỹ thuật tốt và chế độ bảo hành rõ ràng.</p>',
                    'en' => '<p>An access control system not only ensures security but also helps manage personnel and visitors efficiently. Here are 5 key considerations when choosing equipment:</p><p><strong>1. Assess security requirements:</strong> Evaluate the required security level for each area. Public areas may only need basic card readers, while sensitive areas require multi-factor authentication.</p><p><strong>2. Scalability:</strong> Choose a system that can scale as your business grows in size and employee count.</p><p><strong>3. Integration with existing systems:</strong> Ensure access control equipment can integrate with security cameras and building management systems.</p><p><strong>4. Durability and installation environment:</strong> Outdoor equipment should have IP65 or higher water resistance rating and withstand extreme temperatures.</p><p><strong>5. Technical support and warranty:</strong> Select a supplier with good technical support services and clear warranty terms.</p>',
                ],
                'image' => 'posts/sample-4.jpg',
                'is_published' => true,
                'published_at' => now()->subDays(21),
            ],
            [
                'title' => ['vi' => 'Bảo trì hệ thống camera giám sát: Những điều cần biết', 'en' => 'Surveillance Camera Maintenance: What You Need to Know'],
                'slug' => Str::slug('Bảo trì hệ thống camera giám sát: Những điều cần biết'),
                'excerpt' => [
                    'vi' => 'Bảo trì định kỳ giúp hệ thống camera giám sát hoạt động ổn định và kéo dài tuổi thọ thiết bị. Tìm hiểu các bước bảo trì cơ bản.',
                    'en' => 'Regular maintenance helps your surveillance camera system operate stably and extends equipment lifespan. Learn the basic maintenance steps.',
                ],
                'content' => [
                    'vi' => '<p>Hệ thống camera giám sát cần được bảo trì định kỳ để đảm bảo hoạt động ổn định và chất lượng hình ảnh tốt nhất. Dưới đây là các hạng mục bảo trì quan trọng:</p><p><strong>Vệ sinh ống kính:</strong> Bụi bẩn trên ống kính là nguyên nhân phổ biến khiến hình ảnh mờ, đặc biệt với camera ngoài trời. Nên vệ sinh ống kính ít nhất 3 tháng/lần.</p><p><strong>Kiểm tra kết nối mạng:</strong> Đảm bảo tất cả camera đều kết nối ổn định với đầu ghi hoặc hệ thống quản lý trung tâm. Kiểm tra cáp mạng và nguồn điện định kỳ.</p><p><strong>Cập nhật firmware:</strong> Các nhà sản xuất thường xuyên phát hành bản cập nhật firmware để vá lỗi bảo mật và cải thiện hiệu năng. Luôn cập nhật firmware mới nhất cho thiết bị.</p><p><strong>Kiểm tra lưu trữ:</strong> Đảm bảo ổ cứng đầu ghi còn dung lượng và hoạt động tốt. Nên kiểm tra và sao lưu dữ liệu quan trọng định kỳ.</p>',
                    'en' => '<p>Surveillance camera systems require regular maintenance to ensure stable operation and optimal image quality. Here are the key maintenance items:</p><p><strong>Lens cleaning:</strong> Dust on the lens is a common cause of blurry images, especially for outdoor cameras. Clean lenses at least every 3 months.</p><p><strong>Network connection check:</strong> Ensure all cameras are stably connected to the recorder or central management system. Check network cables and power supply regularly.</p><p><strong>Firmware updates:</strong> Manufacturers regularly release firmware updates to patch security vulnerabilities and improve performance. Always keep devices updated with the latest firmware.</p><p><strong>Storage check:</strong> Ensure recorder hard drives have sufficient capacity and are functioning properly. Regularly check and back up important data.</p>',
                ],
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
