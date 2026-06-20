@extends('layouts.app')

@section('title', 'Trang chủ')

@section('content')
<!-- Carousel Start -->
<div class="container-fluid px-0 mb-5">
    <div id="header-carousel" class="carousel slide carousel-fade" data-bs-ride="carousel">
        <div class="carousel-inner">
            <div class="carousel-item active">
                <img class="w-100" src="{{ asset('img/carousel-1.jpg') }}" alt="Image">
                <div class="carousel-caption">
                    <div class="container">
                        <div class="row justify-content-center">
                            <div class="col-lg-7 text-center">
                                <p class="fs-4 text-white animated zoomIn">Chào mừng đến với <strong class="text-dark">{{ config('app.name') }}</strong></p>
                                <h1 class="display-1 text-dark mb-4 animated zoomIn">Giải phép sản phẩm chất lượng cho doanh nghiệp</h1>
                                <a href="{{ route('products.index') }}" class="btn btn-light rounded-pill py-3 px-5 animated zoomIn">Xem sản phẩm</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="carousel-item">
                <img class="w-100" src="{{ asset('img/carousel-2.jpg') }}" alt="Image">
                <div class="carousel-caption">
                    <div class="container">
                        <div class="row justify-content-center">
                            <div class="col-lg-7 text-center">
                                <p class="fs-4 text-white animated zoomIn">Chào mừng đến với <strong class="text-dark">{{ config('app.name') }}</strong></p>
                                <h1 class="display-1 text-dark mb-4 animated zoomIn">Đối tác tin cậy trong mọi lĩnh vực</h1>
                                <a href="{{ route('products.index') }}" class="btn btn-light rounded-pill py-3 px-5 animated zoomIn">Khám phá ngay</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <button class="carousel-control-prev" type="button" data-bs-target="#header-carousel" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Previous</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#header-carousel" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Next</span>
        </button>
    </div>
</div>
<!-- Carousel End -->

<!-- Featured Products Start -->
<div class="container-fluid product py-5 my-5">
    <div class="container py-5">
        <div class="section-title text-center mx-auto wow fadeInUp" data-wow-delay="0.1s" style="max-width: 500px;">
            <p class="fs-5 fw-medium fst-italic text-primary">Sản phẩm nổi bật</p>
            <h1 class="display-6">Sản phẩm tiêu biểu</h1>
        </div>
        <div class="owl-carousel product-carousel wow fadeInUp" data-wow-delay="0.5s">
            @forelse($featuredProducts as $product)
                <a href="{{ route('products.show', $product->slug) }}" class="d-block product-item rounded">
                    <img src="{{ $product->images[0] ?? asset('img/product-1.jpg') }}" alt="{{ $product->name }}">
                    <div class="bg-white shadow-sm text-center p-4 position-relative mt-n5 mx-4">
                        <h4 class="text-primary">{{ $product->name }}</h4>
                        <span class="text-body">{{ $product->short_description }}</span>
                    </div>
                </a>
            @empty
                <a href="" class="d-block product-item rounded">
                    <img src="{{ asset('img/product-1.jpg') }}" alt="">
                    <div class="bg-white shadow-sm text-center p-4 position-relative mt-n5 mx-4">
                        <h4 class="text-primary">Sản phẩm 1</h4>
                        <span class="text-body">Thông tin sản phẩm đang cập nhật</span>
                    </div>
                </a>
                <a href="" class="d-block product-item rounded">
                    <img src="{{ asset('img/product-2.jpg') }}" alt="">
                    <div class="bg-white shadow-sm text-center p-4 position-relative mt-n5 mx-4">
                        <h4 class="text-primary">Sản phẩm 2</h4>
                        <span class="text-body">Thông tin sản phẩm đang cập nhật</span>
                    </div>
                </a>
            @endforelse
        </div>
    </div>
</div>
<!-- Featured Products End -->

<!-- About Start -->
<div class="container-xxl py-5">
    <div class="container">
        <div class="row g-5">
            <div class="col-lg-6">
                <div class="row g-3">
                    <div class="col-6 text-end">
                        <img class="img-fluid bg-white w-100 mb-3 wow fadeIn" data-wow-delay="0.1s" src="{{ asset('img/about-1.jpg') }}" alt="">
                        <img class="img-fluid bg-white w-50 wow fadeIn" data-wow-delay="0.2s" src="{{ asset('img/about-3.jpg') }}" alt="">
                    </div>
                    <div class="col-6">
                        <img class="img-fluid bg-white w-50 mb-3 wow fadeIn" data-wow-delay="0.3s" src="{{ asset('img/about-4.jpg') }}" alt="">
                        <img class="img-fluid bg-white w-100 wow fadeIn" data-wow-delay="0.4s" src="{{ asset('img/about-2.jpg') }}" alt="">
                    </div>
                </div>
            </div>
            <div class="col-lg-6 wow fadeIn" data-wow-delay="0.5s">
                <div class="section-title">
                    <p class="fs-5 fw-medium fst-italic text-primary">Về chúng tôi</p>
                    <h1 class="display-6">Đối tác cung cấp thiết bị hàng đầu</h1>
                </div>
                <div class="row g-3 mb-4">
                    <div class="col-sm-4">
                        <img class="img-fluid bg-white w-100" src="{{ asset('img/about-5.jpg') }}" alt="">
                    </div>
                    <div class="col-sm-8">
                        <h5>Cam kết chất lượng và uy tín</h5>
                        <p class="mb-0">Chúng tôi chuyên cung cấp các sản phẩm và giải pháp chất lượng cao cho doanh nghiệp trong nhiều lĩnh vực.</p>
                    </div>
                </div>
                <div class="border-top mb-4"></div>
                <div class="row g-3">
                    <div class="col-sm-8">
                        <h5>Hơn 15 năm kinh nghiệm</h5>
                        <p class="mb-0">Với đội ngũ chuyên nghiệp và giàu kinh nghiệm, chúng tôi đã đồng hành cùng hàng trăm dự án lớn nhỏ.</p>
                    </div>
                    <div class="col-sm-4">
                        <img class="img-fluid bg-white w-100" src="{{ asset('img/about-6.jpg') }}" alt="">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- About End -->

<!-- Store Section Start -->
<div class="container-xxl py-5">
    <div class="container">
        <div class="section-title text-center mx-auto wow fadeInUp" data-wow-delay="0.1s" style="max-width: 500px;">
            <p class="fs-5 fw-medium fst-italic text-primary">Danh mục sản phẩm</p>
            <h1 class="display-6">Sản phẩm tiêu biểu</h1>
        </div>
        <div class="row g-4">
            @forelse($latestProducts as $product)
                <div class="col-lg-4 col-md-6 wow fadeInUp" data-wow-delay="0.1s">
                    <div class="product-card-b2b text-start">
                        @if($product->is_featured)
                            <span class="product-badge">Nổi bật</span>
                        @endif
                        <img class="card-img" src="{{ $product->images[0] ?? asset('img/product-1.jpg') }}" alt="{{ $product->name }}">
                        <div class="card-body">
                            <div class="card-brand">{{ $product->brand->name ?? '' }}</div>
                            <h5 class="card-title"><a href="{{ route('products.show', $product->slug) }}">{{ $product->name }}</a></h5>
                            <p class="card-text">{{ $product->short_description }}</p>
                        </div>
                        <div class="card-footer">
                            <a href="{{ route('products.show', $product->slug) }}" class="btn-detail"><i class="bi bi-eye me-1"></i> Chi tiết</a>
                            <a href="{{ route('contact') }}?product={{ $product->slug }}" class="btn-quote-sm"><i class="bi bi-envelope me-1"></i> Báo giá</a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12 text-center">
                    <p class="text-muted">Chưa có sản phẩm nào.</p>
                </div>
            @endforelse
            <div class="col-12 text-center wow fadeInUp" data-wow-delay="0.1s">
                <a href="{{ route('products.index') }}" class="btn btn-primary rounded-pill py-3 px-5">Xem tất cả sản phẩm</a>
            </div>
        </div>
    </div>
</div>
<!-- Store Section End -->

<!-- Article Start -->
<div class="container-xxl py-5">
    <div class="container">
        <div class="row g-5">
            <div class="col-lg-5 wow fadeIn" data-wow-delay="0.1s">
                <img class="img-fluid" src="{{ asset('img/article.jpg') }}" alt="">
            </div>
            <div class="col-lg-6 wow fadeIn" data-wow-delay="0.5s">
                <div class="section-title">
                    <p class="fs-5 fw-medium fst-italic text-primary">Bài viết nổi bật</p>
                    <h1 class="display-6">Giải pháp tối ưu cho doanh nghiệp</h1>
                </div>
                <p class="mb-4">Chúng tôi cung cấp các sản phẩm và giải pháp công nghệ tiên tiến, đáp ứng nhu cầu đa dạng của khách hàng trong nhiều lĩnh vực khác nhau.</p>
                <p class="mb-4">Đội ngũ kỹ thuật giàu kinh nghiệm, sẵn sàng tư vấn và hỗ trợ khách hàng lựa chọn sản phẩm phù hợp nhất.</p>
                <a href="" class="btn btn-primary rounded-pill py-3 px-5">Đọc thêm</a>
            </div>
        </div>
    </div>
</div>
<!-- Article End -->
@endsection

@push('scripts')
<script>
    $('.product-carousel').owlCarousel({
        autoplay: true,
        smartSpeed: 1000,
        margin: 45,
        dots: false,
        loop: true,
        nav: true,
        navText: [
            '<i class="bi bi-arrow-left"></i>',
            '<i class="bi bi-arrow-right"></i>'
        ],
        responsive: {
            0:{ items:1 },
            768:{ items:2 },
            992:{ items:3 }
        }
    });
</script>
@endpush
