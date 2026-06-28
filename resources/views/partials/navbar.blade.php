<!-- Navbar Start -->
<div class="container-fluid bg-white sticky-top">
    <div class="container">
        <nav class="navbar navbar-expand-lg bg-white navbar-light py-2 py-lg-0">
            <a href="{{ route('home') }}" class="navbar-brand">
                <img class="img-fluid" src="{{ asset('img/logo.png') }}" alt="Logo">
            </a>
            <button type="button" class="navbar-toggler ms-auto me-0" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarCollapse">
                <div class="navbar-nav ms-auto">
                    <a href="{{ route('home') }}" class="nav-item nav-link {{ request()->routeIs('home') ? 'active' : '' }}">Trang chủ</a>
                    <a href="{{ route('products.index') }}" class="nav-item nav-link {{ request()->routeIs('products.*') ? 'active' : '' }}">Sản phẩm</a>
                    @php
                        $parentCategories = \App\Models\Category::whereNull('parent_id')->where('is_active', true)->get();
                    @endphp
                    @if($parentCategories->count() > 0)
                    <div class="nav-item dropdown">
                        <a href="#" class="nav-link dropdown-toggle {{ request()->routeIs('categories.*') ? 'active' : '' }}" data-bs-toggle="dropdown">Danh mục</a>
                        <div class="dropdown-menu bg-light rounded-0 m-0">
                            @foreach($parentCategories as $cat)
                                <a href="{{ route('categories.show', $cat->slug) }}" class="dropdown-item">{{ $cat->name }}</a>
                            @endforeach
                        </div>
                    </div>
                    @endif
                    <a href="{{ route('posts.index') }}" class="nav-item nav-link {{ request()->routeIs('posts.*') ? 'active' : '' }}">Tin tức</a>
                    <a href="{{ route('projects.index') }}" class="nav-item nav-link {{ request()->routeIs('projects.*') ? 'active' : '' }}">Dự án</a>
                    <a href="{{ route('contact') }}" class="nav-item nav-link {{ request()->routeIs('contact') ? 'active' : '' }}">Liên hệ</a>
                </div>
                <div class="border-start ps-4 d-none d-lg-block">
                    <a href="{{ route('products.index') }}" class="btn btn-sm p-0"><i class="fa fa-search"></i></a>
                </div>
            </div>
        </nav>
    </div>
</div>
<!-- Navbar End -->
