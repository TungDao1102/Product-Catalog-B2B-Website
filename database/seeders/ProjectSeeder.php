<?php

namespace Database\Seeders;

use App\Models\Project;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProjectSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $projects = [
            [
                'title' => 'Lắp đặt hệ thống an ninh cho Tòa nhà Văn phòng ABC',
                'slug' => Str::slug('Lắp đặt hệ thống an ninh cho Tòa nhà Văn phòng ABC'),
                'description' => 'Cung cấp và lắp đặt toàn bộ hệ thống camera an ninh, kiểm soát ra vào cho tòa nhà văn phòng 15 tầng tại Hà Nội.',
                'content' => '<p>Dự án bao gồm lắp đặt 120 camera IP Hikvision 4MP trên toàn bộ các tầng, khu vực sảnh, thang máy và bãi đỗ xe. Hệ thống kiểm soát ra vào với 20 đầu đọc thẻ RFID Bosch tại các cửa ra vào chính và khu vực hạn chế.</p><p>Hệ thống được quản lý tập trung qua phần mềm quản lý giám sát, cho phép bảo vệ theo dõi toàn bộ tòa nhà từ một trung tâm điều khiển duy nhất. Dữ liệu được lưu trữ trong 30 ngày với hệ thống lưu trữ mạng NAS dự phòng.</p><p>Thời gian thực hiện: 45 ngày. Dự án hoàn thành đúng tiến độ và đạt nghiệm thu với chất lượng cao.</p>',
                'images' => ['projects/sample-1.jpg', 'projects/sample-2.jpg', 'projects/sample-3.jpg'],
                'is_active' => true,
            ],
            [
                'title' => 'Hệ thống PCCC cho Trung tâm Thương mại XYZ',
                'slug' => Str::slug('Hệ thống PCCC cho Trung tâm Thương mại XYZ'),
                'description' => 'Thiết kế, cung cấp và lắp đặt hệ thống báo cháy và chữa cháy tự động cho trung tâm thương mại quy mô lớn tại TP. Hồ Chí Minh.',
                'content' => '<p>Dự án PCCC cho trung tâm thương mại XYZ với tổng diện tích 50.000m² bao gồm 4 tầng thương mại và 2 tầng hầm. Hệ thống bao gồm hơn 500 đầu báo khói Honeywell, 200 đầu báo nhiệt, 50 tủ trung tâm báo cháy và hệ thống chữa cháy sprinkler tự động.</p><p>Hệ thống được thiết kế theo tiêu chuẩn NFPA và TCVN về phòng cháy chữa cháy. Tích hợp với hệ thống thông gió, thang máy và cửa thoát hiểm để tự động kích hoạt các biện pháp an toàn khi có sự cố.</p><p>Chúng tôi đã thực hiện đào tạo vận hành cho đội PCCC cơ sở và cung cấp đầy đủ hồ sơ hoàn công, bản vẽ as-built.</p>',
                'images' => ['projects/sample-4.jpg', 'projects/sample-5.jpg'],
                'is_active' => true,
            ],
            [
                'title' => 'Giải pháp soi chiếu an ninh cho Sân bay Quốc tế Đà Nẵng',
                'slug' => Str::slug('Giải pháp soi chiếu an ninh cho Sân bay Quốc tế Đà Nẵng'),
                'description' => 'Cung cấp và lắp đặt hệ thống máy soi chiếu X-Ray và cửa dò kim loại tại nhà ga quốc tế sân bay Đà Nẵng.',
                'content' => '<p>Dự án nâng cấp hệ thống an ninh cho nhà ga quốc tế sân bay Đà Nẵng với 10 máy soi chiếu X-Ray trường bay Bosch, 20 cửa dò kim loại 6 vùng và hệ thống camera an ninh đồng bộ.</p><p>Hệ thống máy soi chiếu được trang bị công nghệ AI phát hiện vật cấm tự động, giúp nhân viên an ninh làm việc hiệu quả hơn. Các máy được kết nối mạng để giám sát và quản lý tập trung từ phòng điều khiển an ninh.</p><p>Dự án được thực hiện trong 60 ngày, đảm bảo không gián đoạn hoạt động khai thác của sân bay.</p>',
                'images' => ['projects/sample-6.jpg', 'projects/sample-7.jpg', 'projects/sample-8.jpg'],
                'is_active' => true,
            ],
            [
                'title' => 'Hệ thống kiểm soát ra vào cho Khu Công nghiệp Thăng Long',
                'slug' => Str::slug('Hệ thống kiểm soát ra vào cho Khu Công nghiệp Thăng Long'),
                'description' => 'Lắp đặt hệ thống kiểm soát ra vào tích hợp camera nhận dạng biển số cho 5 cổng chính của khu công nghiệp.',
                'content' => '<p>Dự án kiểm soát ra vào cho Khu Công nghiệp Thăng Long với 5 cổng chính, mỗi cổng được trang bị barrier tự động, đầu đọc thẻ RFID, camera nhận dạng biển số và hệ thống đèn tín hiệu.</p><p>Hệ thống cho phép quản lý hơn 10.000 lượt xe ra vào mỗi ngày với thời gian xử lý dưới 5 giây mỗi lượt. Nhân viên và xe được cấp thẻ RFID, khách vãng lai được đăng ký tại bảo vệ và cấp thẻ tạm.</p><p>Phần mềm quản lý tích hợp cho phép tra cứu lịch sử ra vào, báo cáo thống kê và cảnh báo khi có phương tiện lạ xâm nhập. Hệ thống vận hành ổn định từ khi bàn giao.</p>',
                'images' => ['projects/sample-9.jpg', 'projects/sample-10.jpg'],
                'is_active' => true,
            ],
            [
                'title' => 'Trang bị thiết bị PCCC cho Bệnh viện Đa khoa Quốc tế',
                'slug' => Str::slug('Trang bị thiết bị PCCC cho Bệnh viện Đa khoa Quốc tế'),
                'description' => 'Cung cấp và lắp đặt thiết bị báo cháy, chữa cháy cho bệnh viện đa khoa 500 giường tại Hà Nội.',
                'content' => '<p>Bệnh viện Đa khoa Quốc tế được trang bị hệ thống PCCC hiện đại với các thiết bị đạt tiêu chuẩn y tế. Hệ thống bao gồm đầu báo khói, đầu báo nhiệt tại tất cả các khu vực bao gồm phòng bệnh, phòng mổ, khoa dược và khu vực hành chính.</p><p>Bình chữa cháy CO2 được bố trí tại các khu vực có thiết bị điện tử nhạy cảm như phòng MRI, phòng CT và trung tâm dữ liệu. Bình chữa cháy bột ABC được bố trí tại các khu vực thông thường và hành lang.</p><p>Đặc biệt, hệ thống báo cháy được tích hợp với hệ thống quản lý tòa nhà (BMS) để tự động điều phối thang máy, cửa thoát hiểm và hệ thống thông gió khi có sự cố. Dự án đã được nghiệm thu và đưa vào sử dụng.</p>',
                'images' => ['projects/sample-11.jpg', 'projects/sample-12.jpg', 'projects/sample-13.jpg'],
                'is_active' => true,
            ],
        ];

        foreach ($projects as $project) {
            Project::create($project);
        }
    }
}
