<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        // Level 0: Ngành
        $security = Category::create([
            'name' => ['vi' => 'Thiết bị an ninh soi chiếu', 'en' => 'Security & Screening Equipment'],
            'slug' => 'thiet-bi-an-ninh-soi-chieu',
            'parent_id' => null,
            'description' => ['vi' => 'Các thiết bị an ninh, soi chiếu và giám sát chuyên dụng', 'en' => 'Professional security, screening and surveillance equipment'],
            'is_active' => true,
            'sort_order' => 1,
        ]);

        $fire = Category::create([
            'name' => ['vi' => 'Thiết bị báo cháy chữa cháy', 'en' => 'Fire Alarm & Suppression Equipment'],
            'slug' => 'thiet-bi-bao-chay-chua-chay',
            'parent_id' => null,
            'description' => ['vi' => 'Thiết bị phòng cháy chữa cháy và báo cháy', 'en' => 'Fire prevention, suppression and alarm equipment'],
            'is_active' => true,
            'sort_order' => 2,
        ]);

        $telecom = Category::create([
            'name' => ['vi' => 'Thiết bị viễn thông truyền dẫn', 'en' => 'Telecommunications & Transmission Equipment'],
            'slug' => 'thiet-bi-vien-thong-truyen-dan',
            'parent_id' => null,
            'description' => ['vi' => 'Thiết bị viễn thông, truyền dẫn và mạng', 'en' => 'Telecommunications, transmission and networking equipment'],
            'is_active' => true,
            'sort_order' => 3,
        ]);

        // Level 1: Nhóm dưới "Thiết bị an ninh soi chiếu"
        $scanner = Category::create([
            'name' => ['vi' => 'Máy soi chiếu', 'en' => 'X-Ray Scanners'],
            'slug' => 'may-soi-chieu',
            'parent_id' => $security->id,
            'description' => ['vi' => 'Máy soi chiếu an ninh các loại', 'en' => 'Various types of security X-ray scanners'],
            'is_active' => true,
            'sort_order' => 1,
        ]);

        $accessControl = Category::create([
            'name' => ['vi' => 'Thiết bị kiểm soát an ninh', 'en' => 'Access Control Equipment'],
            'slug' => 'thiet-bi-kiem-soat-an-ninh',
            'parent_id' => $security->id,
            'description' => ['vi' => 'Thiết bị kiểm soát ra vào và an ninh', 'en' => 'Access control and security equipment'],
            'is_active' => true,
            'sort_order' => 2,
        ]);

        $camera = Category::create([
            'name' => ['vi' => 'Camera giám sát', 'en' => 'Surveillance Cameras'],
            'slug' => 'camera-giam-sat',
            'parent_id' => $security->id,
            'description' => ['vi' => 'Camera giám sát an ninh các loại', 'en' => 'Various types of security surveillance cameras'],
            'is_active' => true,
            'sort_order' => 3,
        ]);

        // Level 1: Nhóm dưới "Thiết bị báo cháy chữa cháy"
        $fireAlarm = Category::create([
            'name' => ['vi' => 'Hệ thống báo cháy', 'en' => 'Fire Alarm Systems'],
            'slug' => 'he-thong-bao-chay',
            'parent_id' => $fire->id,
            'description' => ['vi' => 'Hệ thống báo cháy tự động', 'en' => 'Automatic fire alarm systems'],
            'is_active' => true,
            'sort_order' => 1,
        ]);

        $fireExtinguisher = Category::create([
            'name' => ['vi' => 'Bình chữa cháy', 'en' => 'Fire Extinguishers'],
            'slug' => 'binh-chua-chay',
            'parent_id' => $fire->id,
            'description' => ['vi' => 'Bình chữa cháy các loại', 'en' => 'Various types of fire extinguishers'],
            'is_active' => true,
            'sort_order' => 2,
        ]);

        $fireProtection = Category::create([
            'name' => ['vi' => 'Thiết bị phòng cháy', 'en' => 'Fire Protection Equipment'],
            'slug' => 'thiet-bi-phong-chay',
            'parent_id' => $fire->id,
            'description' => ['vi' => 'Thiết bị phòng cháy chuyên dụng', 'en' => 'Specialized fire protection equipment'],
            'is_active' => true,
            'sort_order' => 3,
        ]);

        // Level 1: Nhóm dưới "Thiết bị viễn thông truyền dẫn"
        $transmission = Category::create([
            'name' => ['vi' => 'Thiết bị truyền dẫn', 'en' => 'Transmission Equipment'],
            'slug' => 'thiet-bi-truyen-dan',
            'parent_id' => $telecom->id,
            'description' => ['vi' => 'Thiết bị truyền dẫn tín hiệu', 'en' => 'Signal transmission equipment'],
            'is_active' => true,
            'sort_order' => 1,
        ]);

        $networking = Category::create([
            'name' => ['vi' => 'Thiết bị mạng', 'en' => 'Networking Equipment'],
            'slug' => 'thiet-bi-mang',
            'parent_id' => $telecom->id,
            'description' => ['vi' => 'Thiết bị mạng viễn thông', 'en' => 'Telecommunications networking equipment'],
            'is_active' => true,
            'sort_order' => 2,
        ]);

        // Level 2: Loại dưới "Máy soi chiếu"
        Category::create([
            'name' => ['vi' => 'Máy soi chiếu X-Ray', 'en' => 'X-Ray Baggage Scanner'],
            'slug' => 'may-soi-chieu-xray',
            'parent_id' => $scanner->id,
            'description' => ['vi' => 'Máy soi chiếu tia X cho hành lý và hàng hóa', 'en' => 'X-ray scanners for luggage and cargo inspection'],
            'is_active' => true,
            'sort_order' => 1,
        ]);

        Category::create([
            'name' => ['vi' => 'Máy dò kim loại', 'en' => 'Metal Detectors'],
            'slug' => 'may-do-kim-loai',
            'parent_id' => $scanner->id,
            'description' => ['vi' => 'Máy dò kim loại cầm tay và cửa từ', 'en' => 'Handheld metal detectors and walk-through gates'],
            'is_active' => true,
            'sort_order' => 2,
        ]);

        // Level 2: Loại dưới "Thiết bị kiểm soát an ninh"
        Category::create([
            'name' => ['vi' => 'Cửa an ninh', 'en' => 'Security Doors'],
            'slug' => 'cua-an-ninh',
            'parent_id' => $accessControl->id,
            'description' => ['vi' => 'Cửa an ninh và cửa kiểm soát', 'en' => 'Security doors and access control gates'],
            'is_active' => true,
            'sort_order' => 1,
        ]);

        Category::create([
            'name' => ['vi' => 'Đầu đọc thẻ', 'en' => 'Card Readers'],
            'slug' => 'dau-doc-the',
            'parent_id' => $accessControl->id,
            'description' => ['vi' => 'Đầu đọc thẻ từ và thẻ RFID', 'en' => 'Magnetic stripe and RFID card readers'],
            'is_active' => true,
            'sort_order' => 2,
        ]);

        // Level 2: Loại dưới "Camera giám sát"
        Category::create([
            'name' => ['vi' => 'Camera IP', 'en' => 'IP Cameras'],
            'slug' => 'camera-ip',
            'parent_id' => $camera->id,
            'description' => ['vi' => 'Camera IP giám sát qua mạng', 'en' => 'IP network surveillance cameras'],
            'is_active' => true,
            'sort_order' => 1,
        ]);

        Category::create([
            'name' => ['vi' => 'Camera Analog', 'en' => 'Analog Cameras'],
            'slug' => 'camera-analog',
            'parent_id' => $camera->id,
            'description' => ['vi' => 'Camera Analog truyền thống', 'en' => 'Traditional analog cameras'],
            'is_active' => true,
            'sort_order' => 2,
        ]);

        // Level 2: Loại dưới "Hệ thống báo cháy"
        Category::create([
            'name' => ['vi' => 'Đầu báo cháy', 'en' => 'Fire Detectors'],
            'slug' => 'dau-bao-chay',
            'parent_id' => $fireAlarm->id,
            'description' => ['vi' => 'Đầu báo khói, báo nhiệt và báo gas', 'en' => 'Smoke, heat and gas detectors'],
            'is_active' => true,
            'sort_order' => 1,
        ]);

        Category::create([
            'name' => ['vi' => 'Trung tâm báo cháy', 'en' => 'Fire Alarm Panels'],
            'slug' => 'trung-tam-bao-chay',
            'parent_id' => $fireAlarm->id,
            'description' => ['vi' => 'Tủ trung tâm báo cháy tự động', 'en' => 'Automatic fire alarm control panels'],
            'is_active' => true,
            'sort_order' => 2,
        ]);

        // Level 2: Loại dưới "Bình chữa cháy"
        Category::create([
            'name' => ['vi' => 'Bình chữa cháy bột', 'en' => 'Powder Fire Extinguishers'],
            'slug' => 'binh-chua-chay-bot',
            'parent_id' => $fireExtinguisher->id,
            'description' => ['vi' => 'Bình chữa cháy bột ABC', 'en' => 'ABC powder fire extinguishers'],
            'is_active' => true,
            'sort_order' => 1,
        ]);

        Category::create([
            'name' => ['vi' => 'Bình chữa cháy CO2', 'en' => 'CO2 Fire Extinguishers'],
            'slug' => 'binh-chua-chay-co2',
            'parent_id' => $fireExtinguisher->id,
            'description' => ['vi' => 'Bình chữa cháy khí CO2', 'en' => 'CO2 gas fire extinguishers'],
            'is_active' => true,
            'sort_order' => 2,
        ]);
    }
}
