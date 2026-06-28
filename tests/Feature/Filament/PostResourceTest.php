<?php

namespace Tests\Feature\Filament;

use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PostResourceTest extends TestCase
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

    public function test_can_list_posts(): void
    {
        Post::create([
            'title' => 'Test Post',
            'slug' => 'test-post',
            'content' => 'Test content',
            'is_published' => true,
        ]);

        $response = $this->get('/admin/posts');

        $response->assertStatus(200);
        $response->assertSee('Test Post');
    }

    public function test_can_render_create_page(): void
    {
        $response = $this->get('/admin/posts/create');

        $response->assertStatus(200);
    }

    public function test_can_render_edit_page(): void
    {
        $post = Post::create([
            'title' => 'Edit Post',
            'slug' => 'edit-post',
            'content' => 'Content to edit',
        ]);

        $response = $this->get("/admin/posts/{$post->id}/edit");

        $response->assertStatus(200);
    }
}
