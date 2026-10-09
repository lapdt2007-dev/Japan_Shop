<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hàng Hóa - Japan Shop</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

        <!-- CSS dùng chung cho sidebar + layout (tách riêng, không lặp code) -->
    <link rel="stylesheet" href="{{ asset('css/sidebar-layout.css') }}">

    <style>
.product-card { border-radius: 14px; overflow: hidden; transition: all 0.3s ease; }
        .product-card:hover { transform: translateY(-6px); box-shadow: 0 12px 25px rgba(0,0,0,0.1) !important; }
        .product-img-wrapper { position: relative; height: 220px; overflow: hidden; background-color: #fff; }
        .product-img { width: 100%; height: 100%; object-fit: cover; transition: transform 0.4s ease; }
        .product-card:hover .product-img { transform: scale(1.08); }
        .product-badge { position: absolute; top: 12px; left: 12px; font-size: 0.75rem; padding: 4px 8px; border-radius: 6px; }
        .price-tag { color: #dc3545; font-size: 1.1rem; font-weight: 700; }
        .text-clamp-2 { display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }
        .category-chip { transition: all 0.2s ease-in-out; border-radius: 10px; white-space: nowrap; }
        .category-chip:hover { transform: translateY(-2px); }
    </style>
</head>
<body class="bg-light">

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
    <div class="main-wrapper">

    <!-- Header Navigation (đồng bộ style với trang Giỏ hàng) -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-danger sticky-top">
        <div class="container">
            <a class="navbar-brand fw-bold" href="{{ route('home') }}">🇯🇵 JAPAN SHOP</a>
            <a href="{{ route('home') }}" class="btn btn-outline-light btn-sm">
                <i class="fa-solid fa-arrow-left me-1"></i> Về trang chủ
            </a>
        </div>
    </nav>

    <div class="container my-5">
        <h3 class="fw-bold mb-4"><i class="fa-solid fa-box-open text-danger me-2"></i>Hàng Hóa</h3>

        <!-- Ô tìm kiếm -->
        <form action="{{ route('products.index') }}" method="GET" class="mb-4">
            <div class="input-group">
                <input type="text" name="search" class="form-control" placeholder="Tìm kiếm sản phẩm..." value="{{ request('search') }}">
                @if(request('category'))
                    <input type="hidden" name="category" value="{{ request('category') }}">
                @endif
                <button class="btn btn-danger" type="submit">
                    <i class="fa-solid fa-magnifying-glass me-1"></i> Tìm kiếm
                </button>
            </div>
        </form>

        <!-- Dòng danh mục -->
        <div class="d-flex gap-2 overflow-auto pb-3 mb-3">
            <a href="{{ route('products.index') }}" class="text-decoration-none">
                <div class="category-chip px-3 py-2 shadow-sm {{ !request('category') ? 'bg-danger text-white' : 'bg-white text-dark' }}">
                    <i class="fa-solid fa-boxes-stacked me-1"></i> Tất Cả
                </div>
            </a>
            @foreach($categories as $category)
                <a href="{{ route('products.index', ['category' => $category->slug]) }}" class="text-decoration-none">
                    <div class="category-chip px-3 py-2 shadow-sm {{ request('category') == $category->slug ? 'bg-danger text-white' : 'bg-white text-dark' }}">
                        {{ $category->name }}
                    </div>
                </a>
            @endforeach
        </div>

        <p class="text-muted small mb-3">
            Tìm thấy {{ method_exists($products, 'total') ? $products->total() : $products->count() }} sản phẩm phù hợp
        </p>

        @if($products->isEmpty())
            <div class="card border-0 shadow-sm text-center p-5">
                <div class="card-body">
                    <i class="fa-solid fa-box-open text-muted fa-4x mb-3"></i>
                    <h5 class="fw-bold text-secondary">Rất tiếc, chưa tìm thấy sản phẩm nào!</h5>
                    <p class="text-muted small">Hãy thử chọn lại danh mục khác hoặc xóa từ khóa tìm kiếm.</p>
                    <a href="{{ route('products.index') }}" class="btn btn-danger rounded-pill px-4 mt-2">Xem tất cả sản phẩm</a>
                </div>
            </div>
        @else
            <div class="row row-cols-2 row-cols-sm-3 row-cols-md-4 g-4">
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
    </div>

    </div><!-- /.main-wrapper -->

    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>