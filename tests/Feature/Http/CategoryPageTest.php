<?php

namespace Tests\Feature\Http;

use App\Models\Category;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class CategoryPageTest extends TestCase
{
    use DatabaseMigrations;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public function test_category_page_returns_200(): void
    {
        $category = Category::whereNull('parent_id')->first();
        $this->assertNotNull($category, 'No root category found in seed data');

        $response = $this->get(route('categories.show', $category->slug));

        $response->assertStatus(200);
    }

    public function test_category_page_shows_category_name(): void
    {
        $category = Category::whereNull('parent_id')->first();
        $this->assertNotNull($category, 'No root category found in seed data');

        $response = $this->get(route('categories.show', $category->slug));

        $response->assertStatus(200);
        $response->assertSee($category->name);
    }

    public function test_category_page_pagination_returns_200(): void
    {
        $category = Category::whereNull('parent_id')->first();
        $this->assertNotNull($category, 'No root category found in seed data');

        $response = $this->get(route('categories.show', $category->slug) . '?page=1');

        $response->assertStatus(200);
    }
}
