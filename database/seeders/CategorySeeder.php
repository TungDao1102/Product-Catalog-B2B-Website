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
            'name' => 'Thiết bị an ninh soi chiếu',
            'slug' => 'thiet-bi-an-ninh-soi-chieu',
            'parent_id' => null,
            'description' => 'Các thiết bị an ninh, soi chiếu và giám sát chuyên dụng',
            'is_active' => true,
            'sort_order' => 1,
        ]);

        $fire = Category::create([
            'name' => 'Thiết bị báo cháy chữa cháy',
            'slug' => 'thiet-bi-bao-chay-chua-chay',
            'parent_id' => null,
            'description' => 'Thiết bị phòng cháy chữa cháy và báo cháy',
            'is_active' => true,
            'sort_order' => 2,
        ]);

        $telecom = Category::create([
            'name' => 'Thiết bị viễn thông truyền dẫn',
            'slug' => 'thiet-bi-vien-thong-truyen-dan',
            'parent_id' => null,
            'description' => 'Thiết bị viễn thông, truyền dẫn và mạng',
            'is_active' => true,
            'sort_order' => 3,
        ]);

        // Level 1: Nhóm dưới "Thiết bị an ninh soi chiếu"
        $scanner = Category::create([
            'name' => 'Máy soi chiếu',
            'slug' => 'may-soi-chieu',
            'parent_id' => $security->id,
            'description' => 'Máy soi chiếu an ninh các loại',
            'is_active' => true,
            'sort_order' => 1,
        ]);

        $accessControl = Category::create([
            'name' => 'Thiết bị kiểm soát an ninh',
            'slug' => 'thiet-bi-kiem-soat-an-ninh',
            'parent_id' => $security->id,
            'description' => 'Thiết bị kiểm soát ra vào và an ninh',
            'is_active' => true,
            'sort_order' => 2,
        ]);

        $camera = Category::create([
            'name' => 'Camera giám sát',
            'slug' => 'camera-giam-sat',
            'parent_id' => $security->id,
            'description' => 'Camera giám sát an ninh các loại',
            'is_active' => true,
            'sort_order' => 3,
        ]);

        // Level 1: Nhóm dưới "Thiết bị báo cháy chữa cháy"
        $fireAlarm = Category::create([
            'name' => 'Hệ thống báo cháy',
            'slug' => 'he-thong-bao-chay',
            'parent_id' => $fire->id,
            'description' => 'Hệ thống báo cháy tự động',
            'is_active' => true,
            'sort_order' => 1,
        ]);

        $fireExtinguisher = Category::create([
            'name' => 'Bình chữa cháy',
            'slug' => 'binh-chua-chay',
            'parent_id' => $fire->id,
            'description' => 'Bình chữa cháy các loại',
            'is_active' => true,
            'sort_order' => 2,
        ]);

        $fireProtection = Category::create([
            'name' => 'Thiết bị phòng cháy',
            'slug' => 'thiet-bi-phong-chay',
            'parent_id' => $fire->id,
            'description' => 'Thiết bị phòng cháy chuyên dụng',
            'is_active' => true,
            'sort_order' => 3,
        ]);

        // Level 1: Nhóm dưới "Thiết bị viễn thông truyền dẫn"
        $transmission = Category::create([
            'name' => 'Thiết bị truyền dẫn',
            'slug' => 'thiet-bi-truyen-dan',
            'parent_id' => $telecom->id,
            'description' => 'Thiết bị truyền dẫn tín hiệu',
            'is_active' => true,
            'sort_order' => 1,
        ]);

        $networking = Category::create([
            'name' => 'Thiết bị mạng',
            'slug' => 'thiet-bi-mang',
            'parent_id' => $telecom->id,
            'description' => 'Thiết bị mạng viễn thông',
            'is_active' => true,
            'sort_order' => 2,
        ]);

        // Level 2: Loại dưới "Máy soi chiếu"
        Category::create([
            'name' => 'Máy soi chiếu X-Ray',
            'slug' => 'may-soi-chieu-xray',
            'parent_id' => $scanner->id,
            'description' => 'Máy soi chiếu tia X cho hành lý và hàng hóa',
            'is_active' => true,
            'sort_order' => 1,
        ]);

        Category::create([
            'name' => 'Máy dò kim loại',
            'slug' => 'may-do-kim-loai',
            'parent_id' => $scanner->id,
            'description' => 'Máy dò kim loại cầm tay và cửa từ',
            'is_active' => true,
            'sort_order' => 2,
        ]);

        // Level 2: Loại dưới "Thiết bị kiểm soát an ninh"
        Category::create([
            'name' => 'Cửa an ninh',
            'slug' => 'cua-an-ninh',
            'parent_id' => $accessControl->id,
            'description' => 'Cửa an ninh và cửa kiểm soát',
            'is_active' => true,
            'sort_order' => 1,
        ]);

        Category::create([
            'name' => 'Đầu đọc thẻ',
            'slug' => 'dau-doc-the',
            'parent_id' => $accessControl->id,
            'description' => 'Đầu đọc thẻ từ và thẻ RFID',
            'is_active' => true,
            'sort_order' => 2,
        ]);

        // Level 2: Loại dưới "Camera giám sát"
        Category::create([
            'name' => 'Camera IP',
            'slug' => 'camera-ip',
            'parent_id' => $camera->id,
            'description' => 'Camera IP giám sát qua mạng',
            'is_active' => true,
            'sort_order' => 1,
        ]);

        Category::create([
            'name' => 'Camera Analog',
            'slug' => 'camera-analog',
            'parent_id' => $camera->id,
            'description' => 'Camera Analog truyền thống',
            'is_active' => true,
            'sort_order' => 2,
        ]);

        // Level 2: Loại dưới "Hệ thống báo cháy"
        Category::create([
            'name' => 'Đầu báo cháy',
            'slug' => 'dau-bao-chay',
            'parent_id' => $fireAlarm->id,
            'description' => 'Đầu báo khói, báo nhiệt và báo gas',
            'is_active' => true,
            'sort_order' => 1,
        ]);

        Category::create([
            'name' => 'Trung tâm báo cháy',
            'slug' => 'trung-tam-bao-chay',
            'parent_id' => $fireAlarm->id,
            'description' => 'Tủ trung tâm báo cháy tự động',
            'is_active' => true,
            'sort_order' => 2,
        ]);

        // Level 2: Loại dưới "Bình chữa cháy"
        Category::create([
            'name' => 'Bình chữa cháy bột',
            'slug' => 'binh-chua-chay-bot',
            'parent_id' => $fireExtinguisher->id,
            'description' => 'Bình chữa cháy bột ABC',
            'is_active' => true,
            'sort_order' => 1,
        ]);

        Category::create([
            'name' => 'Bình chữa cháy CO2',
            'slug' => 'binh-chua-chay-co2',
            'parent_id' => $fireExtinguisher->id,
            'description' => 'Bình chữa cháy khí CO2',
            'is_active' => true,
            'sort_order' => 2,
        ]);
    }
}
