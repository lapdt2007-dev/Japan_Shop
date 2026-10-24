{{-- ===== FOOTER (dùng chung mọi trang khách hàng) ===== --}}
<footer id="footer-info" class="bg-white border-top mt-4 pt-5 pb-3">
    <div class="container-fluid px-3 px-lg-4">
        <div class="row g-4 mb-4">
            <div class="col-lg-4 col-md-6">
                <h5 class="fw-bold text-danger mb-3"><i class="fa-solid fa-torii-gate me-2"></i>JAPAN SHOP</h5>
                <p class="text-muted small">Chuyên cung cấp các mặt hàng nội địa Nhật Bản chất lượng cao: Đồ gia dụng, Bánh kẹo, Mỹ phẩm, Truyện tranh Anime và Quà lưu niệm.</p>
            </div>
            <div class="col-lg-2 col-md-6">
                <h6 class="fw-bold mb-3">Danh Mục</h6>
                <ul class="list-unstyled small text-muted">
                    @foreach(($navCategories ?? collect())->take(4) as $category)
                        <li class="mb-2"><a href="{{ route('home', ['category' => $category->slug]) }}" class="text-decoration-none text-muted">{{ $category->name }}</a></li>
                    @endforeach
                </ul>
            </div>
            <div class="col-lg-3 col-md-6">
                <h6 class="fw-bold mb-3">Chính Sách</h6>
                <ul class="list-unstyled small text-muted">
                    <li class="mb-2"><a href="#" class="text-decoration-none text-muted">Chính sách vận chuyển</a></li>
                    <li class="mb-2"><a href="#" class="text-decoration-none text-muted">Chính sách đổi trả 1-1</a></li>
                    <li class="mb-2"><a href="#" class="text-decoration-none text-muted">Cam kết bảo mật</a></li>
                </ul>
            </div>
            <div class="col-lg-3 col-md-6">
                <h6 class="fw-bold mb-3">Liên Hệ</h6>
                <p class="text-muted small mb-2"><i class="fa-solid fa-location-dot text-danger me-2"></i> TP. Hồ Chí Minh, Việt Nam</p>
                <p class="text-muted small mb-2"><i class="fa-solid fa-phone text-danger me-2"></i> 0123 456 789</p>
                <p class="text-muted small"><i class="fa-solid fa-envelope text-danger me-2"></i> support@japanshop.vn</p>
            </div>
        </div>
        <hr class="my-4 text-muted">
        <div class="text-center text-muted small">
            © {{ date('Y') }} Đinh Thành Lập, Trần Tấn Đạt, Lê Nguyễn An Trường
        </div>
    </div>
</footer>