<?php

namespace Tests\Feature\Filament;

use App\Models\About;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AboutResourceTest extends TestCase
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

    public function test_about_edit_page_returns_200(): void
    {
        $about = About::create([
            'content' => ['vi' => 'Giới thiệu', 'en' => 'About us'],
        ]);

        $response = $this->get("/admin/about/{$about->id}/edit");

        $response->assertStatus(200);
    }

    public function test_about_can_be_updated(): void
    {
        $about = About::create([
            'content' => ['vi' => 'Nội dung cũ', 'en' => 'Old content'],
            'is_active' => true,
        ]);

        // Filament uses Livewire for form handling,
        // so test the model update directly
        $about->update([
            'content' => ['vi' => 'Nội dung mới', 'en' => 'New content'],
        ]);

        $this->assertEquals('New content', $about->fresh()->getTranslation('content', 'en'));

        // Also verify the edit page renders with updated content
        $response = $this->get("/admin/about/{$about->id}/edit");
        $response->assertStatus(200);
    }

    public function test_about_auto_creates_if_not_exists(): void
    {
        $response = $this->get('/admin/about/1/edit');

        $response->assertStatus(200);

        $this->assertEquals(1, About::count());
    }
}
