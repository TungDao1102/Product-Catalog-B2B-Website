<?php

namespace Tests\Feature;

use App\Models\Post;
use App\Models\Project;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;

class SeederTest extends TestCase
{
    use DatabaseMigrations;

    public function test_post_seeder_creates_posts(): void
    {
        Artisan::call('db:seed', ['--class' => 'DatabaseSeeder']);

        $this->assertGreaterThanOrEqual(3, Post::count());
    }

    public function test_project_seeder_creates_projects(): void
    {
        Artisan::call('db:seed', ['--class' => 'DatabaseSeeder']);

        $this->assertGreaterThanOrEqual(3, Project::count());
    }
}
