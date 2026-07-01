@extends('layouts.app')

@section('title', $project->title)
@section('meta_description', strip_tags($project->description ?: Str::limit($project->content ?? '', 160)))

@push('styles')
<style>
.project-gallery-main {
    position: relative;
    background: #f8f9fa;
    border-radius: 8px;
    overflow: hidden;
    margin-bottom: 1rem;
}
.project-gallery-main img {
    width: 100%;
    height: auto;
    display: block;
}
.gallery-nav {
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    background: rgba(255,255,255,0.9);
    border: none;
    width: 40px;
    height: 40px;
    border-radius: 50%;
    font-size: 1.2rem;
    cursor: pointer;
    z-index: 2;
    transition: all 0.3s;
}
.gallery-nav:hover {
    background: var(--bs-primary);
    color: #fff;
}
.gallery-nav-prev { left: 10px; }
.gallery-nav-next { right: 10px; }
.project-gallery-thumbs {
    display: flex;
    gap: 10px;
    overflow-x: auto;
    padding-bottom: 5px;
}
.project-thumb {
    flex: 0 0 80px;
    cursor: pointer;
    border: 2px solid transparent;
    border-radius: 6px;
    overflow: hidden;
    transition: border-color 0.3s;
}
.project-thumb.active,
.project-thumb:hover { border-color: var(--bs-primary); }
.project-thumb img {
    width: 100%;
    height: 70px;
    object-fit: cover;
}
</style>
@endpush

@section('content')
<!-- Page Header Start -->
<div class="container-fluid page-header py-5 wow fadeIn" data-wow-delay="0.1s">
    <div class="container text-center py-5">
        <h1 class="display-2 text-dark mb-4 animated slideInDown">{{ __('navigation.projects') }}</h1>
        <nav aria-label="breadcrumb animated slideInDown">
            <ol class="breadcrumb justify-content-center mb-0">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('navigation.home') }}</a></li>
                <li class="breadcrumb-item"><a href="{{ route('projects.index') }}">{{ __('navigation.projects') }}</a></li>
                <li class="breadcrumb-item text-dark" aria-current="page">{{ $project->title }}</li>
            </ol>
        </nav>
    </div>
</div>
<!-- Page Header End -->

<!-- Project Detail Start -->
<div class="container-fluid py-5">
    <div class="container py-5">
        <div class="row g-5">
            <!-- Project Gallery -->
            <div class="col-lg-7">
                <div class="project-gallery wow fadeInUp" data-wow-delay="0.1s">
                    <div class="project-gallery-main">
                        @php $mainImage = $project->images[0] ?? null; @endphp
                        <img loading="lazy" src="{{ $mainImage ? asset('storage/' . $mainImage) : asset('img/product-1.jpg') }}" alt="{{ $project->title }}" id="mainImage">
                        <button class="gallery-nav gallery-nav-prev" onclick="prevImage()" aria-label="{{ __('common.gallery_prev') }}">
                            <i class="bi bi-chevron-left"></i>
                        </button>
                        <button class="gallery-nav gallery-nav-next" onclick="nextImage()" aria-label="{{ __('common.gallery_next') }}">
                            <i class="bi bi-chevron-right"></i>
                        </button>
                    </div>
                    @if(count($project->images ?? []) > 1)
                    <div class="project-gallery-thumbs" id="galleryThumbs">
                        @foreach($project->images as $index => $image)
                            <div class="project-thumb {{ $index === 0 ? 'active' : '' }}" onclick="selectImage({{ $index }})">
                                <img loading="lazy" src="{{ asset('storage/' . $image) }}" alt="{{ __('common.gallery_image') }} {{ $index + 1 }}">
                            </div>
                        @endforeach
                    </div>
                    @endif
                </div>
            </div>

            <!-- Project Info Sidebar -->
            <div class="col-lg-5">
                <div class="wow fadeInUp" data-wow-delay="0.2s">
                    <h1 class="mb-3">{{ $project->title }}</h1>

                    @if($project->description)
                        <p class="lead mb-4">{{ $project->description }}</p>
                    @endif

                    @if($project->content)
                        <div class="project-content">
                            {!! $project->content !!}
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Back Navigation -->
        <div class="mt-5 wow fadeInUp" data-wow-delay="0.3s">
            <a href="{{ route('projects.index') }}" class="btn btn-primary rounded-pill py-2 px-4">
                <i class="bi bi-arrow-left me-1"></i> {{ __('common.back_to_projects') }}
            </a>
        </div>
    </div>
</div>
<!-- Project Detail End -->
@endsection

@push('scripts')
<script>
    @if(count($project->images ?? []) > 0)
    var galleryImages = {!! json_encode($project->images) !!};
    @else
    var galleryImages = ['{{ asset("img/product-1.jpg") }}'];
    @endif
    var currentIndex = 0;

    function selectImage(index) {
        currentIndex = index;
        document.getElementById('mainImage').src = '{{ asset("storage/") }}/' + galleryImages[currentIndex];
        document.querySelectorAll('.project-thumb').forEach(function(thumb, i) {
            thumb.classList.toggle('active', i === currentIndex);
        });
    }

    function nextImage() {
        var next = (currentIndex + 1) % galleryImages.length;
        selectImage(next);
    }

    function prevImage() {
        var prev = (currentIndex - 1 + galleryImages.length) % galleryImages.length;
        selectImage(prev);
    }
</script>
@endpush
