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
            'name' => ['en' => 'Hikvision', 'vi' => 'Hikvision'],
            'slug' => 'hikvision',
            'description' => [
                'vi' => 'Hikvision là nhà sản xuất thiết bị giám sát và an ninh hàng đầu thế giới, cung cấp giải pháp camera IP, đầu ghi hình và phần mềm quản lý video chuyên nghiệp.',
                'en' => 'Hikvision is the world\'s leading manufacturer of surveillance and security equipment, providing IP cameras, DVRs/NVRs and professional video management software.',
            ],
            'website' => 'https://www.hikvision.com',
            'is_active' => true,
            'sort_order' => 1,
        ]);

        Brand::create([
            'name' => ['en' => 'Dahua', 'vi' => 'Dahua'],
            'slug' => 'dahua',
            'description' => [
                'vi' => 'Dahua Technology là thương hiệu thiết bị an ninh và giám sát hàng đầu, chuyên về camera AI, thiết bị lưu trữ và giải pháp IoT an ninh.',
                'en' => 'Dahua Technology is a leading security and surveillance equipment brand, specializing in AI cameras, storage devices and security IoT solutions.',
            ],
            'website' => 'https://www.dahuasecurity.com',
            'is_active' => true,
            'sort_order' => 2,
        ]);

        Brand::create([
            'name' => ['en' => 'Axis Communications', 'vi' => 'Axis Communications'],
            'slug' => 'axis',
            'description' => [
                'vi' => 'Axis Communications tiên phong trong lĩnh vực camera mạng IP, cung cấp các giải pháp giám sát chất lượng cao cho doanh nghiệp và tổ chức.',
                'en' => 'Axis Communications pioneered the network IP camera industry, providing high-quality surveillance solutions for businesses and organizations.',
            ],
            'website' => 'https://www.axis.com',
            'is_active' => true,
            'sort_order' => 3,
        ]);

        Brand::create([
            'name' => ['en' => 'Bosch Security', 'vi' => 'Bosch Security'],
            'slug' => 'bosch',
            'description' => [
                'vi' => 'Bosch Security Systems cung cấp giải pháp an ninh tích hợp bao gồm camera giám sát, hệ thống báo động và quản lý truy cập.',
                'en' => 'Bosch Security Systems provides integrated security solutions including surveillance cameras, alarm systems and access control.',
            ],
            'website' => 'https://www.boschsecurity.com',
            'is_active' => true,
            'sort_order' => 4,
        ]);

        Brand::create([
            'name' => ['en' => 'Honeywell', 'vi' => 'Honeywell'],
            'slug' => 'honeywell',
            'description' => [
                'vi' => 'Honeywell là tập đoàn đa quốc gia cung cấp giải pháp an ninh, báo cháy và tự động hóa tòa nhà hàng đầu thế giới.',
                'en' => 'Honeywell is a multinational conglomerate providing world-leading security, fire alarm and building automation solutions.',
            ],
            'website' => 'https://www.honeywell.com',
            'is_active' => true,
            'sort_order' => 5,
        ]);
    }
}
