<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $product->name }} - Japan Shop</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-light">

    <!-- Header Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-danger sticky-top">
        <div class="container">
            <a class="navbar-brand fw-bold" href="{{ route('home') }}">🇯🇵 JAPAN SHOP</a>
            <div class="d-flex align-items-center gap-2">
                @php
                    $cartCount = array_sum(array_column(session('cart', []), 'quantity'));
                @endphp
                <a href="{{ route('cart.index') }}" class="btn btn-outline-light position-relative btn-sm me-2">
                    <i class="fa-solid fa-cart-shopping me-1"></i> Giỏ hàng
                    @if($cartCount > 0)
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-warning text-dark">
                            {{ $cartCount }}
                        </span>
                    @endif
                </a>
                <a href="{{ route('home') }}" class="btn btn-outline-light btn-sm">
                    <i class="fa-solid fa-arrow-left me-1"></i> Trang chủ
                </a>
            </div>
        </div>
    </nav>

    <!-- Thông báo Alert khi Thêm giỏ hàng thành công -->
    @if(session('success'))
        <div class="container mt-4">
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fa-solid fa-circle-check me-2"></i>{{ session('success') }}
                <a href="{{ route('cart.index') }}" class="fw-bold text-success ms-2">Đến giỏ hàng ngay <i class="fa-solid fa-arrow-right"></i></a>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        </div>
    @endif

    <!-- Main Product Detail -->
    <div class="container my-4">
        <div class="card border-0 shadow-sm p-4 mb-5">
            <div class="row g-4">
                <!-- Ảnh sản phẩm -->
                <div class="col-md-5">
                    <img src="{{ $product->image }}" class="img-fluid rounded border w-100" style="max-height: 400px; object-fit: cover;" alt="{{ $product->name }}" onerror="this.src='https://via.placeholder.com/500x500?text=Japan+Shop'">
                </div>

                <!-- Thông tin chi tiết -->
                <div class="col-md-7 d-flex flex-column justify-content-between">
                    <div>
                        <span class="badge bg-secondary mb-2 fs-6">{{ $product->category->name ?? 'Nhật Bản' }}</span>
                        <h2 class="fw-bold text-dark mb-3">{{ $product->name }}</h2>
                        <h3 class="text-danger fw-bold my-3 fs-2">{{ number_format($product->price, 0, ',', '.') }} đ</h3>
                        <p class="text-muted fs-6 leading-relaxed">{{ $product->description }}</p>
                        <p class="small text-secondary">Tình trạng kho: <strong>{{ $product->stock }}</strong> sản phẩm có sẵn</p>
                    </div>

                    <!-- FORM THÊM GIỎ HÀNG CÓ SỐ LƯỢNG NÀY BRO -->
                    <form action="{{ route('cart.add', $product->id) }}" method="POST" class="mt-4">
                        @csrf
                        <div class="d-flex align-items-center gap-3">
                            <div style="width: 100px;">
                                <input type="number" name="quantity" value="1" min="1" class="form-control form-control-lg text-center" required>
                            </div>
                            <button type="submit" class="btn btn-danger btn-lg px-4 fw-semibold">
                                <i class="fa-solid fa-cart-plus me-2"></i>Thêm vào giỏ hàng
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Sản phẩm liên quan -->
        @if($relatedProducts->count() > 0)
            <h4 class="fw-bold mb-4 border-start border-4 border-danger ps-3">Sản Phẩm Liên Quan</h4>
            <div class="row row-cols-1 row-cols-sm-2 row-cols-md-4 g-4">
                @foreach($relatedProducts as $item)
                    <div class="col">
                        <div class="card h-100 border-0 shadow-sm">
                            <img src="{{ $item->image }}" class="card-img-top" alt="{{ $item->name }}" style="height: 180px; object-fit: cover;" onerror="this.src='https://via.placeholder.com/300x300?text=Japan+Shop'">
                            <div class="card-body d-flex flex-column">
                                <h6 class="card-title text-truncate fw-bold">{{ $item->name }}</h6>
                                <p class="text-danger fw-bold mt-auto mb-2">{{ number_format($item->price, 0, ',', '.') }} đ</p>
                                <a href="{{ route('products.show', $item->slug) }}" class="btn btn-sm btn-outline-danger">Xem chi tiết</a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

</body>
</html>