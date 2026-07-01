<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale() === 'vi' ? 'vi_VN' : 'en_US') }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', $seo['title'] ?? config('app.name')) - {{ config('app.name') }}</title>
    <meta name="keywords" content="@yield('meta_keywords')">
    <meta name="description" content="@yield('meta_description', $seo['description'] ?? '')">

    <!-- Open Graph Meta Tags -->
    <meta property="og:title" content="@yield('og_title', $seo['title'] ?? config('app.name'))" />
    <meta property="og:description" content="@yield('og_description', $seo['description'] ?? __('seo.home_description'))" />
    <meta property="og:image" content="@yield('og_image', $seo['image'] ?? asset('img/og-default.jpg'))" />
    <meta property="og:type" content="@yield('og_type', $seo['type'] ?? 'website')" />
    <meta property="og:url" content="{{ url()->current() }}" />
    <meta property="og:locale" content="{{ str_replace('_', '-', app()->getLocale() === 'vi' ? 'vi_VN' : 'en_US') }}" />

    <!-- Alternate Language Links -->
    <link rel="alternate" hreflang="vi" href="{{ url(preg_replace('#^/[a-z]{2}(/|$)#', '/vi$1', request()->getPathInfo())) }}" />
    <link rel="alternate" hreflang="en" href="{{ url(preg_replace('#^/[a-z]{2}(/|$)#', '/en$1', request()->getPathInfo())) }}" />
    <link rel="alternate" hreflang="x-default" href="{{ url(preg_replace('#^/[a-z]{2}(/|$)#', '/vi$1', request()->getPathInfo())) }}" />

    <!-- Favicon -->
    <link href="{{ asset('favicon.ico') }}" rel="icon">

    <!-- Google Web Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600&family=Playfair+Display:wght@700;900&display=swap" rel="stylesheet">

    <!-- Icon Font Stylesheet -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Libraries Stylesheet -->
    <link href="{{ asset('lib/animate/animate.min.css') }}" rel="stylesheet">
    <link href="{{ asset('lib/owlcarousel/assets/owl.carousel.min.css') }}" rel="stylesheet">

    <!-- Customized Bootstrap Stylesheet -->
    <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">

    <!-- Template Stylesheet -->
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">

    <!-- B2B Stylesheet -->
    <link href="{{ asset('css/b2b.css') }}" rel="stylesheet">

    @stack('styles')
</head>
<body>
    <!-- Spinner Start -->
    <div id="spinner" class="show bg-white position-fixed translate-middle w-100 vh-100 top-50 start-50 d-flex align-items-center justify-content-center">
        <div class="spinner-border text-primary" role="status" style="width: 3rem; height: 3rem;"></div>
    </div>
    <!-- Spinner End -->

    @include('partials.navbar')

    <main>
        @yield('content')
    </main>

    @include('partials.footer')

    <!-- Back to Top -->
    <a href="#" class="btn btn-lg btn-primary btn-lg-square rounded-circle back-to-top"><i class="bi bi-arrow-up"></i></a>

    <!-- JavaScript Libraries -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('lib/wow/wow.min.js') }}"></script>
    <script src="{{ asset('lib/easing/easing.min.js') }}"></script>
    <script src="{{ asset('lib/waypoints/waypoints.min.js') }}"></script>
    <script src="{{ asset('lib/owlcarousel/owl.carousel.min.js') }}"></script>

    <!-- Template Javascript -->
    <script src="{{ asset('js/main.js') }}"></script>

    @stack('scripts')
</body>
</html>
