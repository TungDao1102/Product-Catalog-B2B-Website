<?php

namespace Database\Seeders;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        // Query categories by slug to avoid hardcoding IDs
        $xray = Category::where('slug', 'may-soi-chieu-xray')->first();
        $metalDetector = Category::where('slug', 'may-do-kim-loai')->first();
        $ipCamera = Category::where('slug', 'camera-ip')->first();
        $analogCamera = Category::where('slug', 'camera-analog')->first();
        $fireDetector = Category::where('slug', 'dau-bao-chay')->first();
        $firePanel = Category::where('slug', 'trung-tam-bao-chay')->first();
        $powderExt = Category::where('slug', 'binh-chua-chay-bot')->first();
        $co2Ext = Category::where('slug', 'binh-chua-chay-co2')->first();
        $accessDoor = Category::where('slug', 'cua-an-ninh')->first();
        $cardReader = Category::where('slug', 'dau-doc-the')->first();

        // Map brands by slug (dynamic lookup to avoid hardcoded ID issues with RefreshDatabase)
        $hikvision = Brand::where('slug', 'hikvision')->value('id') ?? 1;
        $dahua = Brand::where('slug', 'dahua')->value('id') ?? 2;
        $axis = Brand::where('slug', 'axis')->value('id') ?? 3;
        $bosch = Brand::where('slug', 'bosch')->value('id') ?? 4;
        $honeywell = Brand::where('slug', 'honeywell')->value('id') ?? 5;

        // Products under "Máy soi chiếu X-Ray"
        Product::create([
            'category_id' => $xray->id,
            'brand_id' => $bosch,
            'name' => ['vi' => 'Máy soi chiếu X-Ray trường bay', 'en' => 'Airport X-Ray Scanner'],
            'slug' => Str::slug('Máy soi chiếu X-Ray trường bay'),
            'sku' => 'XRAY-BOSCH-001',
            'short_description' => [
                'vi' => 'Máy soi chiếu tia X trường bay công suất lớn, phát hiện vật cấm chính xác cao.',
                'en' => 'Large-capacity airport X-ray scanner with high-accuracy prohibited item detection.',
            ],
            'description' => [
                'vi' => '<p>Máy soi chiếu tia X trường bay công suất lớn, trang bị công nghệ phát hiện vật cấm thông minh. Hỗ trợ phân loại tự động các vật liệu hữu cơ và vô cơ. Phù hợp cho sân bay, nhà ga và các trung tâm thương mại lớn.</p>',
                'en' => '<p>Large-capacity airport X-ray scanner equipped with smart prohibited item detection technology. Supports automatic classification of organic and inorganic materials. Suitable for airports, train stations and large shopping centers.</p>',
            ],
            'technical_specs' => [
                ['attribute_name' => 'Kích thước đường hầm', 'attribute_value' => '1000x800mm'],
                ['attribute_name' => 'Điện áp hoạt động', 'attribute_value' => '220V/50Hz'],
                ['attribute_name' => 'Công suất tiêu thụ', 'attribute_value' => '1.5kW'],
                ['attribute_name' => 'Độ phân giải', 'attribute_value' => '40 AWG'],
                ['attribute_name' => 'Tốc độ băng tải', 'attribute_value' => '0.2m/s'],
                ['attribute_name' => 'Trọng lượng', 'attribute_value' => '450kg'],
                ['attribute_name' => 'Nhiệt độ hoạt động', 'attribute_value' => '0°C đến 40°C'],
            ],
            'unit' => 'bộ',
            'price' => 450000000,
            'min_order_qty' => 1,
            'images' => ['img/products/xray-1.jpg', 'img/products/xray-2.jpg'],
            'brochure' => null,
            'is_featured' => true,
            'is_active' => true,
            'sort_order' => 1,
        ]);

        Product::create([
            'category_id' => $xray->id,
            'brand_id' => $bosch,
            'name' => ['vi' => 'Máy soi chiếu X-Ray di động', 'en' => 'Portable X-Ray Scanner'],
            'slug' => Str::slug('Máy soi chiếu X-Ray di động'),
            'sku' => 'XRAY-BOSCH-002',
            'short_description' => [
                'vi' => 'Máy soi chiếu tia X di động, phù hợp cho kiểm tra an ninh linh hoạt.',
                'en' => 'Portable X-ray scanner, suitable for flexible security inspections.',
            ],
            'description' => [
                'vi' => '<p>Máy soi chiếu tia X di động nhỏ gọn, dễ dàng di chuyển và lắp đặt. Phù hợp cho các sự kiện, hội nghị và kiểm tra an ninh tạm thời.</p>',
                'en' => '<p>Compact portable X-ray scanner, easy to move and install. Suitable for events, conferences and temporary security inspections.</p>',
            ],
            'technical_specs' => [
                ['attribute_name' => 'Kích thước đường hầm', 'attribute_value' => '600x400mm'],
                ['attribute_name' => 'Điện áp hoạt động', 'attribute_value' => '220V/50Hz'],
                ['attribute_name' => 'Công suất tiêu thụ', 'attribute_value' => '0.8kW'],
                ['attribute_name' => 'Độ phân giải', 'attribute_value' => '38 AWG'],
                ['attribute_name' => 'Tốc độ băng tải', 'attribute_value' => '0.15m/s'],
                ['attribute_name' => 'Trọng lượng', 'attribute_value' => '180kg'],
            ],
            'unit' => 'bộ',
            'price' => 280000000,
            'min_order_qty' => 1,
            'images' => ['img/products/xray-portable-1.jpg'],
            'brochure' => null,
            'is_featured' => false,
            'is_active' => true,
            'sort_order' => 2,
        ]);

        // Products under "Máy dò kim loại"
        Product::create([
            'category_id' => $metalDetector->id,
            'brand_id' => $bosch,
            'name' => ['vi' => 'Cửa dò kim loại 6 vùng', 'en' => '6-Zone Walk-Through Metal Detector'],
            'slug' => Str::slug('Cửa dò kim loại 6 vùng'),
            'sku' => 'MD-BOSCH-001',
            'short_description' => [
                'vi' => 'Cửa dò kim loại 6 vùng phát hiện, độ nhạy cao, phù hợp cửa ra vào.',
                'en' => '6-zone walk-through metal detector, high sensitivity, suitable for entrances.',
            ],
            'description' => [
                'vi' => '<p>Cửa dò kim loại 6 vùng phát hiện với độ nhạy cao, cho phép xác định chính xác vị trí vật thể kim loại trên cơ thể. Phù hợp lắp đặt tại các cửa ra vào tòa nhà, trung tâm thương mại và nhà ga.</p>',
                'en' => '<p>6-zone detection walk-through metal detector with high sensitivity, allowing precise location of metal objects on the body. Suitable for installation at building entrances, shopping centers and train stations.</p>',
            ],
            'technical_specs' => [
                ['attribute_name' => 'Số vùng phát hiện', 'attribute_value' => '6 vùng'],
                ['attribute_name' => 'Độ nhạy', 'attribute_value' => 'Có thể điều chỉnh 100 cấp'],
                ['attribute_name' => 'Tốc độ phát hiện', 'attribute_value' => '< 1 giây'],
                ['attribute_name' => 'Kích thước', 'attribute_value' => '2200x850x500mm'],
                ['attribute_name' => 'Trọng lượng', 'attribute_value' => '65kg'],
                ['attribute_name' => 'Nhiệt độ hoạt động', 'attribute_value' => '-20°C đến 60°C'],
            ],
            'unit' => 'bộ',
            'price' => 35000000,
            'min_order_qty' => 1,
            'images' => ['img/products/walkthrough-1.jpg'],
            'brochure' => null,
            'is_featured' => true,
            'is_active' => true,
            'sort_order' => 1,
        ]);

        // Products under "Camera IP"
        Product::create([
            'category_id' => $ipCamera->id,
            'brand_id' => $hikvision,
            'name' => ['vi' => 'Camera IP Hikvision 2MP', 'en' => 'Hikvision 2MP IP Camera'],
            'slug' => Str::slug('Camera IP Hikvision 2MP'),
            'sku' => 'CAM-HIK-001',
            'short_description' => [
                'vi' => 'Camera IP 2MP hồng ngoại thông minh, độ phân giải Full HD.',
                'en' => '2MP smart IR IP camera, Full HD resolution.',
            ],
            'description' => [
                'vi' => '<p>Camera IP Hikvision 2MP với công nghệ hồng ngoại thông minh, cho hình ảnh sắc nét ngày và đêm. Hỗ trợ giao thức PoE, dễ dàng lắp đặt và tích hợp hệ thống.</p>',
                'en' => '<p>Hikvision 2MP IP camera with smart IR technology, delivering crisp images day and night. Supports PoE protocol, easy to install and integrate into systems.</p>',
            ],
            'technical_specs' => [
                ['attribute_name' => 'Độ phân giải', 'attribute_value' => '2MP (1920x1080)'],
                ['attribute_name' => 'Ống kính', 'attribute_value' => '2.8mm / 4mm / 6mm'],
                ['attribute_name' => 'Hồng ngoại', 'attribute_value' => '30m'],
                ['attribute_name' => 'Chuẩn nén', 'attribute_value' => 'H.265+ / H.265 / H.264+ / H.264'],
                ['attribute_name' => 'Chống nước', 'attribute_value' => 'IP67'],
                ['attribute_name' => 'Nguồn', 'attribute_value' => 'PoE / DC 12V'],
                ['attribute_name' => 'Nhiệt độ hoạt động', 'attribute_value' => '-30°C đến 60°C'],
            ],
            'unit' => 'bộ',
            'price' => 2500000,
            'min_order_qty' => 10,
            'images' => ['img/products/camera-ip-1.jpg', 'img/products/camera-ip-2.jpg'],
            'brochure' => null,
            'is_featured' => true,
            'is_active' => true,
            'sort_order' => 1,
        ]);

        Product::create([
            'category_id' => $ipCamera->id,
            'brand_id' => $dahua,
            'name' => ['vi' => 'Camera IP Dahua 4MP AI', 'en' => 'Dahua 4MP AI IP Camera'],
            'slug' => Str::slug('Camera IP Dahua 4MP AI'),
            'sku' => 'CAM-DAH-001',
            'short_description' => [
                'vi' => 'Camera IP 4MP tích hợp AI phát hiện người và phương tiện.',
                'en' => '4MP IP camera with AI-powered people and vehicle detection.',
            ],
            'description' => [
                'vi' => '<p>Camera IP Dahua 4MP tích hợp trí tuệ nhân tạo có khả năng phát hiện người, phương tiện và phân tích hành vi thông minh. Phù hợp cho các hệ thống an ninh chuyên nghiệp.</p>',
                'en' => '<p>Dahua 4MP IP camera with built-in artificial intelligence capable of people detection, vehicle detection and intelligent behavior analysis. Suitable for professional security systems.</p>',
            ],
            'technical_specs' => [
                ['attribute_name' => 'Độ phân giải', 'attribute_value' => '4MP (2688x1520)'],
                ['attribute_name' => 'Ống kính', 'attribute_value' => '2.7mm~13.5mm motorized'],
                ['attribute_name' => 'Hồng ngoại', 'attribute_value' => '50m'],
                ['attribute_name' => 'AI', 'attribute_value' => 'Phát hiện người, xe, xâm nhập'],
                ['attribute_name' => 'Chuẩn nén', 'attribute_value' => 'H.265+ / H.265'],
                ['attribute_name' => 'Chống nước', 'attribute_value' => 'IP67'],
                ['attribute_name' => 'Nguồn', 'attribute_value' => 'PoE+ / DC 12V'],
            ],
            'unit' => 'bộ',
            'price' => 4500000,
            'min_order_qty' => 5,
            'images' => ['img/products/dahua-ip-1.jpg'],
            'brochure' => null,
            'is_featured' => true,
            'is_active' => true,
            'sort_order' => 2,
        ]);

        // Products under "Camera Analog"
        Product::create([
            'category_id' => $analogCamera->id,
            'brand_id' => $hikvision,
            'name' => ['vi' => 'Camera Analog Hikvision 2MP', 'en' => 'Hikvision 2MP Analog Camera'],
            'slug' => Str::slug('Camera Analog Hikvision 2MP'),
            'sku' => 'CAM-HIK-002',
            'short_description' => [
                'vi' => 'Camera Analog HD 2MP giá thành hợp lý, dễ dàng thay thế hệ thống cũ.',
                'en' => '2MP HD analog camera, cost-effective, easy to upgrade from old systems.',
            ],
            'description' => [
                'vi' => '<p>Camera Analog Hikvision 2MP giải pháp nâng cấp từ hệ thống analog truyền thống lên HD với chi phí thấp. Tương thích đầu ghi DVR và TVI.</p>',
                'en' => '<p>Hikvision 2MP analog camera — an upgrade solution from traditional analog to HD at low cost. Compatible with DVR and TVI recorders.</p>',
            ],
            'technical_specs' => [
                ['attribute_name' => 'Độ phân giải', 'attribute_value' => '2MP (1920x1080)'],
                ['attribute_name' => 'Ống kính', 'attribute_value' => '3.6mm'],
                ['attribute_name' => 'Hồng ngoại', 'attribute_value' => '20m'],
                ['attribute_name' => 'Chuẩn tín hiệu', 'attribute_value' => 'TVI / AHD / CVI / CVBS'],
                ['attribute_name' => 'Chống nước', 'attribute_value' => 'IP67'],
                ['attribute_name' => 'Nguồn', 'attribute_value' => 'DC 12V'],
            ],
            'unit' => 'bộ',
            'price' => 850000,
            'min_order_qty' => 20,
            'images' => ['img/products/analog-cam-1.jpg'],
            'brochure' => null,
            'is_featured' => false,
            'is_active' => true,
            'sort_order' => 1,
        ]);

        // Products under "Đầu báo cháy"
        Product::create([
            'category_id' => $fireDetector->id,
            'brand_id' => $honeywell,
            'name' => ['vi' => 'Đầu báo khói Honeywell', 'en' => 'Honeywell Smoke Detector'],
            'slug' => Str::slug('Đầu báo khói Honeywell'),
            'sku' => 'FD-HON-001',
            'short_description' => [
                'vi' => 'Đầu báo khói quang điện Honeywell, phát hiện cháy sớm, độ tin cậy cao.',
                'en' => 'Honeywell photoelectric smoke detector, early fire detection, high reliability.',
            ],
            'description' => [
                'vi' => '<p>Đầu báo khói Honeywell sử dụng công nghệ quang điện tiên tiến, phát hiện sớm các đám cháy phát sinh khói. Độ nhạy cao, ít báo động giả, phù hợp cho tòa nhà và văn phòng.</p>',
                'en' => '<p>Honeywell smoke detector using advanced photoelectric technology, early detection of smoldering fires. High sensitivity, low false alarm rate, suitable for buildings and offices.</p>',
            ],
            'technical_specs' => [
                ['attribute_name' => 'Loại đầu báo', 'attribute_value' => 'Quang điện (Photoelectric)'],
                ['attribute_name' => 'Điện áp hoạt động', 'attribute_value' => '24VDC'],
                ['attribute_name' => 'Dòng điện standby', 'attribute_value' => '< 50µA'],
                ['attribute_name' => 'Dòng điện báo động', 'attribute_value' => '< 50mA'],
                ['attribute_name' => 'Độ nhạy', 'attribute_value' => '0.5%/ft đến 3.5%/ft'],
                ['attribute_name' => 'Nhiệt độ hoạt động', 'attribute_value' => '-10°C đến 50°C'],
                ['attribute_name' => 'Diện tích bảo vệ', 'attribute_value' => '80m²'],
            ],
            'unit' => 'cái',
            'price' => 450000,
            'min_order_qty' => 50,
            'images' => ['img/products/smoke-detector-1.jpg'],
            'brochure' => null,
            'is_featured' => false,
            'is_active' => true,
            'sort_order' => 1,
        ]);

        Product::create([
            'category_id' => $fireDetector->id,
            'brand_id' => $honeywell,
            'name' => ['vi' => 'Đầu báo nhiệt Honeywell', 'en' => 'Honeywell Heat Detector'],
            'slug' => Str::slug('Đầu báo nhiệt Honeywell'),
            'sku' => 'FD-HON-002',
            'short_description' => [
                'vi' => 'Đầu báo nhiệt cố định và gia tốc Honeywell, phù hợp khu vực bếp, nhà xe.',
                'en' => 'Honeywell fixed-rate heat detector, suitable for kitchens and garages.',
            ],
            'description' => [
                'vi' => '<p>Đầu báo nhiệt Honeywell phát hiện sự gia tăng nhiệt độ đột ngột hoặc ngưỡng nhiệt độ cố định. Lý tưởng cho các khu vực có khói bụi như bếp, nhà xe, phòng máy.</p>',
                'en' => '<p>Honeywell heat detector detects sudden temperature rise or fixed temperature threshold. Ideal for dusty areas such as kitchens, garages and machine rooms.</p>',
            ],
            'technical_specs' => [
                ['attribute_name' => 'Loại đầu báo', 'attribute_value' => 'Cố định + Gia tốc'],
                ['attribute_name' => 'Ngưỡng nhiệt cố định', 'attribute_value' => '57°C'],
                ['attribute_name' => 'Điện áp hoạt động', 'attribute_value' => '24VDC'],
                ['attribute_name' => 'Dòng điện standby', 'attribute_value' => '< 50µA'],
                ['attribute_name' => 'Nhiệt độ hoạt động', 'attribute_value' => '-10°C đến 70°C'],
                ['attribute_name' => 'Diện tích bảo vệ', 'attribute_value' => '60m²'],
            ],
            'unit' => 'cái',
            'price' => 350000,
            'min_order_qty' => 50,
            'images' => ['img/products/heat-detector-1.jpg'],
            'brochure' => null,
            'is_featured' => false,
            'is_active' => true,
            'sort_order' => 2,
        ]);

        // Products under "Trung tâm báo cháy"
        Product::create([
            'category_id' => $firePanel->id,
            'brand_id' => $honeywell,
            'name' => ['vi' => 'Tủ trung tâm báo cháy Honeywell 8 zone', 'en' => 'Honeywell 8-Zone Fire Alarm Panel'],
            'slug' => Str::slug('Tủ trung tâm báo cháy Honeywell 8 zone'),
            'sku' => 'FCP-HON-001',
            'short_description' => [
                'vi' => 'Trung tâm báo cháy 8 kênh, quản lý tập trung toàn bộ hệ thống.',
                'en' => '8-zone fire alarm panel, centralized management of the entire system.',
            ],
            'description' => [
                'vi' => '<p>Trung tâm báo cháy Honeywell 8 zone cho phép quản lý đến 8 khu vực độc lập. Hỗ trợ đầu báo khói, nhiệt, nút nhấn và còi báo động. Giao diện thân thiện, dễ cài đặt và vận hành.</p>',
                'en' => '<p>Honeywell 8-zone fire alarm panel manages up to 8 independent zones. Supports smoke detectors, heat detectors, manual call points and alarm bells. User-friendly interface, easy to install and operate.</p>',
            ],
            'technical_specs' => [
                ['attribute_name' => 'Số zone', 'attribute_value' => '8 zone'],
                ['attribute_name' => 'Điện áp hoạt động', 'attribute_value' => '220VAC / 24VDC dự phòng'],
                ['attribute_name' => 'Dòng điện tối đa', 'attribute_value' => '3A'],
                ['attribute_name' => 'Pin dự phòng', 'attribute_value' => '7Ah / 12VDC x 2'],
                ['attribute_name' => 'Chuẩn kết nối', 'attribute_value' => 'RS232, RS485'],
                ['attribute_name' => 'Chứng nhận', 'attribute_value' => 'UL, CE, EN54'],
            ],
            'unit' => 'bộ',
            'price' => 8500000,
            'min_order_qty' => 1,
            'images' => ['img/products/fire-panel-1.jpg'],
            'brochure' => null,
            'is_featured' => false,
            'is_active' => true,
            'sort_order' => 1,
        ]);

        // Products under "Bình chữa cháy bột"
        Product::create([
            'category_id' => $powderExt->id,
            'brand_id' => 5, // Honeywell
            'name' => ['vi' => 'Bình chữa cháy bột ABC 4kg', 'en' => 'ABC 4kg Powder Fire Extinguisher'],
            'slug' => Str::slug('Bình chữa cháy bột ABC 4kg'),
            'sku' => 'FE-POW-001',
            'short_description' => [
                'vi' => 'Bình chữa cháy bột ABC 4kg phù hợp mọi loại đám cháy A, B, C.',
                'en' => 'ABC 4kg powder fire extinguisher suitable for all Class A, B, C fires.',
            ],
            'description' => [
                'vi' => '<p>Bình chữa cháy bột ABC 4kg chữa được tất cả các loại đám cháy rắn (A), lỏng (B) và khí (C). Thiết kế nhỏ gọn, van xả an toàn, dễ sử dụng. Đã qua kiểm định PCCC.</p>',
                'en' => '<p>ABC 4kg dry powder fire extinguisher capable of extinguishing solid (A), liquid (B) and gas (C) fires. Compact design, safety discharge valve, easy to use. Fire safety certified.</p>',
            ],
            'technical_specs' => [
                ['attribute_name' => 'Loại bột', 'attribute_value' => 'ABC - Amoni Phosphate'],
                ['attribute_name' => 'Khối lượng bột', 'attribute_value' => '4kg'],
                ['attribute_name' => 'Áp suất', 'attribute_value' => '15 bar'],
                ['attribute_name' => 'Tầm phun', 'attribute_value' => '3-5m'],
                ['attribute_name' => 'Thời gian xả', 'attribute_value' => '15 giây'],
                ['attribute_name' => 'Nhiệt độ bảo quản', 'attribute_value' => '-5°C đến 55°C'],
                ['attribute_name' => 'Kích thước', 'attribute_value' => 'D130 x H450mm'],
                ['attribute_name' => 'Tiêu chuẩn', 'attribute_value' => 'TCVN 7435-1:2004'],
            ],
            'unit' => 'bình',
            'price' => 350000,
            'min_order_qty' => 10,
            'images' => ['img/products/powder-ext-1.jpg'],
            'brochure' => null,
            'is_featured' => false,
            'is_active' => true,
            'sort_order' => 1,
        ]);

        // Products under "Bình chữa cháy CO2"
        Product::create([
            'category_id' => $co2Ext->id,
            'brand_id' => 5, // Honeywell
            'name' => ['vi' => 'Bình chữa cháy CO2 5kg', 'en' => 'CO2 5kg Fire Extinguisher'],
            'slug' => Str::slug('Bình chữa cháy CO2 5kg'),
            'sku' => 'FE-CO2-001',
            'short_description' => [
                'vi' => 'Bình chữa cháy CO2 5kg sạch, không để lại cặn, an toàn cho thiết bị điện.',
                'en' => 'CO2 5kg clean fire extinguisher, leaves no residue, safe for electrical equipment.',
            ],
            'description' => [
                'vi' => '<p>Bình chữa cháy CO2 5kg sử dụng khí CO2 tinh khiết, không dẫn điện, không để lại cặn sau khi sử dụng. Lý tưởng cho phòng máy tính, trung tâm dữ liệu, phòng thiết bị điện tử.</p>',
                'en' => '<p>CO2 5kg fire extinguisher uses pure CO2 gas, non-conductive, leaves no residue after use. Ideal for computer rooms, data centers and electronic equipment rooms.</p>',
            ],
            'technical_specs' => [
                ['attribute_name' => 'Loại khí', 'attribute_value' => 'CO2 tinh khiết 99.5%'],
                ['attribute_name' => 'Khối lượng CO2', 'attribute_value' => '5kg'],
                ['attribute_name' => 'Áp suất', 'attribute_value' => '57 bar (20°C)'],
                ['attribute_name' => 'Tầm phun', 'attribute_value' => '2-3m'],
                ['attribute_name' => 'Thời gian xả', 'attribute_value' => '12 giây'],
                ['attribute_name' => 'Kích thước', 'attribute_value' => 'D152 x H540mm'],
                ['attribute_name' => 'Nhiệt độ bảo quản', 'attribute_value' => '-10°C đến 50°C'],
            ],
            'unit' => 'bình',
            'price' => 650000,
            'min_order_qty' => 10,
            'images' => ['img/products/co2-ext-1.jpg'],
            'brochure' => null,
            'is_featured' => false,
            'is_active' => true,
            'sort_order' => 1,
        ]);

        // Products under "Cửa an ninh"
        Product::create([
            'category_id' => $accessDoor->id,
            'brand_id' => $bosch,
            'name' => ['vi' => 'Cửa an ninh Bosch 3 cánh', 'en' => 'Bosch 3-Wing Security Door'],
            'slug' => Str::slug('Cửa an ninh Bosch 3 cánh'),
            'sku' => 'AC-BOSCH-001',
            'short_description' => [
                'vi' => 'Cửa an ninh 3 cánh tích hợp kiểm soát ra vào, chịu lực cao.',
                'en' => '3-wing security door with integrated access control, high durability.',
            ],
            'description' => [
                'vi' => '<p>Cửa an ninh Bosch 3 cánh tích hợp hệ thống kiểm soát ra vào, cảm biến chống phá hoại. Thiết kế chịu lực cao, phù hợp cho các lối vào chính của tòa nhà và khu vực yêu cầu an ninh cao.</p>',
                'en' => '<p>Bosch 3-wing security door with integrated access control system and tamper sensors. High-durability design, suitable for building main entrances and high-security areas.</p>',
            ],
            'technical_specs' => [
                ['attribute_name' => 'Số cánh', 'attribute_value' => '3 cánh'],
                ['attribute_name' => 'Chất liệu', 'attribute_value' => 'Thép không gỉ SUS304'],
                ['attribute_name' => 'Tích hợp', 'attribute_value' => 'Đầu đọc thẻ, cảm biến chống phá'],
                ['attribute_name' => 'Điện áp', 'attribute_value' => '24VDC'],
                ['attribute_name' => 'Kích thước', 'attribute_value' => '1800x1600x120mm'],
                ['attribute_name' => 'Chống nước', 'attribute_value' => 'IP54'],
            ],
            'unit' => 'bộ',
            'price' => 15000000,
            'min_order_qty' => 1,
            'images' => ['img/products/security-door-1.jpg'],
            'brochure' => null,
            'is_featured' => false,
            'is_active' => true,
            'sort_order' => 1,
        ]);

        // Products under "Đầu đọc thẻ"
        Product::create([
            'category_id' => $cardReader->id,
            'brand_id' => $bosch,
            'name' => ['vi' => 'Đầu đọc thẻ RFID Bosch', 'en' => 'Bosch RFID Card Reader'],
            'slug' => Str::slug('Đầu đọc thẻ RFID Bosch'),
            'sku' => 'CR-BOSCH-001',
            'short_description' => [
                'vi' => 'Đầu đọc thẻ RFID 125KHz chống nước, giao tiếp Wiegand 26-bit.',
                'en' => '125KHz RFID card reader, waterproof, Wiegand 26-bit communication.',
            ],
            'description' => [
                'vi' => '<p>Đầu đọc thẻ RFID Bosch hoạt động ở tần số 125KHz, giao tiếp chuẩn Wiegand 26-bit. Thiết kế chống nước IP65, lắp đặt ngoài trời. Phù hợp hệ thống kiểm soát ra vào doanh nghiệp.</p>',
                'en' => '<p>Bosch RFID card reader operates at 125KHz with standard Wiegand 26-bit communication. IP65 waterproof design for outdoor installation. Suitable for enterprise access control systems.</p>',
            ],
            'technical_specs' => [
                ['attribute_name' => 'Tần số', 'attribute_value' => '125KHz'],
                ['attribute_name' => 'Chuẩn giao tiếp', 'attribute_value' => 'Wiegand 26-bit'],
                ['attribute_name' => 'Khoảng cách đọc', 'attribute_value' => '5-8cm'],
                ['attribute_name' => 'Điện áp', 'attribute_value' => '12VDC'],
                ['attribute_name' => 'Dòng điện', 'attribute_value' => '< 100mA'],
                ['attribute_name' => 'Chống nước', 'attribute_value' => 'IP65'],
                ['attribute_name' => 'Nhiệt độ hoạt động', 'attribute_value' => '-20°C đến 60°C'],
            ],
            'unit' => 'cái',
            'price' => 1200000,
            'min_order_qty' => 20,
            'images' => ['img/products/card-reader-1.jpg'],
            'brochure' => null,
            'is_featured' => false,
            'is_active' => true,
            'sort_order' => 1,
        ]);
    }
}
