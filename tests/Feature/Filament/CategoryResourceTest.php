<?php

namespace Tests\Feature\Filament;

use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CategoryResourceTest extends TestCase
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

    public function test_can_list_categories(): void
    {
        Category::factory()->create(['name' => 'Test Category']);

        $response = $this->get('/admin/categories');

        $response->assertStatus(200);
        $response->assertSee('Test Category');
    }

    public function test_can_render_create_page(): void
    {
        $response = $this->get('/admin/categories/create');

        $response->assertStatus(200);
    }

    public function test_can_create_category(): void
    {
        $response = $this->post('/admin/categories', [
            'name' => 'New Category',
            'slug' => 'new-category',
            'is_active' => true,
            'sort_order' => 1,
        ]);

        $response->assertSessionHasNoErrors();
    }

    public function test_can_render_edit_page(): void
    {
        $category = Category::factory()->create();

        $response = $this->get("/admin/categories/{$category->id}/edit");

        $response->assertStatus(200);
    }
}
