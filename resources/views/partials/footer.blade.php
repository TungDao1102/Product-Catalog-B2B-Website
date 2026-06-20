<!-- Footer Start -->
<div class="container-fluid bg-dark footer mt-5 py-5 wow fadeIn" data-wow-delay="0.1s">
    <div class="container py-5">
        <div class="row g-5">
            <div class="col-lg-3 col-md-6">
                <h4 class="text-primary mb-4">Văn phòng</h4>
                <p class="mb-2"><i class="fa fa-map-marker-alt text-primary me-3"></i>123 Đường ABC, Quận 1, TP.HCM</p>
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
                <h4 class="text-primary mb-4">Liên kết nhanh</h4>
                <a class="btn btn-link" href="{{ route('home') }}">Trang chủ</a>
                <a class="btn btn-link" href="{{ route('products.index') }}">Sản phẩm</a>
                <a class="btn btn-link" href="{{ route('contact') }}">Liên hệ</a>
            </div>
            <div class="col-lg-3 col-md-6">
                <h4 class="text-primary mb-4">Giờ làm việc</h4>
                <p class="mb-1">Thứ Hai - Thứ Sáu</p>
                <h6 class="text-light">08:00 - 17:30</h6>
                <p class="mb-1">Thứ Bảy</p>
                <h6 class="text-light">08:00 - 12:00</h6>
                <p class="mb-1">Chủ Nhật</p>
                <h6 class="text-light">Đóng cửa</h6>
            </div>
            <div class="col-lg-3 col-md-6">
                <h4 class="text-primary mb-4">Đăng ký nhận tin</h4>
                <p>Nhận thông tin sản phẩm mới và khuyến mãi qua email.</p>
                <div class="position-relative w-100">
                    <input class="form-control bg-transparent w-100 py-3 ps-4 pe-5" type="text" placeholder="Email của bạn">
                    <button type="button" class="btn btn-primary py-2 position-absolute top-0 end-0 mt-2 me-2">Đăng ký</button>
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
