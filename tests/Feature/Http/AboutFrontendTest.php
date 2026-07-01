<?php

namespace Tests\Feature\Http;

use App\Models\About;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class AboutFrontendTest extends TestCase
{
    use DatabaseMigrations;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public function test_about_page_returns_200(): void
    {
        About::create([
            'content' => ['vi' => 'Nội dung giới thiệu', 'en' => 'About content'],
            'is_active' => true,
        ]);

        $response = $this->get('/vi/ve-chung-toi');

        $response->assertStatus(200);
    }

    public function test_about_page_shows_content(): void
    {
        About::create([
            'content' => ['vi' => 'Chào mừng đến với công ty', 'en' => 'Welcome to our company'],
            'is_active' => true,
        ]);

        $response = $this->get('/vi/ve-chung-toi');

        $response->assertSee('Chào mừng đến với công ty');
    }

    public function test_about_returns_404_when_inactive(): void
    {
        About::create([
            'content' => ['vi' => 'Nội dung', 'en' => 'Content'],
            'is_active' => false,
        ]);

        $response = $this->get('/vi/ve-chung-toi');

        $response->assertStatus(404);
    }

    public function test_about_returns_404_when_no_record(): void
    {
        $response = $this->get('/vi/ve-chung-toi');

        $response->assertStatus(404);
    }
}
