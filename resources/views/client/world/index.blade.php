<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quốc tế - Japan Shop</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- CSS dùng chung cho sidebar + layout (tách riêng, không lặp code) -->
    <link rel="stylesheet" href="{{ asset('css/sidebar-layout.css') }}">

    <style>
        .main-wrapper { min-width: 0; }

        body {
            font-family: 'Inter', sans-serif;
            background: #f5f5f7;
            color: #222;
        }

        .top-header {
            background: #e52d45;
            border-bottom: 1px solid #d51f36;
            min-height: 54px;
        }

        .top-header .brand-title {
            color: #fff !important;
            font-size: 1.2rem;
            font-weight: 700;
            letter-spacing: .2px;
        }

        .home-return-btn {
            color: #fff;
            border: 1px solid rgba(255,255,255,.9);
            border-radius: 5px;
            padding: 5px 11px;
            font-size: .85rem;
            text-decoration: none;
            transition: .2s ease;
        }

        .home-return-btn:hover {
            background: #fff;
            color: #e52d45;
        }

        .search-pill {
            border-radius: 999px;
            overflow: hidden;
            border: 2px solid var(--brand);
        }

        .search-pill input {
            border: 0;
            box-shadow: none;
        }

        .search-pill input:focus { box-shadow: none; }

        .btn-search {
            background: var(--brand);
            color: #fff;
            border: 0;
            font-weight: 700;
            padding: 0 22px;
        }

        .header-icon-link {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 2px;
            font-size: .72rem;
            color: #444;
            text-decoration: none;
            white-space: nowrap;
        }

        .header-icon-link i { font-size: 1.05rem; }
        .header-icon-link:hover { color: var(--brand); }

        .page-title {
            font-size: 2rem;
            font-weight: 700;
            margin: 0;
            color: #111;
        }

        .page-search input {
            border: 0;
            outline: 0;
            box-shadow: none;
            background: transparent;
        }

        .page-search {
            background: #fff;
            border: 1px solid #d9d9d9;
            border-radius: 7px;
            padding: 0 0 0 14px;
            overflow: hidden;
            min-height: 47px;
        }

        .page-search input {
            min-height: 45px;
            font-size: 1rem;
        }

        .page-search button {
            width: 143px;
            height: 47px;
            border-radius: 0;
            border: 0;
            background: #e52d45;
            color: #fff;
            font-size: 1rem;
        }

        .hero-card,
        .recommend-card,
        .export-card,
        .category-card,
        .product-section {
            background: #fff;
            border-radius: 10px;
        }

        .hero-card {
            height: 294px;
            overflow: hidden;
            position: relative;
        }

        .hero-card img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .hero-overlay {
            position: absolute;
            inset: auto 0 0;
            padding: 20px 18px 16px;
            color: #fff;
            background: linear-gradient(transparent, rgba(0,0,0,.72));
        }

        .recommend-card {
            padding: 9px 10px 12px;
            height: 294px;
        }

        .recommend-title {
            font-weight: 700;
            font-size: .85rem;
            margin-bottom: 8px;
        }

        .recommend-tabs {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 5px;
        }

        .recommend-tab {
            background: #fff7f7;
            padding: 7px;
            border-radius: 5px;
            min-width: 0;
        }

        .recommend-tab h6 {
            font-size: .75rem;
            margin-bottom: 5px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .mini-images {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 3px;
        }

        .mini-images img {
            width: 100%;
            height: 70px;
            object-fit: cover;
            border-radius: 3px;
        }

        .export-card {
            padding: 10px;
            height: 144px;
            overflow: hidden;
        }

        .export-title {
            font-size: .85rem;
            font-weight: 700;
            margin-bottom: 6px;
        }

        .export-images {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 5px;
        }

        .export-images img {
            width: 100%;
            height: 92px;
            object-fit: cover;
            border-radius: 4px;
        }

        .category-card {
            padding: 12px;
        }

        .category-row {
            display: flex;
            flex-wrap: wrap;
            gap: 12px 22px;
            align-items: center;
        }

        .category-link {
            color: #333;
            font-size: .76rem;
            font-weight: 600;
            text-decoration: none;
            white-space: nowrap;
        }

        .category-link:hover,
        .category-link.active { color: var(--brand); }

        .market-row,
        .service-row {
            display: flex;
            flex-wrap: wrap;
            gap: 16px 28px;
            font-size: .75rem;
            font-weight: 600;
        }

        .product-section { padding: 12px; }

        .product-card {
            height: 100%;
            background: #fff;
            border: 1px solid #e7e7e7;
            border-radius: 8px;
            overflow: hidden;
            transition: .25s ease;
        }

        .product-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 20px rgba(0,0,0,.09);
        }

        .product-image {
            width: 100%;
            height: 205px;
            object-fit: cover;
            display: block;
            background: #f2f2f2;
        }

        .product-body { padding: 9px; }

        .product-name {
            font-size: .78rem;
            line-height: 1.4;
            height: 34px;
            overflow: hidden;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
        }

        .price {
            color: #e2231a;
            font-weight: 700;
            font-size: .86rem;
            margin-top: 8px;
        }

        .old-price {
            color: #999;
            font-size: .72rem;
            text-decoration: line-through;
        }

        .shop-label {
            font-size: .68rem;
            font-weight: 600;
            color: #444;
        }

        .pagination { margin-bottom: 0; }

        footer a:hover { color: var(--brand) !important; }

        @media (max-width: 991.98px) {
            .hero-card { height: 260px; }
            .recommend-card { height: auto; }
        }
    </style>
</head>
<body>

@php
    $resolveImg = function ($product) {
        $img = $product->thumbnail ?? '';
        if (!$img) {
            return 'data:image/svg+xml,%3Csvg xmlns=%22http://www.w3.org/2000/svg%22 width=%22300%22 height=%22300%22%3E%3Crect width=%22100%25%22 height=%22100%25%22 fill=%22%23f1f1f1%22/%3E%3Ctext x=%2250%25%22 y=%2250%25%22 font-family=%22sans-serif%22 font-size=%2216%22 fill=%22%23999%22 text-anchor=%22middle%22 dy=%22.3em%22%3EJapan Shop%3C/text%3E%3C/svg%3E';
        }
        return filter_var($img, FILTER_VALIDATE_URL)
            ? $img
            : asset('storage/' . ltrim($img, '/'));
    };

    $promo = $promoProducts->values();
@endphp

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

<div class="main-wrapper">
    <header class="top-header sticky-top">
        <div class="container-fluid px-3 px-lg-4">
            <div class="d-flex align-items-center justify-content-between" style="min-height:54px;">
                <a href="{{ route('home') }}" class="brand-title text-decoration-none">
                    JP JAPAN SHOP
                </a>

                <a href="{{ route('home') }}" class="home-return-btn">
                    <i class="fa-solid fa-arrow-left me-2"></i>Về trang chủ
                </a>
            </div>
        </div>
    </header>

    @if(session('success'))
        <div class="container-fluid px-3 px-lg-4 mt-3">
            <div class="alert alert-success alert-dismissible fade show">
                <i class="fa-solid fa-circle-check me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        </div>
    @endif

    <main class="container-fluid px-4 px-lg-5 py-5">
        <div class="mb-4">
            <h1 class="page-title mb-4">
                <i class="fa-solid fa-box-open me-2" style="color:#e52d45;font-size:1.7rem;"></i>
                Tin tức sản phẩm quốc tế
            </h1>
            <form action="{{ route('world.index') }}" method="GET" class="page-search d-flex align-items-center w-100">
                <input type="text" name="search" class="form-control" placeholder="Tìm kiếm sản phẩm..." value="{{ request('search') }}">
                <button type="submit"><i class="fa-solid fa-magnifying-glass me-2"></i>Tìm kiếm</button>
            </form>
        </div>

        <div class="row g-3 mb-3">
            <div class="col-xl-3 col-lg-4">
                <div class="hero-card">
                    @if($promo->get(0))
                        <img src="{{ $resolveImg($promo[0]) }}" alt="Sản phẩm quốc tế" onerror="this.style.display='none'">
                    @endif
                    <div class="hero-overlay">
                        <div class="small fw-semibold">JAPAN SHOP GLOBAL</div>
                        <div class="fw-bold fs-5">Sản phẩm Nhật Bản vươn ra thế giới</div>
                        <div class="small">Chất lượng • Chính hãng • Giao hàng tận nơi</div>
                    </div>
                </div>
            </div>

            <div class="col-xl-9 col-lg-8">
                <div class="recommend-card">
                    <div class="recommend-title">Khuyến Nghị</div>
                    <div class="recommend-tabs">
                        <div class="recommend-tab">
                            <h6>Sản phẩm được chứng nhận</h6>
                            <div class="mini-images">
                                @foreach($promo->slice(1, 3) as $product)
                                    <img src="{{ $resolveImg($product) }}" alt="{{ $product->name }}">
                                @endforeach
                            </div>
                        </div>
                        <div class="recommend-tab">
                            <h6>Lựa chọn thêm</h6>
                            <div class="mini-images">
                                @foreach($promo->slice(4, 3) as $product)
                                    <img src="{{ $resolveImg($product) }}" alt="{{ $product->name }}">
                                @endforeach
                            </div>
                        </div>
                        <div class="recommend-tab">
                            <h6>Ưu đãi nóng</h6>
                            <div class="mini-images">
                                @foreach($promo->slice(7, 3) as $product)
                                    <img src="{{ $resolveImg($product) }}" alt="{{ $product->name }}">
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <div class="row g-2 mt-2">
                        <div class="col-md-6">
                            <div class="export-card">
                                <div class="export-title">Sản phẩm xuất khẩu</div>
                                <div class="export-images">
                                    @foreach($promo->slice(2, 3) as $product)
                                        <a href="{{ route('client.products.show', $product->slug) }}">
                                            <img src="{{ $resolveImg($product) }}" alt="{{ $product->name }}">
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="export-card">
                                <div class="export-title">Cửa hàng phái mua</div>
                                <div class="export-images">
                                    @foreach($promo->slice(5, 3) as $product)
                                        <a href="{{ route('client.products.show', $product->slug) }}">
                                            <img src="{{ $resolveImg($product) }}" alt="{{ $product->name }}">
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="category-card mb-3">
            <div class="fw-bold small mb-2">Danh mục sản phẩm:</div>
            <div class="category-row mb-3">
                @foreach($categories as $category)
                    <a class="category-link {{ request('category') === $category->slug ? 'active' : '' }}" href="{{ route('world.index', ['category' => $category->slug]) }}">
                        {{ $category->name }}
                    </a>
                @endforeach
            </div>

            <div class="d-flex flex-wrap align-items-center gap-4 mb-3">
                <span class="fw-bold small">thị trường:</span>
                <a href="{{ route('world.index') }}" class="category-link">Nhật Bản</a>
                <a href="{{ route('world.index') }}" class="category-link">Việt Nam</a>
                <span class="category-link">Châu Á</span>
                <span class="category-link">Quốc tế</span>
            </div>

            <div class="d-flex flex-wrap align-items-center gap-4">
                <span class="fw-bold small">Dịch vụ:</span>
                <span class="category-link">Giao hàng nhanh</span>
                <span class="category-link">Hàng chính hãng</span>
                <span class="category-link">Giấy chứng nhận đầy đủ</span>
                <span class="category-link">Miễn phí trả hàng trong và ngoài nước</span>
            </div>
        </div>

        <section class="product-section">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div>
                    <h5 class="fw-bold mb-1">Gợi ý <span class="text-muted small fw-normal">Khuyến nghị dựa trên sản phẩm hiện có</span></h5>
                    <div class="text-muted small">
                        {{ method_exists($products, 'total') ? $products->total() : $products->count() }} sản phẩm
                    </div>
                </div>
                @if(request('category') || request('search'))
                    <a href="{{ route('world.index') }}" class="btn btn-sm btn-outline-secondary rounded-pill">Bỏ lọc</a>
                @endif
            </div>

            @if($products->isEmpty())
                <div class="text-center py-5">
                    <i class="fa-solid fa-box-open fa-3x text-muted mb-3"></i>
                    <h6>Chưa tìm thấy sản phẩm phù hợp</h6>
                    <a href="{{ route('world.index') }}" class="btn btn-danger rounded-pill mt-2">Xem tất cả</a>
                </div>
            @else
                <div class="row row-cols-2 row-cols-sm-3 row-cols-md-4 row-cols-xl-5 g-3">
                    @foreach($products as $product)
                        <div class="col">
                            <a href="{{ route('client.products.show', $product->slug) }}" class="text-decoration-none text-dark">
                                <div class="product-card">
                                    <img class="product-image" src="{{ $resolveImg($product) }}" alt="{{ $product->name }}" onerror="this.src='{{ asset('images/no-image.png') }}'">
                                    <div class="product-body">
                                        <div class="product-name">{{ $product->name }}</div>
                                        <div class="price">{{ number_format($product->sale_price ?? $product->price, 0, ',', '.') }}đ</div>
                                        @if($product->sale_price && $product->price > $product->sale_price)
                                            <div class="old-price">¥ {{ number_format($product->price, 0, ',', '.') }}</div>
                                        @else
                                            <div class="old-price">&nbsp;</div>
                                        @endif
                                        <div class="d-flex justify-content-between align-items-center mt-2">
                                            <span class="shop-label">Shop uy tín</span>
                                            <i class="fa-regular fa-heart text-secondary"></i>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                    @endforeach
                </div>

                <div class="d-flex justify-content-center mt-4">
                    {{ $products->links('pagination::bootstrap-5') }}
                </div>
            @endif
        </section>
    </main>

    <footer id="footer-info" class="bg-white border-top mt-4 pt-5 pb-3">
        <div class="container-fluid px-3 px-lg-4">
            <div class="row g-4 mb-4">
                <div class="col-lg-4 col-md-6">
                    <h5 class="fw-bold text-danger mb-3"><i class="fa-solid fa-torii-gate me-2"></i>JAPAN SHOP</h5>
                    <p class="text-muted small">Chuyên cung cấp các mặt hàng nội địa Nhật Bản chất lượng cao và hỗ trợ khách hàng trong nước, quốc tế.</p>
                </div>
                <div class="col-lg-2 col-md-6">
                    <h6 class="fw-bold mb-3">Điều hướng</h6>
                    <ul class="list-unstyled small">
                        <li class="mb-2"><a href="{{ route('home') }}" class="text-decoration-none text-muted">Trang chủ</a></li>
                        <li class="mb-2"><a href="{{ route('about.index') }}" class="text-decoration-none text-muted">Giới thiệu</a></li>
                        <li class="mb-2"><a href="{{ route('world.index') }}" class="text-decoration-none text-danger">Quốc tế</a></li>
                    </ul>
                </div>
                <div class="col-lg-3 col-md-6">
                    <h6 class="fw-bold mb-3">Dịch vụ quốc tế</h6>
                    <ul class="list-unstyled small text-muted">
                        <li class="mb-2">Giao hàng nhanh</li>
                        <li class="mb-2">Hàng chính hãng</li>
                        <li class="mb-2">Hỗ trợ khách hàng</li>
                    </ul>
                </div>
                <div class="col-lg-3 col-md-6">
                    <h6 class="fw-bold mb-3">Liên hệ</h6>
                    <p class="text-muted small mb-2"><i class="fa-solid fa-location-dot text-danger me-2"></i>TP. Hồ Chí Minh, Việt Nam</p>
                    <p class="text-muted small mb-2"><i class="fa-solid fa-phone text-danger me-2"></i>0123 456 789</p>
                    <p class="text-muted small"><i class="fa-solid fa-envelope text-danger me-2"></i>support@japanshop.vn</p>
                </div>
            </div>
            <hr class="my-4 text-muted">
            <div class="text-center text-muted small">© {{ date('Y') }} Đinh Thành Lập, Trần Tấn Đạt, Lê Nguyễn An Trường.</div>
        </div>
    </footer>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>