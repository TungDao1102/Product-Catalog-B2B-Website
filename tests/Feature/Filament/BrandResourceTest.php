<?php

namespace Tests\Feature\Filament;

use App\Models\Brand;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BrandResourceTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutMiddleware();

        $this->actingAs(User::factory()->create([
            'email' => 'admin@example.com',
        ]));
    }

    public function test_can_list_brands(): void
    {
        Brand::factory()->create(['name' => 'Test Brand']);

        $response = $this->get('/admin/brands');

        $response->assertStatus(200);
        $response->assertSee('Test Brand');
    }

    public function test_can_render_create_page(): void
    {
        $response = $this->get('/admin/brands/create');

        $response->assertStatus(200);
    }

    public function test_can_create_brand(): void
    {
        $response = $this->post('/admin/brands', [
            'name' => 'New Brand',
            'slug' => 'new-brand',
            'is_active' => true,
            'sort_order' => 1,
        ]);

        $response->assertSessionHasNoErrors();
    }

    public function test_can_render_edit_page(): void
    {
        $brand = Brand::factory()->create();

        $response = $this->get("/admin/brands/{$brand->id}/edit");

        $response->assertStatus(200);
    }
}
