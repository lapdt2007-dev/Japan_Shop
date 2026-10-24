<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Japan Shop - Hàng Nhật Chính Hãng</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Font (Inter) -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('css/sidebar-layout.css') }}">
    <link rel="stylesheet" href="{{ asset('css/home.css')}}">
</head>
<body>
<div>
    <!-- ============ SIDEBAR TRÁI ============ -->
    @include('layouts.sidebar')

    <!-- ============ NỘI DUNG CHÍNH ============ -->
    <div class="main-wrapper" style="min-width:0;">

    <!-- ===== TOP HEADER ===== -->
    @include('layouts.header')

        
@if(session('error') || session('warning') || $errors->any())
<div class="container-fluid px-3 px-lg-4 mt-3">
    @include('partials.flash-errors')
</div>
@endif
        <!-- Thông báo -->
        @if(session('success'))
            <div class="container-fluid px-3 px-lg-4 mt-3">
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fa-solid fa-circle-check me-2"></i>{{ session('success') }}
                    @if(Route::has('cart.index'))
                        <a href="{{ route('cart.index') }}" class="fw-bold text-success ms-2">Xem giỏ hàng <i class="fa-solid fa-arrow-right"></i></a>
                    @endif
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            </div>
        @endif

        <main class="container-fluid px-3 px-lg-4 py-4">
            @php
                $promoProducts = $products->take(10)->values();
                $resolveImg = function ($product) {
                    $img = $product->thumbnail ?? '';
                    if (!$img) return 'data:image/svg+xml,%3Csvg xmlns=%22http://www.w3.org/2000/svg%22 width=%22300%22 height=%22300%22%3E%3Crect width=%22100%25%22 height=%22100%25%22 fill=%22%23f1f1f1%22/%3E%3Ctext x=%2250%25%22 y=%2250%25%22 font-family=%22sans-serif%22 font-size=%2216%22 fill=%22%23999%22 text-anchor=%22middle%22 dy=%22.3em%22%3EJapan Shop%3C/text%3E%3C/svg%3E';
                    return filter_var($img, FILTER_VALIDATE_URL) ? $img : asset('storage/' . ltrim($img, '/'));
                };

                // Map icon cho từng danh mục theo slug
                $categoryIcons = [
                    'manga' => 'fa-book',
                    'noi-that' => 'fa-couch',
                    'the-thao' => 'fa-dumbbell',
                    'do-choi' => 'fa-gamepad',
                    'thoi-trang' => 'fa-shirt',
                    'thuc-pham' => 'fa-utensils',
                    'my-pham' => 'fa-spray-can-sparkles',
                    'van-phong-pham' => 'fa-briefcase',
                    'dien-tu' => 'fa-mobile-screen',
                ];
            @endphp
            <div class="row g-3 mb-4">
                <!-- ===== DANH SÁCH DANH MỤC (dọc) ===== -->
                <div class="col-lg-3">
                    <div id="category-row" class="bg-white rounded-3 shadow-sm border p-3">
                        <h6 class="fw-bold text-dark mb-2">Danh sách</h6>
                        <div class="d-flex flex-column gap-1">
                            <a href="{{ route('home') }}" class="d-flex align-items-center gap-2 px-2 py-1 rounded-2 text-decoration-none {{ !request('category') ? 'bg-danger text-white' : 'text-dark' }}">
                                <i class="fa-solid fa-boxes-stacked" style="width:16px; font-size:0.8rem;"></i>
                                <span class="small fw-semibold">Tất Cả Sản Phẩm</span>
                            </a>
                            @foreach($categories as $category)
                                <a href="{{ route('home', ['category' => $category->slug]) }}" class="d-flex align-items-center gap-2 px-2 py-1 rounded-2 text-decoration-none {{ request('category') == $category->slug ? 'bg-danger text-white' : 'text-dark' }}">
                                    <i class="fa-solid {{ $categoryIcons[$category->slug] ?? 'fa-tag' }}" style="width:16px; font-size:0.8rem;"></i>
                                    <span class="small fw-semibold">{{ $category->name }}</span>
                                </a>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="row g-3 h-100">
                        <div class="col-md-6">
                            <div class="promo-box">
                                <div class="promo-label"><i class="fa-solid fa-fire text-danger me-1"></i>Ưu Đãi Hôm Nay</div>
                                <div class="promo-imgs">
                                    @if($promoProducts->get(0)) <img src="{{ $resolveImg($promoProducts[0]) }}" alt="" onerror="this.onerror=null;this.src='data:image/svg+xml,%3Csvg xmlns=%22http://www.w3.org/2000/svg%22 width=%22300%22 height=%22300%22%3E%3Crect width=%22100%25%22 height=%22100%25%22 fill=%22%23f1f1f1%22/%3E%3Ctext x=%2250%25%22 y=%2250%25%22 font-family=%22sans-serif%22 font-size=%2216%22 fill=%22%23999%22 text-anchor=%22middle%22 dy=%22.3em%22%3EJapan Shop%3C/text%3E%3C/svg%3E'"> @endif
                                    @if($promoProducts->get(1)) <img src="{{ $resolveImg($promoProducts[1]) }}" alt="" onerror="this.onerror=null;this.src='data:image/svg+xml,%3Csvg xmlns=%22http://www.w3.org/2000/svg%22 width=%22300%22 height=%22300%22%3E%3Crect width=%22100%25%22 height=%22100%25%22 fill=%22%23f1f1f1%22/%3E%3Ctext x=%2250%25%22 y=%2250%25%22 font-family=%22sans-serif%22 font-size=%2216%22 fill=%22%23999%22 text-anchor=%22middle%22 dy=%22.3em%22%3EJapan Shop%3C/text%3E%3C/svg%3E'"> @endif
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="promo-box">
                                <div class="promo-label"><i class="fa-solid fa-truck-fast text-danger me-1"></i>Miễn Phí Vận Chuyển</div>
                                <div class="promo-imgs">
                                    @if($promoProducts->get(2)) <img src="{{ $resolveImg($promoProducts[2]) }}" alt="" onerror="this.onerror=null;this.src='data:image/svg+xml,%3Csvg xmlns=%22http://www.w3.org/2000/svg%22 width=%22300%22 height=%22300%22%3E%3Crect width=%22100%25%22 height=%22100%25%22 fill=%22%23f1f1f1%22/%3E%3Ctext x=%2250%25%22 y=%2250%25%22 font-family=%22sans-serif%22 font-size=%2216%22 fill=%22%23999%22 text-anchor=%22middle%22 dy=%22.3em%22%3EJapan Shop%3C/text%3E%3C/svg%3E'"> @endif
                                    @if($promoProducts->get(3)) <img src="{{ $resolveImg($promoProducts[3]) }}" alt="" onerror="this.onerror=null;this.src='data:image/svg+xml,%3Csvg xmlns=%22http://www.w3.org/2000/svg%22 width=%22300%22 height=%22300%22%3E%3Crect width=%22100%25%22 height=%22100%25%22 fill=%22%23f1f1f1%22/%3E%3Ctext x=%2250%25%22 y=%2250%25%22 font-family=%22sans-serif%22 font-size=%2216%22 fill=%22%23999%22 text-anchor=%22middle%22 dy=%22.3em%22%3EJapan Shop%3C/text%3E%3C/svg%3E'"> @endif
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="promo-box">
                                <div class="promo-label"><i class="fa-solid fa-star text-danger me-1"></i>Bán Chạy Nhất</div>
                                <div class="promo-imgs">
                                    @if($promoProducts->get(4)) <img src="{{ $resolveImg($promoProducts[4]) }}" alt="" onerror="this.onerror=null;this.src='data:image/svg+xml,%3Csvg xmlns=%22http://www.w3.org/2000/svg%22 width=%22300%22 height=%22300%22%3E%3Crect width=%22100%25%22 height=%22100%25%22 fill=%22%23f1f1f1%22/%3E%3Ctext x=%2250%25%22 y=%2250%25%22 font-family=%22sans-serif%22 font-size=%2216%22 fill=%22%23999%22 text-anchor=%22middle%22 dy=%22.3em%22%3EJapan Shop%3C/text%3E%3C/svg%3E'"> @endif
                                    @if($promoProducts->get(5)) <img src="{{ $resolveImg($promoProducts[5]) }}" alt="" onerror="this.onerror=null;this.src='data:image/svg+xml,%3Csvg xmlns=%22http://www.w3.org/2000/svg%22 width=%22300%22 height=%22300%22%3E%3Crect width=%22100%25%22 height=%22100%25%22 fill=%22%23f1f1f1%22/%3E%3Ctext x=%2250%25%22 y=%2250%25%22 font-family=%22sans-serif%22 font-size=%2216%22 fill=%22%23999%22 text-anchor=%22middle%22 dy=%22.3em%22%3EJapan Shop%3C/text%3E%3C/svg%3E'"> @endif
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="promo-box">
                                <div class="promo-label"><i class="fa-solid fa-shield-halved text-danger me-1"></i>Hàng Chính Hãng</div>
                                <div class="promo-imgs">
                                    @if($promoProducts->get(6)) <img src="{{ $resolveImg($promoProducts[6]) }}" alt="" onerror="this.onerror=null;this.src='data:image/svg+xml,%3Csvg xmlns=%22http://www.w3.org/2000/svg%22 width=%22300%22 height=%22300%22%3E%3Crect width=%22100%25%22 height=%22100%25%22 fill=%22%23f1f1f1%22/%3E%3Ctext x=%2250%25%22 y=%2250%25%22 font-family=%22sans-serif%22 font-size=%2216%22 fill=%22%23999%22 text-anchor=%22middle%22 dy=%22.3em%22%3EJapan Shop%3C/text%3E%3C/svg%3E'"> 
                                    @endif
                                    @if($promoProducts->get(7)) <img src="{{ $resolveImg($promoProducts[7]) }}" alt="" onerror="this.onerror=null;this.src='data:image/svg+xml,%3Csvg xmlns=%22http://www.w3.org/2000/svg%22 width=%22300%22 height=%22300%22%3E%3Crect width=%22100%25%22 height=%22100%25%22 fill=%22%23f1f1f1%22/%3E%3Ctext x=%2250%25%22 y=%2250%25%22 font-family=%22sans-serif%22 font-size=%2216%22 fill=%22%23999%22 text-anchor=%22middle%22 dy=%22.3em%22%3EJapan Shop%3C/text%3E%3C/svg%3E'"> 
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- ===== PANEL CHÀO MỪNG BÊN PHẢI ===== -->
                <div class="col-lg-3">
                    <div class="welcome-panel">
                        <h6 class="fw-bold mb-1"><i class="fa-solid fa-torii-gate text-danger me-1"></i>Chào mừng đến Japan Shop</h6>
                        <p class="small text-muted mb-3">Ưu đãi dành riêng cho thành viên</p>

                        <div class="d-flex gap-2 mb-3">
                            <div class="text-center flex-fill">
                                <div class="stat-icon mx-auto mb-1"><i class="fa-solid fa-shield-halved text-danger"></i></div>
                                <div class="small">Chứng chỉ</div>
                            </div>
                            <div class="text-center flex-fill">
                                <div class="stat-icon mx-auto mb-1"><i class="fa-solid fa-tags text-danger"></i></div>
                                <div class="small">Phiếu giảm giá</div>
                            </div>
                            <div class="text-center flex-fill">
                                <div class="stat-icon mx-auto mb-1"><i class="fa-solid fa-clock text-danger"></i></div>
                                <div class="small">Sau nhận hàng</div>
                            </div>
                        </div>

                        @guest
                            <a href="{{ route('login') }}" class="btn btn-danger w-100 fw-bold rounded-pill">Đăng nhập ngay</a>
                            <a href="{{ route('register') }}" class="btn btn-outline-danger w-100 fw-bold rounded-pill mt-2">Đăng ký tài khoản</a>
                        @else
                            <a href="{{ route('home') }}#products-section" class="btn btn-danger w-100 fw-bold rounded-pill">Mua sắm ngay</a>
                        @endguest
                    </div>
                </div>
            </div>

            <!-- ===== DANH SÁCH SẢN PHẨM ===== -->
            <div id="products-section" class="d-flex justify-content-between align-items-center mb-3">
                <div>
                    <h5 class="fw-bold m-0 text-dark">
                        <i class="fa-solid fa-list text-danger me-2"></i>
                        {{ request('category') ? 'Sản Phẩm Theo Danh Mục' : (request('search') ? 'Kết Quả Tìm Kiếm' : 'Tất Cả Sản Phẩm') }}
                    </h5>
                    <p class="text-muted small m-0 mt-1">
                        Tìm thấy {{ method_exists($products, 'total') ? $products->total() : $products->count() }} sản phẩm phù hợp
                    </p>
                </div>
                @if(request('category') || request('search'))
                    <a href="{{ route('home') }}" class="btn btn-sm btn-outline-secondary rounded-pill">
                        <i class="fa-solid fa-xmark me-1"></i> Bỏ lọc
                    </a>
                @endif
            </div>

            @if($products->isEmpty())
                <div class="card border-0 shadow-sm text-center p-5 my-4">
                    <div class="card-body">
                        <i class="fa-solid fa-box-open text-muted fa-4x mb-3"></i>
                        <h5 class="fw-bold text-secondary">Rất tiếc, chưa tìm thấy sản phẩm nào!</h5>
                        <p class="text-muted small">Hãy thử chọn lại danh mục khác hoặc xóa từ khóa tìm kiếm.</p>
                        <a href="{{ route('home') }}" class="btn btn-danger rounded-pill px-4 mt-2">Xem tất cả sản phẩm</a>
                    </div>
                </div>
            @else
                <div class="row row-cols-2 row-cols-sm-3 row-cols-md-4 row-cols-lg-5 g-3">
                    @foreach($products as $product)
                        <div class="col">
                            <x-product-card :product="$product" />
                        </div>
                    @endforeach
                </div>

                <div class="d-flex justify-content-center mt-5">
                    {{ $products->links('pagination::bootstrap-5') }}
                </div>
            @endif

        </main>

        <!-- ===== FOOTER ===== -->
        @include('layouts.footer')
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>