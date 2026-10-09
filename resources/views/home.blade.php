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

    <!-- CSS dùng chung cho sidebar + layout (tách riêng, không lặp code) -->
    <link rel="stylesheet" href="{{ asset('css/sidebar-layout.css') }}">

    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f5f5f7;
        }

        /* ===== TOP HEADER ===== */
        .top-header {
            background: #fff;
            border-bottom: 1px solid #eee;
        }

        .search-pill {
            border-radius: 999px;
            overflow: hidden;
            border: 2px solid var(--brand);
        }

        .search-pill input {
            border: none;
            box-shadow: none;
        }

        .search-pill input:focus { box-shadow: none; }

        .search-pill .btn-search {
            background: var(--brand);
            color: #fff;
            border: none;
            font-weight: 700;
            padding: 0 22px;
        }

        .header-icon-link {
            display: flex;
            flex-direction: column;
            align-items: center;
            font-size: 0.72rem;
            color: #444;
            text-decoration: none;
            gap: 2px;
        }

        .header-icon-link i { font-size: 1.05rem; }
        .header-icon-link:hover { color: var(--brand); }

        /* ===== CATEGORY CHIP ROW ===== */
        .category-chip {
            transition: all 0.2s ease-in-out;
            border-radius: 10px;
            white-space: nowrap;
        }

        .category-chip:hover { transform: translateY(-2px); }

        /* ===== PROMO GRID (banner lưới kiểu sàn TMĐT) ===== */
        .promo-box {
            position: relative;
            border-radius: 14px;
            overflow: hidden;
            background: #fff;
            border: 1px solid #eee;
            height: 100%;
        }

        .promo-box .promo-label {
            padding: 10px 12px 4px;
            font-weight: 700;
            font-size: 0.85rem;
            color: #333;
        }

        .promo-box .promo-imgs {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 2px;
            height: 130px;
        }

        .promo-box .promo-imgs img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .promo-box .promo-imgs img:only-child {
            grid-column: span 2;
        }

        /* ===== WELCOME PANEL bên phải ===== */
        .welcome-panel {
            background: linear-gradient(160deg, #fff4e6 0%, #ffe8d6 100%);
            border-radius: 14px;
            padding: 18px;
            height: 100%;
        }

        .welcome-panel .stat-icon {
            width: 34px; height: 34px;
            border-radius: 8px;
            display: flex; align-items: center; justify-content: center;
            background: #fff;
            font-size: 0.9rem;
        }

        /* ===== PRODUCT CARD (giữ nguyên từ bản gốc) ===== */
        .product-card {
            border-radius: 14px;
            overflow: hidden;
            transition: all 0.3s ease;
        }

        .product-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 12px 25px rgba(0,0,0,0.1) !important;
        }

        .product-img-wrapper {
            position: relative;
            height: 220px;
            overflow: hidden;
            background-color: #fff;
        }

        .product-img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.4s ease;
        }

        .product-card:hover .product-img { transform: scale(1.08); }

        .product-badge {
            position: absolute;
            top: 12px;
            left: 12px;
            font-size: 0.75rem;
            padding: 4px 8px;
            border-radius: 6px;
        }

        .price-tag {
            color: #dc3545;
            font-size: 1.1rem;
            font-weight: 700;
        }

        .text-clamp-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
    </style>
</head>
<body>

<div>

    <!-- ============ SIDEBAR TRÁI ============ -->
    <aside class="side-nav d-flex flex-column">
        <a href="{{ route('home') }}" class="{{ request()->routeIs('home') && !request('category') && !request('search') ? 'active' : '' }}">
            <i class="fa-solid fa-house"></i> Trang chủ
        </a>
        <a href="{{ route('products.index') }}" class="{{ request()->routeIs('products.index') ? 'active' : '' }}">
            <i class="fa-solid fa-bag-shopping"></i> Mua
        </a>
        <a href="{{ route('orders.index') }}" class="{{ request()->routeIs('orders.index') ? 'active' : '' }}">
            <i class="fa-solid fa-box-open"></i> Hàng hóa
        </a>
        <a href="{{ route('about.index') }}" class="{{ request()->routeIs('about.index') ? 'active' : '' }}">
            <i class="fa-solid fa-circle-info"></i> Giới thiệu
        </a>
        <a href="{{ route('world.index') }}" class="{{ request()->routeIs('world.index') ? 'active' : '' }}">
            <i class="fa-solid fa-earth-asia"></i> Quốc tế
        </a>
        <a href="{{ route('feedback.index') }}" class="{{ request()->routeIs('feedback.index') ? 'active' : '' }}">
            <i class="fa-solid fa-comment-dots"></i> Phản hồi
        </a>
    </aside>

    <!-- ============ NỘI DUNG CHÍNH ============ -->
    <div class="main-wrapper" style="min-width:0;">

        <!-- ===== TOP HEADER ===== -->
        <header class="top-header py-3 sticky-top">
            <div class="container-fluid px-3 px-lg-4">
                <div class="d-flex align-items-center gap-3 flex-wrap">

                    <a href="{{ route('home') }}" class="text-danger fw-bold fs-4 text-decoration-none flex-shrink-0">
                        <i class="fa-solid fa-torii-gate me-1"></i>JAPAN SHOP
                    </a>

                    <!-- Ô tìm kiếm -->
                    <form action="{{ route('home') }}" method="GET" class="flex-grow-1" style="max-width: 620px;">
                        <div class="input-group search-pill">
                            <input type="text" name="search" class="form-control ps-3" placeholder="Tìm kiếm sản phẩm, thương hiệu..." value="{{ request('search') }}">
                            <button class="btn btn-search" type="submit">
                                <i class="fa-solid fa-magnifying-glass me-1"></i> Tìm kiếm
                            </button>
                        </div>
                    </form>

                    <!-- Menu icon tiện ích (giống mẫu: Mua / Hàng hóa / Chat / Shop) -->
                    <div class="d-none d-xl-flex align-items-center gap-4">
                        <a href="{{ route('products.index') }}" class="header-icon-link">
                            <i class="fa-solid fa-bag-shopping"></i> Mua sắm
                        </a>
                        <div class="dropdown">
                            <button class="header-icon-link border-0 bg-transparent" type="button" data-bs-toggle="dropdown">
                                <i class="fa-solid fa-layer-group"></i> Danh mục
                            </button>
                            <ul class="dropdown-menu shadow-sm">
                                @foreach($categories as $category)
                                    <li><a class="dropdown-item" href="{{ route('home', ['category' => $category->slug]) }}">{{ $category->name }}</a></li>
                                @endforeach
                            </ul>
                        </div>
                        <a href="{{ route('feedback.index') }}" class="header-icon-link">
                            <i class="fa-solid fa-headset"></i> Hỗ trợ
                        </a>
                        <a href="{{ route('about.index') }}" class="header-icon-link">
                            <i class="fa-solid fa-shop"></i> Về chúng tôi
                        </a>
                    </div>

                    <!-- Icon tiện ích -->
                    <div class="d-flex align-items-center gap-4 ms-auto">
                        <a href="{{ Route::has('cart.index') ? route('cart.index') : '#' }}" class="header-icon-link position-relative">
                            <i class="fa-solid fa-cart-shopping"></i>
                            @php $cartCount = array_sum(array_column(session('cart', []), 'quantity')); @endphp
                            @if($cartCount > 0)
                                <span class="position-absolute translate-middle badge rounded-pill bg-danger" style="top:0; left:22px; font-size:0.6rem;">{{ $cartCount }}</span>
                            @endif
                            Giỏ hàng
                        </a>

                        @auth
                            <div class="dropdown">
                                <button class="header-icon-link border-0 bg-transparent" type="button" data-bs-toggle="dropdown">
                                    <i class="fa-solid fa-circle-user"></i>
                                    {{ Str::limit(auth()->user()->name, 10) }}
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end shadow-sm">
                                    @if(auth()->user()->role === 'admin')
                                        <li><a class="dropdown-item text-danger fw-bold" href="{{ route('admin.orders.index') }}"><i class="fa-solid fa-user-shield me-2"></i>Trang Quản Lý Admin</a></li>
                                        <li><a class="dropdown-item fw-semibold" href="{{ route('admin.products.index') }}"><i class="fa-solid fa-boxes-stacked me-2"></i>Quản Lý Sản Phẩm</a></li>
                                        <li><hr class="dropdown-divider"></li>
                                    @endif
                                    <li><a class="dropdown-item" href="{{ route('cart.index') }}"><i class="fa-solid fa-cart-shopping me-2"></i>Giỏ hàng của tôi</a></li>
                                    <li><a class="dropdown-item" href="{{ route('orders.index') }}"><i class="fa-solid fa-clipboard-list me-2"></i>Lịch sử mua hàng</a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li>
                                        <form action="{{ route('logout') }}" method="POST">
                                            @csrf
                                            <button type="submit" class="dropdown-item text-danger fw-semibold"><i class="fa-solid fa-right-from-bracket me-2"></i>Đăng xuất</button>
                                        </form>
                                    </li>
                                </ul>
                            </div>
                        @else
                            <a href="{{ route('login') }}" class="header-icon-link">
                                <i class="fa-solid fa-right-to-bracket"></i> Đăng nhập
                            </a>
                        @endauth
                    </div>
                </div>
            </div>
        </header>
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
                            @foreach($categories->take(4) as $category)
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
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
@include('partials.chatbot');
</body>
</html>