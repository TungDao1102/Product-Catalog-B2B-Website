@extends('layouts.app')

@section('title', __('navigation.about'))
@section('meta_description', __('seo.about_description'))

@section('content')
<!-- Page Header Start -->
<div class="container-fluid page-header py-5 wow fadeIn" data-wow-delay="0.1s">
    <div class="container text-center py-5">
        <h1 class="display-2 text-dark mb-4 animated slideInDown">{{ __('navigation.about') }}</h1>
        <nav aria-label="breadcrumb animated slideInDown">
            <ol class="breadcrumb justify-content-center mb-0">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('navigation.home') }}</a></li>
                <li class="breadcrumb-item text-dark" aria-current="page">{{ __('navigation.about') }}</li>
            </ol>
        </nav>
    </div>
</div>
<!-- Page Header End -->

<!-- About Content Start -->
@if($about->content)
<div class="container-xxl py-5">
    <div class="container">
        <div class="row g-5">
            <div class="col-lg-12 wow fadeIn" data-wow-delay="0.1s">
                <div class="section-title text-center mx-auto" style="max-width: 800px;">
                    <p class="fs-5 fw-medium fst-italic text-primary">{{ __('navigation.about') }}</p>
                </div>
                <div class="about-content">
                    {!! $about->content !!}
                </div>
            </div>
        </div>
    </div>
</div>
@endif

<!-- Mission & Vision Start -->
@if($about->mission || $about->vision)
<div class="container-fluid bg-light py-5">
    <div class="container py-5">
        <div class="row g-5">
            @if($about->mission)
            <div class="col-lg-6 wow fadeIn" data-wow-delay="0.1s">
                <div class="section-title">
                    <p class="fs-5 fw-medium fst-italic text-primary">{{ __('about.mission') }}</p>
                </div>
                <div class="mission-content">
                    {!! $about->mission !!}
                </div>
            </div>
            @endif
            @if($about->vision)
            <div class="col-lg-6 wow fadeIn" data-wow-delay="0.3s">
                <div class="section-title">
                    <p class="fs-5 fw-medium fst-italic text-primary">{{ __('about.vision') }}</p>
                </div>
                <div class="vision-content">
                    {!! $about->vision !!}
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endif

<!-- Values Start -->
@if($about->values)
<div class="container-xxl py-5">
    <div class="container">
        <div class="section-title text-center mx-auto wow fadeInUp" data-wow-delay="0.1s" style="max-width: 500px;">
            <p class="fs-5 fw-medium fst-italic text-primary">{{ __('about.core_values') }}</p>
            <h1 class="display-6">{{ __('about.core_values_title') }}</h1>
        </div>
        <div class="row g-4">
            @foreach(explode("\n", strip_tags($about->values)) as $value)
                @if(trim($value))
                <div class="col-lg-4 col-md-6 wow fadeInUp" data-wow-delay="0.1s">
                    <div class="bg-white shadow-sm rounded p-4 text-center h-100">
                        <p class="mb-0">{{ trim($value) }}</p>
                    </div>
                </div>
                @endif
            @endforeach
        </div>
    </div>
</div>
@endif

<!-- History Start -->
@if($about->history)
<div class="container-fluid bg-light py-5">
    <div class="container py-5">
        <div class="section-title text-center mx-auto wow fadeInUp" data-wow-delay="0.1s" style="max-width: 500px;">
            <p class="fs-5 fw-medium fst-italic text-primary">{{ __('about.history') }}</p>
            <h1 class="display-6">{{ __('about.history_title') }}</h1>
        </div>
        <div class="row justify-content-center">
            <div class="col-lg-8 wow fadeIn" data-wow-delay="0.3s">
                <div class="history-content">
                    {!! $about->history !!}
                </div>
            </div>
        </div>
    </div>
</div>
@endif
@endsection
