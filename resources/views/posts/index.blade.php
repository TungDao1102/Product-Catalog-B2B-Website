@extends('layouts.app')

@section('title', __('navigation.posts'))
@section('meta_description', __('seo.posts_title'))

@section('content')
<!-- Page Header Start -->
<div class="container-fluid page-header py-5 wow fadeIn" data-wow-delay="0.1s">
    <div class="container text-center py-5">
        <h1 class="display-2 text-dark mb-4 animated slideInDown">{{ __('navigation.posts') }}</h1>
        <nav aria-label="breadcrumb animated slideInDown">
            <ol class="breadcrumb justify-content-center mb-0">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('navigation.home') }}</a></li>
                <li class="breadcrumb-item text-dark" aria-current="page">{{ __('navigation.posts') }}</li>
            </ol>
        </nav>
    </div>
</div>
<!-- Page Header End -->

<!-- Posts Section Start -->
<div class="container-fluid py-5">
    <div class="container py-5">
        <div class="row g-4">
            @forelse($posts as $post)
                <div class="col-lg-4 col-md-6 wow fadeInUp" data-wow-delay="0.1s">
                    <div class="product-card-b2b">
                        <img class="card-img" loading="lazy" src="{{ $post->image ? asset('storage/' . $post->image) : asset('img/product-1.jpg') }}" alt="{{ $post->title }}">
                        <div class="card-body">
                            <p class="text-muted small mb-2">
                                <i class="bi bi-calendar me-1"></i>
                                {{ $post->published_at ? $post->published_at->format('d/m/Y') : '' }}
                            </p>
                            <h5 class="card-title">
                                <a href="{{ route('posts.show', $post->slug) }}">{{ $post->title }}</a>
                            </h5>
                            <p class="card-text">{{ Str::limit(strip_tags($post->excerpt ?: $post->content), 120) }}</p>
                        </div>
                        <div class="card-footer">
                            <a href="{{ route('posts.show', $post->slug) }}" class="btn-detail">
                                <i class="bi bi-eye me-1"></i> {{ __('common.read_more') }}
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12 text-center py-5">
                    <i class="bi bi-newspaper" style="font-size: 3rem; color: #ddd;"></i>
                    <p class="text-muted mt-3">{{ __('common.no_posts') }}</p>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        <div class="pagination-b2b wow fadeInUp mt-5" data-wow-delay="0.3s">
            {{ $posts->links() }}
        </div>
    </div>
</div>
<!-- Posts Section End -->
@endsection
