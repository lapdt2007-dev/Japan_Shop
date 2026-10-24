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
    <link rel="stylesheet" href="{{ asset('css/world.css') }}">
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

    @include('layouts.sidebar')

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

    @include('layouts.footer')
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
