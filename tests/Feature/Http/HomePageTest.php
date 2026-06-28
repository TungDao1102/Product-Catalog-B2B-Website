<?php

namespace Tests\Feature\Http;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class HomePageTest extends TestCase
{
    use DatabaseMigrations;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public function test_homepage_returns_200(): void
    {
        $response = $this->get(route('home'));

        $response->assertStatus(200);
    }

    public function test_homepage_shows_featured_section(): void
    {
        $response = $this->get(route('home'));

        $response->assertStatus(200);
        $response->assertSee('Sản phẩm tiêu biểu');
    }

    public function test_homepage_shows_latest_products(): void
    {
        $response = $this->get(route('home'));

        $response->assertStatus(200);
        $response->assertSee('Sản phẩm tiêu biểu');
    }
}
