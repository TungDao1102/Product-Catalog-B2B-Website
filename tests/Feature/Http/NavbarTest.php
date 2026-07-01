<?php

namespace Tests\Feature\Http;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class NavbarTest extends TestCase
{
    use DatabaseMigrations;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public function test_navbar_has_tin_tuc_link(): void
    {
        $response = $this->get('/vi');

        $response->assertStatus(200);
        $response->assertSee('Tin tức');
    }

    public function test_navbar_has_du_an_link(): void
    {
        $response = $this->get('/vi');

        $response->assertStatus(200);
        $response->assertSee('Dự án');
    }
}
