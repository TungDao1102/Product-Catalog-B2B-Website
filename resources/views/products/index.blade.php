@extends('layouts.app')

@section('title', __('products.page_title'))
@section('meta_description', __('products.page_description'))

@section('content')
<!-- Page Header Start -->
<div class="container-fluid page-header py-5 wow fadeIn" data-wow-delay="0.1s">
    <div class="container text-center py-5">
        <h1 class="display-2 text-dark mb-4 animated slideInDown">{{ __('products.page_title') }}</h1>
        <nav aria-label="breadcrumb animated slideInDown">
            <ol class="breadcrumb justify-content-center mb-0">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('navigation.home') }}</a></li>
                <li class="breadcrumb-item text-dark" aria-current="page">{{ __('navigation.products') }}</li>
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
                    <form action="{{ route('products.index') }}" method="GET">
                        <!-- Search -->
                        <div class="filter-section">
                            <h5>{{ __('navigation.search') }}</h5>
                            <div class="search-box">
                                <input type="text" name="search" class="form-control" placeholder="{{ __('common.search_placeholder') }}" value="{{ request('search') }}">
                                <button type="submit"><i class="bi bi-search"></i></button>
                            </div>
                        </div>

                        <!-- Category Tree -->
                        <div class="filter-section">
                            <x-category-tree :categories="$categories" :activeCategorySlug="request('category')" />
                        </div>

                        <!-- Brand Filter -->
                        @if($brands->count() > 0)
                        <div class="filter-section">
                            <h5>{{ __('products.brand') }}</h5>
                            @foreach($brands as $brand)
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="brands[]" value="{{ $brand->slug }}"
                                        id="brand{{ $brand->id }}"
                                        {{ in_array($brand->slug, (array)request('brands', [])) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="brand{{ $brand->id }}">{{ $brand->name }}</label>
                                </div>
                            @endforeach
                        </div>
                        @endif

                        <button type="submit" class="btn btn-primary w-100 rounded-pill py-2">{{ __('common.apply_filters') }}</button>
                    </form>
                </div>
            </div>

            <!-- Product Grid -->
            <div class="col-lg-9">
                <!-- Sort Bar -->
                <div class="d-flex justify-content-between align-items-center mb-4 wow fadeInUp" data-wow-delay="0.1s">
                    <p class="mb-0 text-muted">
                        {{ __('common.showing') }} {{ $products->firstItem() ?? 0 }}-{{ $products->lastItem() ?? 0 }}
                        {{ __('common.of') }} {{ $products->total() }} {{ __('common.products_lower') }}
                    </p>
                    <form action="{{ route('products.index') }}" method="GET" class="d-flex gap-2">
                        @if(request('category'))
                            <input type="hidden" name="category" value="{{ request('category') }}">
                        @endif
                        @if(request('search'))
                            <input type="hidden" name="search" value="{{ request('search') }}">
                        @endif
                        @foreach((array)request('brands', []) as $brand)
                            <input type="hidden" name="brands[]" value="{{ $brand }}">
                        @endforeach
                        <select name="sort" class="form-select form-select-sm w-auto" onchange="this.form.submit()">
                            <option value="default" {{ request('sort') == 'default' ? 'selected' : '' }}>{{ __('common.sort') }}: {{ __('common.sort_default') }}</option>
                            <option value="name_asc" {{ request('sort') == 'name_asc' ? 'selected' : '' }}>{{ __('common.sort_name_asc') }}</option>
                            <option value="name_desc" {{ request('sort') == 'name_desc' ? 'selected' : '' }}>{{ __('common.sort_name_desc') }}</option>
                            <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>{{ __('common.sort_newest') }}</option>
                            <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>{{ __('common.sort_oldest') }}</option>
                        </select>
                    </form>
                </div>

                <div class="row g-4">
                    @forelse($products as $product)
                        <div class="col-lg-4 col-md-6 wow fadeInUp" data-wow-delay="0.1s">
                            <div class="product-card-b2b">
                                @if($product->is_featured)
                                    <span class="product-badge">{{ __('common.featured') }}</span>
                                @endif
                                <img class="card-img" loading="lazy" src="{{ $product->imageUrl(0) }}" alt="{{ $product->name }}" width="300" height="200" style="object-fit:cover;">
                                <div class="card-body">
                                    <div class="card-brand">{{ $product->brand->name ?? $product->category->name ?? '' }}</div>
                                    <h5 class="card-title"><a href="{{ route('products.show', $product->slug) }}">{{ $product->name }}</a></h5>
                                    <p class="card-text">{{ Str::limit($product->short_description, 100) }}</p>
                                </div>
                                <div class="card-footer">
                                    <a href="{{ route('products.show', $product->slug) }}" class="btn-detail"><i class="bi bi-eye me-1"></i> {{ __('common.view_detail') }}</a>
                                    <a href="{{ route('contact') }}?product={{ $product->slug }}" class="btn-quote-sm"><i class="bi bi-envelope me-1"></i> {{ __('common.get_quote') }}</a>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-12 text-center py-5">
                            <i class="bi bi-box-seam" style="font-size: 3rem; color: #ddd;"></i>
                            <p class="text-muted mt-3">{{ __('common.no_products') }}</p>
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
