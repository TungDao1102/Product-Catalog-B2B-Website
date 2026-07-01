@extends('layouts.app')

@section('title', __('errors.419_title'))
@section('meta_description', __('errors.419_description'))

@section('content')
<!-- Page Header Start -->
<div class="container-fluid page-header py-5 wow fadeIn" data-wow-delay="0.1s">
    <div class="container text-center py-5">
        <h1 class="display-2 text-dark mb-4 animated slideInDown">419</h1>
        <nav aria-label="breadcrumb animated slideInDown">
            <ol class="breadcrumb justify-content-center mb-0">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('navigation.home') }}</a></li>
                <li class="breadcrumb-item text-dark" aria-current="page">419</li>
            </ol>
        </nav>
    </div>
</div>
<!-- Page Header End -->

<!-- 419 Start -->
<div class="container-xxl py-5 wow fadeInUp" data-wow-delay="0.1s">
    <div class="container text-center">
        <div class="row justify-content-center">
            <div class="col-lg-6">
                <i class="bi bi-clock-history display-1 text-primary"></i>
                <h1 class="display-1">419</h1>
                <h1 class="mb-4">{{ __('errors.419_heading') }}</h1>
                <p class="mb-4">{{ __('errors.419_message') }}</p>
                <a class="btn btn-primary rounded-pill py-3 px-5" href="{{ route('home') }}">{{ __('errors.back_home') }}</a>
            </div>
        </div>
    </div>
</div>
<!-- 419 End -->
@endsection
