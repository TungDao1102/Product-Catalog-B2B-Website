<?php

namespace Tests\Feature\Http;

use App\Models\Product;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class ProductCardRenderingTest extends TestCase
{
    use DatabaseMigrations;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public function test_product_list_shows_product_cards(): void
    {
        $response = $this->get(route('products.index'));

        $response->assertStatus(200);
        $response->assertSee('product-card-b2b');
    }

    public function test_product_list_shows_featured_badge(): void
    {
        $featuredProduct = Product::where('is_featured', true)->first();

        if (!$featuredProduct) {
            $this->markTestSkipped('No featured product found in seed data');
            return;
        }

        $response = $this->get(route('products.index'));

        $response->assertStatus(200);
        $response->assertSee('Nổi bật');
    }

    public function test_product_list_pagination_returns_200(): void
    {
        $response = $this->get(route('products.index', ['page' => 1]));

        $response->assertStatus(200);
    }
}
