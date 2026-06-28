<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Support\Str;

class PostController extends Controller
{
    public function index()
    {
        $posts = Post::where('is_published', true)
            ->orderBy('published_at', 'desc')
            ->paginate(9);

        $seo = [
            'title' => __('navigation.posts'),
            'description' => __('seo.posts_title'),
            'image' => asset('img/og-default.jpg'),
            'type' => 'website',
        ];

        return view('posts.index', compact('posts', 'seo'));
    }

    public function show(string $slug)
    {
        $post = Post::where('slug', $slug)
            ->where('is_published', true)
            ->firstOrFail();

        $seo = [
            'title' => $post->getTranslation('title', app()->getLocale()),
            'description' => strip_tags($post->getTranslation('excerpt', app()->getLocale()) ?: Str::limit(strip_tags($post->getTranslation('content', app()->getLocale())), 160)),
            'image' => $post->image ? asset('storage/' . $post->image) : asset('img/og-default.jpg'),
            'type' => 'article',
        ];

        return view('posts.show', compact('post', 'seo'));
    }
}
