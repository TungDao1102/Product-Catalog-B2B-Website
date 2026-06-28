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
                'title' => ['vi' => 'Lắp đặt hệ thống an ninh cho Tòa nhà Văn phòng ABC', 'en' => 'Security System Installation for ABC Office Building'],
                'slug' => Str::slug('Lắp đặt hệ thống an ninh cho Tòa nhà Văn phòng ABC'),
                'description' => [
                    'vi' => 'Cung cấp và lắp đặt toàn bộ hệ thống camera an ninh, kiểm soát ra vào cho tòa nhà văn phòng 15 tầng tại Hà Nội.',
                    'en' => 'Supply and installation of a complete security camera and access control system for a 15-story office building in Hanoi.',
                ],
                'content' => [
                    'vi' => '<p>Dự án bao gồm lắp đặt 120 camera IP Hikvision 4MP trên toàn bộ các tầng, khu vực sảnh, thang máy và bãi đỗ xe. Hệ thống kiểm soát ra vào với 20 đầu đọc thẻ RFID Bosch tại các cửa ra vào chính và khu vực hạn chế.</p><p>Hệ thống được quản lý tập trung qua phần mềm quản lý giám sát, cho phép bảo vệ theo dõi toàn bộ tòa nhà từ một trung tâm điều khiển duy nhất. Dữ liệu được lưu trữ trong 30 ngày với hệ thống lưu trữ mạng NAS dự phòng.</p><p>Thời gian thực hiện: 45 ngày. Dự án hoàn thành đúng tiến độ và đạt nghiệm thu với chất lượng cao.</p>',
                    'en' => '<p>The project includes installation of 120 Hikvision 4MP IP cameras across all floors, lobby areas, elevators and parking lots. Access control system with 20 Bosch RFID card readers at main entrances and restricted areas.</p><p>The system is centrally managed through surveillance management software, allowing security personnel to monitor the entire building from a single control center. Data is stored for 30 days with a redundant NAS storage system.</p><p>Project duration: 45 days. Completed on schedule with high-quality acceptance.</p>',
                ],
                'images' => ['projects/sample-1.jpg', 'projects/sample-2.jpg', 'projects/sample-3.jpg'],
                'is_active' => true,
            ],
            [
                'title' => ['vi' => 'Hệ thống PCCC cho Trung tâm Thương mại XYZ', 'en' => 'Fire Protection System for XYZ Shopping Center'],
                'slug' => Str::slug('Hệ thống PCCC cho Trung tâm Thương mại XYZ'),
                'description' => [
                    'vi' => 'Thiết kế, cung cấp và lắp đặt hệ thống báo cháy và chữa cháy tự động cho trung tâm thương mại quy mô lớn tại TP. Hồ Chí Minh.',
                    'en' => 'Design, supply and installation of automatic fire alarm and suppression systems for a large-scale shopping center in Ho Chi Minh City.',
                ],
                'content' => [
                    'vi' => '<p>Dự án PCCC cho trung tâm thương mại XYZ với tổng diện tích 50.000m² bao gồm 4 tầng thương mại và 2 tầng hầm. Hệ thống bao gồm hơn 500 đầu báo khói Honeywell, 200 đầu báo nhiệt, 50 tủ trung tâm báo cháy và hệ thống chữa cháy sprinkler tự động.</p><p>Hệ thống được thiết kế theo tiêu chuẩn NFPA và TCVN về phòng cháy chữa cháy. Tích hợp với hệ thống thông gió, thang máy và cửa thoát hiểm để tự động kích hoạt các biện pháp an toàn khi có sự cố.</p><p>Chúng tôi đã thực hiện đào tạo vận hành cho đội PCCC cơ sở và cung cấp đầy đủ hồ sơ hoàn công, bản vẽ as-built.</p>',
                    'en' => '<p>The fire protection project for XYZ shopping center covers a total area of 50,000m² including 4 retail floors and 2 basement levels. The system includes over 500 Honeywell smoke detectors, 200 heat detectors, 50 fire alarm panels and an automatic sprinkler suppression system.</p><p>The system is designed to NFPA and TCVN fire protection standards. It integrates with ventilation, elevator and emergency exit systems to automatically activate safety measures during incidents.</p><p>We conducted operational training for the facility\'s fire safety team and provided complete as-built documentation.</p>',
                ],
                'images' => ['projects/sample-4.jpg', 'projects/sample-5.jpg'],
                'is_active' => true,
            ],
            [
                'title' => ['vi' => 'Giải pháp soi chiếu an ninh cho Sân bay Quốc tế Đà Nẵng', 'en' => 'Security Screening Solution for Da Nang International Airport'],
                'slug' => Str::slug('Giải pháp soi chiếu an ninh cho Sân bay Quốc tế Đà Nẵng'),
                'description' => [
                    'vi' => 'Cung cấp và lắp đặt hệ thống máy soi chiếu X-Ray và cửa dò kim loại tại nhà ga quốc tế sân bay Đà Nẵng.',
                    'en' => 'Supply and installation of X-ray screening machines and metal detector gates at Da Nang International Airport terminal.',
                ],
                'content' => [
                    'vi' => '<p>Dự án nâng cấp hệ thống an ninh cho nhà ga quốc tế sân bay Đà Nẵng với 10 máy soi chiếu X-Ray trường bay Bosch, 20 cửa dò kim loại 6 vùng và hệ thống camera an ninh đồng bộ.</p><p>Hệ thống máy soi chiếu được trang bị công nghệ AI phát hiện vật cấm tự động, giúp nhân viên an ninh làm việc hiệu quả hơn. Các máy được kết nối mạng để giám sát và quản lý tập trung từ phòng điều khiển an ninh.</p><p>Dự án được thực hiện trong 60 ngày, đảm bảo không gián đoạn hoạt động khai thác của sân bay.</p>',
                    'en' => '<p>The security system upgrade project for Da Nang International Airport\'s international terminal includes 10 Bosch airport-grade X-ray screening machines, 20 six-zone walk-through metal detectors and a synchronized security camera system.</p><p>The screening machines are equipped with AI-powered automatic prohibited item detection, enabling security personnel to work more efficiently. All machines are network-connected for centralized monitoring and management from the security control room.</p><p>The project was completed in 60 days, ensuring no disruption to airport operations.</p>',
                ],
                'images' => ['projects/sample-6.jpg', 'projects/sample-7.jpg', 'projects/sample-8.jpg'],
                'is_active' => true,
            ],
            [
                'title' => ['vi' => 'Hệ thống kiểm soát ra vào cho Khu Công nghiệp Thăng Long', 'en' => 'Access Control System for Thang Long Industrial Park'],
                'slug' => Str::slug('Hệ thống kiểm soát ra vào cho Khu Công nghiệp Thăng Long'),
                'description' => [
                    'vi' => 'Lắp đặt hệ thống kiểm soát ra vào tích hợp camera nhận dạng biển số cho 5 cổng chính của khu công nghiệp.',
                    'en' => 'Installation of access control system with integrated license plate recognition cameras for 5 main gates of the industrial park.',
                ],
                'content' => [
                    'vi' => '<p>Dự án kiểm soát ra vào cho Khu Công nghiệp Thăng Long với 5 cổng chính, mỗi cổng được trang bị barrier tự động, đầu đọc thẻ RFID, camera nhận dạng biển số và hệ thống đèn tín hiệu.</p><p>Hệ thống cho phép quản lý hơn 10.000 lượt xe ra vào mỗi ngày với thời gian xử lý dưới 5 giây mỗi lượt. Nhân viên và xe được cấp thẻ RFID, khách vãng lai được đăng ký tại bảo vệ và cấp thẻ tạm.</p><p>Phần mềm quản lý tích hợp cho phép tra cứu lịch sử ra vào, báo cáo thống kê và cảnh báo khi có phương tiện lạ xâm nhập. Hệ thống vận hành ổn định từ khi bàn giao.</p>',
                    'en' => '<p>The access control project for Thang Long Industrial Park covers 5 main gates, each equipped with automatic barriers, RFID card readers, license plate recognition cameras and signal light systems.</p><p>The system manages over 10,000 vehicle entries per day with processing time under 5 seconds per vehicle. Employees and registered vehicles receive RFID cards, while visitors register at security and receive temporary passes.</p><p>The integrated management software allows entry/exit history lookup, statistical reports and alerts for unauthorized vehicle intrusion. The system has been operating stably since handover.</p>',
                ],
                'images' => ['projects/sample-9.jpg', 'projects/sample-10.jpg'],
                'is_active' => true,
            ],
            [
                'title' => ['vi' => 'Trang bị thiết bị PCCC cho Bệnh viện Đa khoa Quốc tế', 'en' => 'Fire Protection Equipment for International General Hospital'],
                'slug' => Str::slug('Trang bị thiết bị PCCC cho Bệnh viện Đa khoa Quốc tế'),
                'description' => [
                    'vi' => 'Cung cấp và lắp đặt thiết bị báo cháy, chữa cháy cho bệnh viện đa khoa 500 giường tại Hà Nội.',
                    'en' => 'Supply and installation of fire alarm and suppression equipment for a 500-bed general hospital in Hanoi.',
                ],
                'content' => [
                    'vi' => '<p>Bệnh viện Đa khoa Quốc tế được trang bị hệ thống PCCC hiện đại với các thiết bị đạt tiêu chuẩn y tế. Hệ thống bao gồm đầu báo khói, đầu báo nhiệt tại tất cả các khu vực bao gồm phòng bệnh, phòng mổ, khoa dược và khu vực hành chính.</p><p>Bình chữa cháy CO2 được bố trí tại các khu vực có thiết bị điện tử nhạy cảm như phòng MRI, phòng CT và trung tâm dữ liệu. Bình chữa cháy bột ABC được bố trí tại các khu vực thông thường và hành lang.</p><p>Đặc biệt, hệ thống báo cháy được tích hợp với hệ thống quản lý tòa nhà (BMS) để tự động điều phối thang máy, cửa thoát hiểm và hệ thống thông gió khi có sự cố. Dự án đã được nghiệm thu và đưa vào sử dụng.</p>',
                    'en' => '<p>The International General Hospital is equipped with a modern fire protection system meeting medical standards. The system includes smoke detectors and heat detectors in all areas including patient rooms, operating theaters, pharmacy departments and administrative areas.</p><p>CO2 fire extinguishers are placed in areas with sensitive electronic equipment such as MRI rooms, CT scan rooms and data centers. ABC powder extinguishers are placed in general areas and hallways.</p><p>Notably, the fire alarm system integrates with the Building Management System (BMS) to automatically coordinate elevators, emergency exits and ventilation systems during incidents. The project has been accepted and put into operation.</p>',
                ],
                'images' => ['projects/sample-11.jpg', 'projects/sample-12.jpg', 'projects/sample-13.jpg'],
                'is_active' => true,
            ],
        ];

        foreach ($projects as $project) {
            Project::create($project);
        }
    }
}
