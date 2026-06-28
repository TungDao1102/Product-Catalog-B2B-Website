<?php

namespace Tests\Feature\Http;

use App\Models\Product;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class ProductDetailTest extends TestCase
{
    use DatabaseMigrations;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public function test_product_detail_returns_200(): void
    {
        $product = Product::first();
        $this->assertNotNull($product, 'No product found in seed data');

        $response = $this->get(route('products.show', $product->slug));

        $response->assertStatus(200);
    }

    public function test_product_detail_shows_product_name(): void
    {
        $product = Product::first();
        $this->assertNotNull($product, 'No product found in seed data');

        $response = $this->get(route('products.show', $product->slug));

        $response->assertStatus(200);
        $response->assertSee($product->name);
    }

    public function test_product_detail_shows_technical_specs(): void
    {
        $product = Product::whereNotNull('technical_specs')->first();

        if (!$product) {
            $this->markTestSkipped('No product with technical_specs found in seed data');
            return;
        }

        $response = $this->get(route('products.show', $product->slug));

        $response->assertStatus(200);
        $response->assertSee('Thông số kỹ thuật');

        $specs = $product->technical_specs;
        if (is_array($specs) && count($specs) > 0 && isset($specs[0]['attribute_value'])) {
            $response->assertSee($specs[0]['attribute_value']);
        }
    }

    public function test_product_detail_shows_quote_button(): void
    {
        $product = Product::first();
        $this->assertNotNull($product, 'No product found in seed data');

        $response = $this->get(route('products.show', $product->slug));

        $response->assertStatus(200);
        $response->assertSee('Yêu cầu báo giá');
    }

    public function test_product_detail_shows_related_products(): void
    {
        $product = Product::first();
        $this->assertNotNull($product, 'No product found in seed data');

        $response = $this->get(route('products.show', $product->slug));

        $response->assertStatus(200);
        $response->assertSee('Sản phẩm liên quan');
    }
}
