@extends('layouts.app')

@section('title', __('navigation.projects'))
@section('meta_description', __('seo.projects_title'))

@section('content')
<!-- Page Header Start -->
<div class="container-fluid page-header py-5 wow fadeIn" data-wow-delay="0.1s">
    <div class="container text-center py-5">
        <h1 class="display-2 text-dark mb-4 animated slideInDown">{{ __('navigation.projects') }}</h1>
        <nav aria-label="breadcrumb animated slideInDown">
            <ol class="breadcrumb justify-content-center mb-0">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('navigation.home') }}</a></li>
                <li class="breadcrumb-item text-dark" aria-current="page">{{ __('navigation.projects') }}</li>
            </ol>
        </nav>
    </div>
</div>
<!-- Page Header End -->

<!-- Projects Section Start -->
<div class="container-fluid py-5">
    <div class="container py-5">
        <div class="row g-4">
            @forelse($projects as $project)
                <div class="col-lg-4 col-md-6 wow fadeInUp" data-wow-delay="0.1s">
                    <div class="product-card-b2b">
                        <img class="card-img" src="{{ isset($project->images[0]) ? asset('storage/' . $project->images[0]) : asset('img/product-1.jpg') }}" alt="{{ $project->title }}">
                        <div class="card-body">
                            <h5 class="card-title">
                                <a href="{{ route('projects.show', $project->slug) }}">{{ $project->title }}</a>
                            </h5>
                            <p class="card-text">{{ Str::limit(strip_tags($project->description ?: ''), 120) }}</p>
                        </div>
                        <div class="card-footer">
                            <a href="{{ route('projects.show', $project->slug) }}" class="btn-detail">
                                <i class="bi bi-eye me-1"></i> {{ __('common.view_detail') }}
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12 text-center py-5">
                    <i class="bi bi-folder-open" style="font-size: 3rem; color: #ddd;"></i>
                    <p class="text-muted mt-3">{{ __('common.no_projects') }}</p>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        <div class="pagination-b2b wow fadeInUp mt-5" data-wow-delay="0.3s">
            {{ $projects->links() }}
        </div>
    </div>
</div>
<!-- Projects Section End -->
@endsection
