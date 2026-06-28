<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Support\Str;

class ProjectController extends Controller
{
    public function index()
    {
        $projects = Project::where('is_active', true)
            ->orderBy('created_at', 'desc')
            ->paginate(9);

        $seo = [
            'title' => __('navigation.projects'),
            'description' => __('seo.projects_title'),
            'image' => asset('img/og-default.jpg'),
            'type' => 'website',
        ];

        return view('projects.index', compact('projects', 'seo'));
    }

    public function show(string $slug)
    {
        $project = Project::where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        $seo = [
            'title' => $project->getTranslation('title', app()->getLocale()),
            'description' => strip_tags($project->getTranslation('description', app()->getLocale()) ?: Str::limit(strip_tags($project->getTranslation('content', app()->getLocale())), 160)),
            'image' => isset($project->images[0]) ? asset('storage/' . $project->images[0]) : asset('img/og-default.jpg'),
            'type' => 'article',
        ];

        return view('projects.show', compact('project', 'seo'));
    }
}
