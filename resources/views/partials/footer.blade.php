<!-- Footer Start -->
<div class="container-fluid bg-dark footer mt-5 py-5 wow fadeIn" data-wow-delay="0.1s">
    <div class="container py-5">
        <div class="row g-5">
            <div class="col-lg-3 col-md-6">
                <h4 class="text-primary mb-4">{{ __('common.office') }}</h4>
                <p class="mb-2"><i class="fa fa-map-marker-alt text-primary me-3"></i>{{ __('common.address_line') }}</p>
                <p class="mb-2"><i class="fa fa-phone-alt text-primary me-3"></i>+012 345 67890</p>
                <p class="mb-2"><i class="fa fa-envelope text-primary me-3"></i>info@example.com</p>
                <div class="d-flex pt-3">
                    <a class="btn btn-square btn-primary rounded-circle me-2" href=""><i class="fab fa-facebook-f"></i></a>
                    <a class="btn btn-square btn-primary rounded-circle me-2" href=""><i class="fab fa-twitter"></i></a>
                    <a class="btn btn-square btn-primary rounded-circle me-2" href=""><i class="fab fa-youtube"></i></a>
                    <a class="btn btn-square btn-primary rounded-circle me-2" href=""><i class="fab fa-linkedin-in"></i></a>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <h4 class="text-primary mb-4">{{ __('navigation.quick_links') }}</h4>
                <a class="btn btn-link" href="{{ route('home') }}">{{ __('navigation.home') }}</a>
                <a class="btn btn-link" href="{{ route('products.index') }}">{{ __('navigation.products') }}</a>
                <a class="btn btn-link" href="{{ route('contact') }}">{{ __('navigation.contact') }}</a>
            </div>
            <div class="col-lg-3 col-md-6">
                <h4 class="text-primary mb-4">{{ __('navigation.working_hours') }}</h4>
                <p class="mb-1">{{ __('common.monday_friday') }}</p>
                <h6 class="text-light">08:00 - 17:30</h6>
                <p class="mb-1">{{ __('common.saturday') }}</p>
                <h6 class="text-light">08:00 - 12:00</h6>
                <p class="mb-1">{{ __('common.sunday') }}</p>
                <h6 class="text-light">{{ __('common.closed') }}</h6>
            </div>
            <div class="col-lg-3 col-md-6">
                <h4 class="text-primary mb-4">{{ __('common.newsletter_title') }}</h4>
                <p>{{ __('navigation.newsletter') }}</p>
                <div class="position-relative w-100">
                    <input class="form-control bg-transparent w-100 py-3 ps-4 pe-5" type="text" placeholder="{{ __('navigation.your_email') }}">
                    <button type="button" class="btn btn-primary py-2 position-absolute top-0 end-0 mt-2 me-2">{{ __('navigation.subscribe') }}</button>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Footer End -->

<!-- Copyright Start -->
<div class="container-fluid copyright py-4">
    <div class="container">
        <div class="row">
            <div class="col-md-6 text-center text-md-start mb-3 mb-md-0">
                &copy; {{ date('Y') }} <a class="fw-medium" href="{{ route('home') }}">{{ config('app.name') }}</a>, All Right Reserved.
            </div>
            <div class="col-md-6 text-center text-md-end">
                Designed By <a class="fw-medium" href="https://htmlcodex.com">HTML Codex</a>
            </div>
        </div>
    </div>
</div>
<!-- Copyright End -->
