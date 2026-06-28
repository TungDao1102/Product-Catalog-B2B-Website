@extends('layouts.app')

@section('title', $post->title)
@section('meta_description', strip_tags($post->excerpt ?: Str::limit($post->content, 160)))

@section('content')
<!-- Page Header Start -->
<div class="container-fluid page-header py-5 wow fadeIn" data-wow-delay="0.1s">
    <div class="container text-center py-5">
        <h1 class="display-2 text-dark mb-4 animated slideInDown">Tin tức</h1>
        <nav aria-label="breadcrumb animated slideInDown">
            <ol class="breadcrumb justify-content-center mb-0">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Trang chủ</a></li>
                <li class="breadcrumb-item"><a href="{{ route('posts.index') }}">Tin tức</a></li>
                <li class="breadcrumb-item text-dark" aria-current="page">{{ $post->title }}</li>
            </ol>
        </nav>
    </div>
</div>
<!-- Page Header End -->

<!-- Post Detail Start -->
<div class="container-fluid py-5">
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="wow fadeInUp" data-wow-delay="0.1s">
                    <h1 class="mb-3">{{ $post->title }}</h1>
                    <p class="text-muted mb-4">
                        <i class="bi bi-calendar me-1"></i>
                        {{ $post->published_at ? $post->published_at->format('d/m/Y') : '' }}
                    </p>

                    @if($post->image)
                        <div class="mb-4">
                            <img src="{{ asset('storage/' . $post->image) }}" alt="{{ $post->title }}" class="img-fluid rounded">
                        </div>
                    @endif

                    @if($post->excerpt)
                        <p class="lead mb-4 fst-italic">{{ $post->excerpt }}</p>
                    @endif

                    <div class="post-content">
                        {!! $post->content !!}
                    </div>

                    <div class="mt-5 pt-3 border-top">
                        <a href="{{ route('posts.index') }}" class="btn btn-primary rounded-pill py-2 px-4">
                            <i class="bi bi-arrow-left me-1"></i> Quay lại danh sách tin tức
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Post Detail End -->
@endsection
