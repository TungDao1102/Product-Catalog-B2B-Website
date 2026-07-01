<?php

namespace App\Http\Controllers;

use App\Models\About;

class AboutController extends Controller
{
    public function index()
    {
        $about = About::first();

        if (! $about || ! $about->is_active) {
            abort(404);
        }

        $seo = [
            'title' => __('navigation.about'),
            'description' => __('seo.about_description'),
            'image' => $about->image ? asset('storage/' . $about->image) : asset('img/og-default.jpg'),
            'type' => 'website',
        ];

        return view('about.index', compact('about', 'seo'));
    }
}
