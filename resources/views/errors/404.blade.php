@extends('layouts.app')

@section('title', '404 - Không tìm thấy trang')
@section('meta_description', 'Trang bạn tìm kiếm không tồn tại')

@section('content')
<!-- Page Header Start -->
<div class="container-fluid page-header py-5 wow fadeIn" data-wow-delay="0.1s">
    <div class="container text-center py-5">
        <h1 class="display-2 text-dark mb-4 animated slideInDown">404</h1>
        <nav aria-label="breadcrumb animated slideInDown">
            <ol class="breadcrumb justify-content-center mb-0">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Trang chủ</a></li>
                <li class="breadcrumb-item text-dark" aria-current="page">404</li>
            </ol>
        </nav>
    </div>
</div>
<!-- Page Header End -->

<!-- 404 Start -->
<div class="container-xxl py-5 wow fadeInUp" data-wow-delay="0.1s">
    <div class="container text-center">
        <div class="row justify-content-center">
            <div class="col-lg-6">
                <i class="bi bi-exclamation-triangle display-1 text-primary"></i>
                <h1 class="display-1">404</h1>
                <h1 class="mb-4">Không tìm thấy trang</h1>
                <p class="mb-4">Trang bạn đang tìm kiếm không tồn tại hoặc đã bị di chuyển. Vui lòng quay lại trang chủ hoặc sử dụng tìm kiếm.</p>
                <a class="btn btn-primary rounded-pill py-3 px-5" href="{{ route('home') }}">Quay lại trang chủ</a>
            </div>
        </div>
    </div>
</div>
<!-- 404 End -->
@endsection
