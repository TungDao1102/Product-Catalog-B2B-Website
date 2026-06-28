<?php

namespace Tests\Feature\Filament;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductResourceTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutMiddleware();

        $this->actingAs(User::factory()->create([
            'email' => 'admin@example.com',
        ]));

        $this->seed([
            \Database\Seeders\CategorySeeder::class,
            \Database\Seeders\BrandSeeder::class,
        ]);
    }

    public function test_can_list_products(): void
    {
        Product::factory()->create(['name' => 'Test Product']);

        $response = $this->get('/admin/products');

        $response->assertStatus(200);
        $response->assertSee('Test Product');
    }

    public function test_can_render_create_page(): void
    {
        $response = $this->get('/admin/products/create');

        $response->assertStatus(200);
    }

    public function test_can_render_edit_page(): void
    {
        $category = Category::whereNotNull('parent_id')->whereDoesntHave('children')->first();
        $brand = Brand::first();
        $product = Product::factory()->create([
            'category_id' => $category->id,
            'brand_id' => $brand->id,
        ]);

        $response = $this->get("/admin/products/{$product->id}/edit");

        $response->assertStatus(200);
    }
}

