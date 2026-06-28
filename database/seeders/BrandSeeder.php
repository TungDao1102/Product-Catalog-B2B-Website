<?php

namespace Database\Seeders;

use App\Models\Brand;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BrandSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        Brand::create([
            'name' => 'Hikvision',
            'slug' => 'hikvision',
            'description' => 'Hikvision là nhà sản xuất thiết bị giám sát và an ninh hàng đầu thế giới, cung cấp giải pháp camera IP, đầu ghi hình và phần mềm quản lý video chuyên nghiệp.',
            'website' => 'https://www.hikvision.com',
            'is_active' => true,
            'sort_order' => 1,
        ]);

        Brand::create([
            'name' => 'Dahua',
            'slug' => 'dahua',
            'description' => 'Dahua Technology là thương hiệu thiết bị an ninh và giám sát hàng đầu, chuyên về camera AI, thiết bị lưu trữ và giải pháp IoT an ninh.',
            'website' => 'https://www.dahuasecurity.com',
            'is_active' => true,
            'sort_order' => 2,
        ]);

        Brand::create([
            'name' => 'Axis Communications',
            'slug' => 'axis',
            'description' => 'Axis Communications tiên phong trong lĩnh vực camera mạng IP, cung cấp các giải pháp giám sát chất lượng cao cho doanh nghiệp và tổ chức.',
            'website' => 'https://www.axis.com',
            'is_active' => true,
            'sort_order' => 3,
        ]);

        Brand::create([
            'name' => 'Bosch Security',
            'slug' => 'bosch',
            'description' => 'Bosch Security Systems cung cấp giải pháp an ninh tích hợp bao gồm camera giám sát, hệ thống báo động và quản lý truy cập.',
            'website' => 'https://www.boschsecurity.com',
            'is_active' => true,
            'sort_order' => 4,
        ]);

        Brand::create([
            'name' => 'Honeywell',
            'slug' => 'honeywell',
            'description' => 'Honeywell là tập đoàn đa quốc gia cung cấp giải pháp an ninh, báo cháy và tự động hóa tòa nhà hàng đầu thế giới.',
            'website' => 'https://www.honeywell.com',
            'is_active' => true,
            'sort_order' => 5,
        ]);
    }
}
