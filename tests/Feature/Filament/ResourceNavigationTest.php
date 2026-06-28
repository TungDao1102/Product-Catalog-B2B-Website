<?php

namespace Tests\Feature\Filament;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ResourceNavigationTest extends TestCase
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

    public function test_categories_index_page_returns_200(): void
    {
        $response = $this->get('/admin/categories');

        $response->assertStatus(200);
    }

    public function test_brands_index_page_returns_200(): void
    {
        $response = $this->get('/admin/brands');

        $response->assertStatus(200);
    }
}
