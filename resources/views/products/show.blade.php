@extends('layouts.app')

@section('title', $product->name)
@section('meta_keywords', $product->category->name ?? '')
@section('meta_description', $product->short_description ?? '')

@push('styles')
<style>
.product-gallery-main {
    position: relative;
    background: #f8f9fa;
    border-radius: 8px;
    overflow: hidden;
    margin-bottom: 1rem;
}
.product-gallery-main img {
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
.product-gallery-thumbs {
    display: flex;
    gap: 10px;
    overflow-x: auto;
    padding-bottom: 5px;
}
.product-thumb {
    flex: 0 0 80px;
    cursor: pointer;
    border: 2px solid transparent;
    border-radius: 6px;
    overflow: hidden;
    transition: border-color 0.3s;
}
.product-thumb.active,
.product-thumb:hover { border-color: var(--bs-primary); }
.product-thumb img {
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
        <h1 class="display-2 text-dark mb-4 animated slideInDown">Chi tiết sản phẩm</h1>
        <nav aria-label="breadcrumb animated slideInDown">
            <ol class="breadcrumb justify-content-center mb-0">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Trang chủ</a></li>
                <li class="breadcrumb-item"><a href="{{ route('products.index') }}">Sản phẩm</a></li>
                @if($product->category)
                    <li class="breadcrumb-item"><a href="{{ route('categories.show', $product->category->slug) }}">{{ $product->category->name }}</a></li>
                @endif
                <li class="breadcrumb-item text-dark" aria-current="page">{{ $product->name }}</li>
            </ol>
        </nav>
    </div>
</div>
<!-- Page Header End -->

<!-- Product Detail Start -->
<div class="container-fluid py-5">
    <div class="container py-5">
        <div class="row g-5">
            <!-- Product Gallery -->
            <div class="col-lg-7">
                <div class="product-gallery wow fadeInUp" data-wow-delay="0.1s">
                    <div class="product-gallery-main">
                        <img src="{{ $product->images[0] ?? asset('img/product-1.jpg') }}" alt="{{ $product->name }}" id="mainImage">
                        <button class="gallery-nav gallery-nav-prev" onclick="prevImage()" aria-label="Ảnh trước">
                            <i class="bi bi-chevron-left"></i>
                        </button>
                        <button class="gallery-nav gallery-nav-next" onclick="nextImage()" aria-label="Ảnh tiếp">
                            <i class="bi bi-chevron-right"></i>
                        </button>
                    </div>
                    @if(count($product->images ?? []) > 1)
                    <div class="product-gallery-thumbs" id="galleryThumbs">
                        @foreach($product->images as $index => $image)
                            <div class="product-thumb {{ $index === 0 ? 'active' : '' }}" onclick="selectImage({{ $index }})">
                                <img src="{{ $image }}" alt="Ảnh {{ $index + 1 }}">
                            </div>
                        @endforeach
                    </div>
                    @endif
                </div>

                <!-- Product Description -->
                <div class="product-description mt-4 wow fadeInUp" data-wow-delay="0.3s">
                    <h3>Mô tả sản phẩm</h3>
                    <div>{!! nl2br(e($product->description)) !!}</div>
                </div>

                <!-- Technical Specifications -->
                @if($product->technical_specs)
                <div class="product-specs-section mt-4 wow fadeInUp" data-wow-delay="0.4s">
                    <h3>Thông số kỹ thuật</h3>
                    <table class="product-specs">
                        <thead>
                            <tr>
                                <th>Thông số</th>
                                <th>Giá trị</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($product->technical_specs as $spec => $value)
                                <tr>
                                    <td>{{ $spec }}</td>
                                    <td>{{ $value }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @endif

                <!-- Brochure Download -->
                @if($product->brochure)
                <div class="product-brochure mt-4 wow fadeInUp" data-wow-delay="0.5s">
                    <div class="product-brochure-icon">
                        <i class="bi bi-file-pdf"></i>
                    </div>
                    <div class="product-brochure-text">
                        <h4>Tải brochure sản phẩm</h4>
                        <p>Tài liệu thông số kỹ thuật chi tiết, hướng dẫn sử dụng và thông tin bảo hành.</p>
                    </div>
                    <a href="{{ asset($product->brochure) }}" class="btn-brochure ms-auto" target="_blank">
                        <i class="bi bi-download"></i> Tải PDF
                    </a>
                </div>
                @endif
            </div>

            <!-- Product Info Sidebar -->
            <div class="col-lg-5">
                <div class="product-info-sidebar wow fadeInUp" data-wow-delay="0.2s">
                    <h1>{{ $product->name }}</h1>
                    <span class="product-code">Mã sản phẩm: {{ $product->sku }}</span>

                    <div class="product-meta">
                        @if($product->brand)
                        <div class="product-meta-item">
                            <span class="product-meta-label">Hãng sản xuất</span>
                            <span class="product-meta-value">{{ $product->brand->name }}</span>
                        </div>
                        @endif
                        @if($product->category)
                        <div class="product-meta-item">
                            <span class="product-meta-label">Danh mục</span>
                            <span class="product-meta-value">{{ $product->category->name }}</span>
                        </div>
                        @endif
                        <div class="product-meta-item">
                            <span class="product-meta-label">Đơn vị tính</span>
                            <span class="product-meta-value">{{ $product->unit }}</span>
                        </div>
                        <div class="product-meta-item">
                            <span class="product-meta-label">Số lượng tối thiểu</span>
                            <span class="product-meta-value">{{ $product->min_order_qty }} {{ $product->unit }}</span>
                        </div>
                        <div class="product-meta-item">
                            <span class="product-meta-label">Tình trạng</span>
                            <span class="product-meta-value text-success">Còn hàng</span>
                        </div>
                    </div>

                    <div class="product-actions">
                        <a href="{{ route('contact') }}?product={{ $product->slug }}" class="btn-quote">
                            <i class="bi bi-envelope"></i> Yêu cầu báo giá
                        </a>
                        @if($product->brochure)
                            <a href="{{ asset($product->brochure) }}" class="btn-brochure" target="_blank">
                                <i class="bi bi-file-pdf"></i> Tải brochure kỹ thuật
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Related Products -->
        @if($relatedProducts->count() > 0)
        <div class="related-products mt-5 wow fadeInUp" data-wow-delay="0.6s">
            <h2>Sản phẩm liên quan</h2>
            <div class="row g-4">
                @foreach($relatedProducts as $related)
                    <div class="col-lg-3 col-md-6">
                        <div class="product-card-b2b">
                            @if($related->is_featured)
                                <span class="product-badge">Nổi bật</span>
                            @endif
                            <img class="card-img" src="{{ $related->images[0] ?? asset('img/product-1.jpg') }}" alt="{{ $related->name }}">
                            <div class="card-body">
                                <div class="card-brand">{{ $related->brand->name ?? $related->category->name ?? '' }}</div>
                                <h5 class="card-title"><a href="{{ route('products.show', $related->slug) }}">{{ $related->name }}</a></h5>
                                <p class="card-text">{{ Str::limit($related->short_description, 80) }}</p>
                            </div>
                            <div class="card-footer">
                                <a href="{{ route('products.show', $related->slug) }}" class="btn-detail"><i class="bi bi-eye me-1"></i> Chi tiết</a>
                                <a href="{{ route('contact') }}?product={{ $related->slug }}" class="btn-quote-sm"><i class="bi bi-envelope me-1"></i> Báo giá</a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>
</div>
<!-- Product Detail End -->
@endsection

@push('scripts')
<script>
    @if(count($product->images ?? []) > 0)
    var galleryImages = {!! json_encode($product->images) !!};
    @else
    var galleryImages = ['{{ asset("img/product-1.jpg") }}'];
    @endif
    var currentIndex = 0;

    function selectImage(index) {
        currentIndex = index;
        document.getElementById('mainImage').src = galleryImages[currentIndex];
        document.querySelectorAll('.product-thumb').forEach(function(thumb, i) {
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
