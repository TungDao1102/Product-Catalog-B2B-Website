<?php

namespace Tests\Feature\Http;

use App\Models\Post;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class PostFrontendTest extends TestCase
{
    use DatabaseMigrations;

    public function test_posts_index_page_renders(): void
    {
        $response = $this->get(route('posts.index'));

        $response->assertStatus(200);
        $response->assertSee('Tin tức');
    }

    public function test_posts_index_shows_published_posts(): void
    {
        $post = Post::create([
            'title' => 'Bài viết test',
            'slug' => 'bai-viet-test',
            'content' => 'Nội dung bài viết test',
            'is_published' => true,
            'published_at' => now(),
        ]);

        $response = $this->get(route('posts.index'));

        $response->assertStatus(200);
        $response->assertSee('Bài viết test');
    }

    public function test_posts_index_hides_unpublished_posts(): void
    {
        $post = Post::create([
            'title' => 'Bài viết chưa xuất bản',
            'slug' => 'bai-viet-chua-xuat-ban',
            'content' => 'Nội dung',
            'is_published' => false,
        ]);

        $response = $this->get(route('posts.index'));

        $response->assertStatus(200);
        $response->assertDontSee('Bài viết chưa xuất bản');
    }

    public function test_post_show_page_renders(): void
    {
        $post = Post::create([
            'title' => 'Bài viết chi tiết',
            'slug' => 'bai-viet-chi-tiet',
            'content' => 'Nội dung chi tiết bài viết',
            'is_published' => true,
            'published_at' => now(),
        ]);

        $response = $this->get(route('posts.show', $post->slug));

        $response->assertStatus(200);
        $response->assertSee('Bài viết chi tiết');
    }

    public function test_post_show_returns_404_for_unpublished(): void
    {
        $post = Post::create([
            'title' => 'Bài viết ẩn',
            'slug' => 'bai-viet-an',
            'content' => 'Nội dung',
            'is_published' => false,
        ]);

        $response = $this->get(route('posts.show', $post->slug));

        $response->assertStatus(404);
    }
}
