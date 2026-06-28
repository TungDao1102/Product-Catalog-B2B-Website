@extends('layouts.app')

@section('title', $category->name)
@section('meta_description', $category->description ?? '')

@section('content')
<!-- Page Header Start -->
<div class="container-fluid page-header py-5 wow fadeIn" data-wow-delay="0.1s">
    <div class="container text-center py-5">
        <h1 class="display-2 text-dark mb-4 animated slideInDown">{{ $category->name }}</h1>
        <nav aria-label="breadcrumb animated slideInDown">
            <ol class="breadcrumb justify-content-center mb-0">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('navigation.home') }}</a></li>
                <li class="breadcrumb-item"><a href="{{ route('products.index') }}">{{ __('navigation.products') }}</a></li>
                <li class="breadcrumb-item text-dark" aria-current="page">{{ $category->name }}</li>
            </ol>
        </nav>
    </div>
</div>
<!-- Page Header End -->

<!-- Category Intro Start -->
@if($category->description)
<div class="container-fluid py-4 wow fadeInUp" data-wow-delay="0.1s">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <p class="text-muted fs-5 mb-0">
                    <i class="bi bi-shield-check text-primary me-2"></i>
                    {{ $category->description }}
                </p>
            </div>
            <div class="col-lg-4 text-lg-end mt-3 mt-lg-0">
                <span class="badge bg-primary rounded-pill py-2 px-3">{{ $products->total() }} {{ __('common.products_count') }}</span>
            </div>
        </div>
    </div>
</div>
@endif
<!-- Category Intro End -->

<!-- Category Products Start -->
<div class="container-fluid py-5">
    <div class="container py-5">
        <div class="row g-5">
            <!-- Filter Sidebar -->
            <div class="col-lg-3">
                <div class="filter-sidebar wow fadeInUp" data-wow-delay="0.1s">
                    <!-- Search -->
                    <div class="filter-section">
                        <h5>{{ __('navigation.search') }}</h5>
                        <form action="{{ route('categories.show', $category->slug) }}" method="GET">
                            <div class="search-box">
                                <input type="text" name="search" class="form-control" placeholder="{{ __('common.search_placeholder') }}" value="{{ request('search') }}">
                                <button type="submit"><i class="bi bi-search"></i></button>
                            </div>
                        </form>
                    </div>

                    <!-- Category Tree -->
                    <div class="filter-section">
                        @if(isset($rootCategories))
                            <x-category-tree :categories="$rootCategories" :activeCategorySlug="$category->slug" />
                        @else
                            <x-category-tree :categories="collect([$category])" :activeCategorySlug="$category->slug" />
                        @endif
                    </div>

                    <a href="{{ route('categories.show', $category->slug) }}" class="btn btn-primary w-100 rounded-pill py-2">{{ __('common.apply_filters') }}</a>
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
                    <form action="{{ route('categories.show', $category->slug) }}" method="GET">
                        @if(request('search'))
                            <input type="hidden" name="search" value="{{ request('search') }}">
                        @endif
                        <select name="sort" class="form-select form-select-sm w-auto" onchange="this.form.submit()">
                            <option value="default" {{ request('sort') == 'default' ? 'selected' : '' }}>{{ __('common.sort') }}: {{ __('common.sort_default') }}</option>
                            <option value="name_asc" {{ request('sort') == 'name_asc' ? 'selected' : '' }}>{{ __('common.sort_name_asc') }}</option>
                            <option value="name_desc" {{ request('sort') == 'name_desc' ? 'selected' : '' }}>{{ __('common.sort_name_desc') }}</option>
                            <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>{{ __('common.sort_newest') }}</option>
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
                                <img class="card-img" src="{{ isset($product->images[0]) ? asset('storage/' . $product->images[0]) : asset('img/product-1.jpg') }}" alt="{{ $product->name }}">
                                <div class="card-body">
                                    <div class="card-brand">{{ $product->brand->name ?? '' }}</div>
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
                            <p class="text-muted mt-3">{{ __('common.no_products_in_category') }}</p>
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
<!-- Category Products End -->
@endsection
