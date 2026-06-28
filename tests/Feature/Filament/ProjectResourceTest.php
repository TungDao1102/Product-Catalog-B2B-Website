<?php

namespace Tests\Feature\Filament;

use App\Models\Project;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProjectResourceTest extends TestCase
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

    public function test_can_list_projects(): void
    {
        Project::create([
            'title' => 'Test Project',
            'slug' => 'test-project',
            'content' => 'Project description',
            'is_active' => true,
        ]);

        $response = $this->get('/admin/projects');

        $response->assertStatus(200);
        $response->assertSee('Test Project');
    }

    public function test_can_render_create_page(): void
    {
        $response = $this->get('/admin/projects/create');

        $response->assertStatus(200);
    }

    public function test_can_render_edit_page(): void
    {
        $project = Project::create([
            'title' => 'Edit Project',
            'slug' => 'edit-project',
            'content' => 'Content to edit',
        ]);

        $response = $this->get("/admin/projects/{$project->id}/edit");

        $response->assertStatus(200);
    }
}
