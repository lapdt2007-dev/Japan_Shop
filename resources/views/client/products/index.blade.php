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
    <link rel="stylesheet" href="{{ asset('css/order.css') }}">

</head>
<body class="bg-light">

    <!-- ============ SIDEBAR TRÁI ============ -->
    @include('layouts.sidebar')

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

        @include('layouts.footer')
    </div><!-- /.main-wrapper -->

    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
