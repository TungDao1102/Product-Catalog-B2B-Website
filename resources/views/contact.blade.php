@extends('layouts.app')

@section('title', __('navigation.contact'))
@section('meta_description', __('seo.contact_description'))

@section('content')
<!-- Page Header Start -->
<div class="container-fluid page-header py-5 wow fadeIn" data-wow-delay="0.1s">
    <div class="container text-center py-5">
        <h1 class="display-2 text-dark mb-4 animated slideInDown">{{ __('navigation.contact') }}</h1>
        <nav aria-label="breadcrumb animated slideInDown">
            <ol class="breadcrumb justify-content-center mb-0">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('navigation.home') }}</a></li>
                <li class="breadcrumb-item text-dark" aria-current="page">{{ __('navigation.contact') }}</li>
            </ol>
        </nav>
    </div>
</div>
<!-- Page Header End -->

<!-- Contact Start -->
<div class="container-xxl contact py-5">
    <div class="container">
        <div class="section-title text-center mx-auto wow fadeInUp" data-wow-delay="0.1s" style="max-width: 500px;">
            <p class="fs-5 fw-medium fst-italic text-primary">{{ __('navigation.contact') }}</p>
            <h1 class="display-6">{{ __('common.contact_form_subtitle') }}</h1>
        </div>
        <div class="row g-5 mb-5">
            <div class="col-md-4 text-center wow fadeInUp" data-wow-delay="0.3s">
                <div class="btn-square mx-auto mb-3">
                    <i class="fa fa-envelope fa-2x text-white"></i>
                </div>
                <p class="mb-2">info@example.com</p>
                <p class="mb-0">sales@example.com</p>
            </div>
            <div class="col-md-4 text-center wow fadeInUp" data-wow-delay="0.4s">
                <div class="btn-square mx-auto mb-3">
                    <i class="fa fa-phone fa-2x text-white"></i>
                </div>
                <p class="mb-2">+012 345 67890</p>
                <p class="mb-0">+012 345 67891</p>
            </div>
            <div class="col-md-4 text-center wow fadeInUp" data-wow-delay="0.5s">
                <div class="btn-square mx-auto mb-3">
                    <i class="fa fa-map-marker-alt fa-2x text-white"></i>
                </div>
                <p class="mb-2">{{ __('common.address_line_1') }}</p>
                <p class="mb-0">{{ __('common.address_line_2') }}</p>
            </div>
        </div>
        <div class="row g-5">
            <div class="col-lg-6 wow fadeInUp" data-wow-delay="0.1s">
                <h3 class="mb-4">{{ __('common.contact_form_title') }}</h3>
                <p class="mb-4">{{ __('common.contact_form_intro') }}</p>

                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <form action="{{ route('contact') }}" method="POST">
                    @csrf
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" placeholder="{{ __('common.full_name') }}" value="{{ old('name') }}" required>
                                <label for="name">{{ __('common.full_name') }}</label>
                                @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" placeholder="{{ __('common.email') }}" value="{{ old('email') }}" required>
                                <label for="email">{{ __('common.email') }}</label>
                                @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="text" class="form-control @error('phone') is-invalid @enderror" id="phone" name="phone" placeholder="{{ __('common.phone') }}" value="{{ old('phone') }}">
                                <label for="phone">{{ __('common.phone') }}</label>
                                @error('phone') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="text" class="form-control @error('company') is-invalid @enderror" id="company" name="company" placeholder="{{ __('common.company') }}" value="{{ old('company') }}">
                                <label for="company">{{ __('common.company') }}</label>
                                @error('company') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-floating">
                                <textarea class="form-control @error('message') is-invalid @enderror" placeholder="{{ __('common.message') }}" id="message" name="message" style="height: 120px" required>{{ old('message') }}</textarea>
                                <label for="message">{{ __('common.message') }}</label>
                                @error('message') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>
                        <div class="col-12">
                            <button class="btn btn-primary rounded-pill py-3 px-5" type="submit">{{ __('common.send_contact') }}</button>
                        </div>
                    </div>
                </form>
            </div>
            <div class="col-lg-6 wow fadeInUp" data-wow-delay="0.5s">
                <div class="h-100">
                    <iframe class="w-100 rounded"
                    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3919.424319696152!2d106.693339!3d10.775889!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x31752f47e3f6d5c5%3A0x5f2e8a3c5f0e8a3c!2sHo%20Chi%20Minh%20City!5e0!3m2!1sen!2s!4v1"
                    frameborder="0" style="height: 100%; min-height: 300px; border:0;" allowfullscreen="" aria-hidden="false"
                    tabindex="0"></iframe>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Contact End -->
@endsection
