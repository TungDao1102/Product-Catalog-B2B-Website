<?php

namespace Tests\Feature\Http;

use App\Models\Project;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class ProjectFrontendTest extends TestCase
{
    use DatabaseMigrations;

    public function test_projects_index_page_renders(): void
    {
        $response = $this->get(route('projects.index'));

        $response->assertStatus(200);
        $response->assertSee('Dự án');
    }

    public function test_projects_index_shows_active_projects(): void
    {
        $project = Project::create([
            'title' => 'Dự án test',
            'slug' => 'du-an-test',
            'description' => 'Mô tả dự án test',
            'content' => 'Nội dung dự án',
            'is_active' => true,
        ]);

        $response = $this->get(route('projects.index'));

        $response->assertStatus(200);
        $response->assertSee('Dự án test');
    }

    public function test_projects_index_hides_inactive_projects(): void
    {
        $project = Project::create([
            'title' => 'Dự án không hoạt động',
            'slug' => 'du-an-khong-hoat-dong',
            'description' => 'Mô tả',
            'content' => 'Nội dung',
            'is_active' => false,
        ]);

        $response = $this->get(route('projects.index'));

        $response->assertStatus(200);
        $response->assertDontSee('Dự án không hoạt động');
    }

    public function test_project_show_page_renders(): void
    {
        $project = Project::create([
            'title' => 'Dự án chi tiết',
            'slug' => 'du-an-chi-tiet',
            'description' => 'Mô tả chi tiết',
            'content' => 'Nội dung chi tiết dự án',
            'is_active' => true,
        ]);

        $response = $this->get(route('projects.show', $project->slug));

        $response->assertStatus(200);
        $response->assertSee('Dự án chi tiết');
    }

    public function test_project_show_returns_404_for_inactive(): void
    {
        $project = Project::create([
            'title' => 'Dự án ẩn',
            'slug' => 'du-an-an',
            'description' => 'Mô tả',
            'content' => 'Nội dung',
            'is_active' => false,
        ]);

        $response = $this->get(route('projects.show', $project->slug));

        $response->assertStatus(404);
    }
}
