<?php

namespace Tests\Feature\Http;

use App\Models\Product;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class ProductSearchTest extends TestCase
{
    use DatabaseMigrations;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public function test_search_by_name_returns_matching_products(): void
    {
        $product = Product::first();
        $this->assertNotNull($product, 'No product found in seed data');

        $searchTerm = mb_substr($product->name, 0, 5);

        $response = $this->get(route('products.index', ['search' => $searchTerm]));

        $response->assertStatus(200);
        $response->assertSee($product->name);
    }

    public function test_search_by_sku_returns_matching_products(): void
    {
        $product = Product::first();
        $this->assertNotNull($product, 'No product found in seed data');

        $response = $this->get(route('products.index', ['search' => $product->sku]));

        $response->assertStatus(200);
        $response->assertSee($product->name);
    }

    public function test_search_with_no_results_shows_empty_state(): void
    {
        $response = $this->get(route('products.index', ['search' => 'ZZZZNOTEXIST']));

        $response->assertStatus(200);
        $response->assertSee('Không tìm thấy sản phẩm nào');
    }

    public function test_category_filter_works_with_search(): void
    {
        $product = Product::first();
        $this->assertNotNull($product, 'No product found in seed data');

        $category = $product->category;
        $rootCategory = $category;
        while ($rootCategory && $rootCategory->parent) {
            $rootCategory = $rootCategory->parent;
        }

        $response = $this->get(route('products.index', [
            'category' => $rootCategory->slug,
            'search' => mb_substr($product->name, 0, 5),
        ]));

        $response->assertStatus(200);
    }
}
