@extends('layouts.app')

@section('title', 'Danh mục sản phẩm')
@section('meta_description', 'Danh mục sản phẩm đa dạng, chất lượng cao cho doanh nghiệp')

@section('content')
<!-- Page Header Start -->
<div class="container-fluid page-header py-5 wow fadeIn" data-wow-delay="0.1s">
    <div class="container text-center py-5">
        <h1 class="display-2 text-dark mb-4 animated slideInDown">Danh mục sản phẩm</h1>
        <nav aria-label="breadcrumb animated slideInDown">
            <ol class="breadcrumb justify-content-center mb-0">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Trang chủ</a></li>
                <li class="breadcrumb-item text-dark" aria-current="page">Sản phẩm</li>
            </ol>
        </nav>
    </div>
</div>
<!-- Page Header End -->

<!-- Products Section Start -->
<div class="container-fluid py-5">
    <div class="container py-5">
        <div class="row g-5">
            <!-- Filter Sidebar -->
            <div class="col-lg-3">
                <div class="filter-sidebar wow fadeInUp" data-wow-delay="0.1s">
                    <!-- Search -->
                    <div class="filter-section">
                        <h5>Tìm kiếm</h5>
                        <form action="{{ route('products.index') }}" method="GET">
                            <div class="search-box">
                                <input type="text" name="search" class="form-control" placeholder="Tìm sản phẩm..." value="{{ request('search') }}">
                                <button type="submit"><i class="bi bi-search"></i></button>
                            </div>
                        </form>
                    </div>

                    <!-- Category Tree -->
                    <div class="filter-section">
                        <x-category-tree :categories="$categories" :activeCategorySlug="request('category')" />
                    </div>

                    <!-- Brand Filter -->
                    @if($brands->count() > 0)
                    <div class="filter-section">
                        <h5>Hãng sản xuất</h5>
                        @foreach($brands as $brand)
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="" id="brand{{ $brand->id }}"
                                    {{ in_array($brand->slug, (array)request('brands', [])) ? 'checked' : '' }}>
                                <label class="form-check-label" for="brand{{ $brand->id }}">{{ $brand->name }}</label>
                            </div>
                        @endforeach
                    </div>
                    @endif

                    <a href="{{ route('products.index') }}" class="btn btn-primary w-100 rounded-pill py-2">Áp dụng bộ lọc</a>
                </div>
            </div>

            <!-- Product Grid -->
            <div class="col-lg-9">
                <!-- Sort Bar -->
                <div class="d-flex justify-content-between align-items-center mb-4 wow fadeInUp" data-wow-delay="0.1s">
                    <p class="mb-0 text-muted">
                        Hiển thị {{ $products->firstItem() ?? 0 }}-{{ $products->lastItem() ?? 0 }}
                        trên {{ $products->total() }} sản phẩm
                    </p>
                    <form action="{{ route('products.index') }}" method="GET" class="d-flex gap-2">
                        @if(request('category'))
                            <input type="hidden" name="category" value="{{ request('category') }}">
                        @endif
                        @if(request('search'))
                            <input type="hidden" name="search" value="{{ request('search') }}">
                        @endif
                        <select name="sort" class="form-select form-select-sm w-auto" onchange="this.form.submit()">
                            <option value="default" {{ request('sort') == 'default' ? 'selected' : '' }}>Sắp xếp: Mặc định</option>
                            <option value="name_asc" {{ request('sort') == 'name_asc' ? 'selected' : '' }}>Tên A-Z</option>
                            <option value="name_desc" {{ request('sort') == 'name_desc' ? 'selected' : '' }}>Tên Z-A</option>
                            <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Mới nhất</option>
                            <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>Cũ nhất</option>
                        </select>
                    </form>
                </div>

                <div class="row g-4">
                    @forelse($products as $product)
                        <div class="col-lg-4 col-md-6 wow fadeInUp" data-wow-delay="0.1s">
                            <div class="product-card-b2b">
                                @if($product->is_featured)
                                    <span class="product-badge">Nổi bật</span>
                                @endif
                                <img class="card-img" src="{{ isset($product->images[0]) ? asset('storage/' . $product->images[0]) : asset('img/product-1.jpg') }}" alt="{{ $product->name }}">
                                <div class="card-body">
                                    <div class="card-brand">{{ $product->brand->name ?? $product->category->name ?? '' }}</div>
                                    <h5 class="card-title"><a href="{{ route('products.show', $product->slug) }}">{{ $product->name }}</a></h5>
                                    <p class="card-text">{{ Str::limit($product->short_description, 100) }}</p>
                                </div>
                                <div class="card-footer">
                                    <a href="{{ route('products.show', $product->slug) }}" class="btn-detail"><i class="bi bi-eye me-1"></i> Chi tiết</a>
                                    <a href="{{ route('contact') }}?product={{ $product->slug }}" class="btn-quote-sm"><i class="bi bi-envelope me-1"></i> Báo giá</a>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-12 text-center py-5">
                            <i class="bi bi-box-seam" style="font-size: 3rem; color: #ddd;"></i>
                            <p class="text-muted mt-3">Không tìm thấy sản phẩm nào.</p>
                        </div>
                    @endforelse
                </div>

                <!-- Pagination -->
                <div class="pagination-b2b wow fadeInUp" data-wow-delay="0.3s">
                    {{ $products->appends(request()->query())->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Products Section End -->
@endsection
