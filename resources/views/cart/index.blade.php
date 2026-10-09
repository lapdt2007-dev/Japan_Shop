<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Giỏ Hàng - Japan Shop</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-light">

    <!-- Header Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-danger sticky-top">
        <div class="container">
            <a class="navbar-brand fw-bold" href="{{ route('home') }}">🇯🇵 JAPAN SHOP</a>
            <a href="{{ route('home') }}" class="btn btn-outline-light btn-sm">
                <i class="fa-solid fa-arrow-left me-1"></i> Tiếp tục mua sắm
            </a>
        </div>
    </nav>

    <div class="container my-5">
        <h3 class="fw-bold mb-4"><i class="fa-solid fa-cart-shopping text-danger me-2"></i>Giỏ Hàng Của Bạn</h3>

        <!-- Thông báo flash alert -->
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fa-solid fa-circle-check me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(empty($cart))
            <!-- Giỏ hàng trống -->
            <div class="card border-0 shadow-sm text-center p-5">
                <div class="card-body">
                    <i class="fa-solid fa-cart-flatbed-empty text-muted fa-4x mb-3"></i>
                    <h5 class="fw-bold text-secondary">Giỏ hàng của bạn đang trống!</h5>
                    <p class="text-muted small">Hãy chọn thêm sản phẩm từ cửa hàng để tiến hành mua hàng.</p>
                    <a href="{{ route('home') }}" class="btn btn-danger rounded-pill px-4 mt-2">Xem danh sách sản phẩm</a>
                </div>
            </div>
        @else
            <div class="row g-4">
                <!-- Bảng sản phẩm -->
                <div class="col-lg-8">
                    <div class="card border-0 shadow-sm p-3">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Sản phẩm</th>
                                        <th>Đơn giá</th>
                                        <th style="width: 140px;">Số lượng</th>
                                        <th>Thành tiền</th>
                                        <th class="text-center">Thao tác</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($cart as $id => $item)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center gap-3">
                                                    <img src="{{ asset('storage/' . $item['image']) }}" 
                                                    alt="{{ $item['name'] }}" 
                                                    style="width: 60px; height: 60px; object-fit: cover;" 
                                                    class="rounded border" 
                                                    onerror="this.src='https://via.placeholder.com/60'">
                                                    <div>
                                                        <a href="{{route('client.products.show', $item['slug']) }}" class="text-decoration-none text-dark fw-bold small">
                                                            {{ $item['name'] }}
                                                        </a>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="fw-semibold text-secondary">
                                                {{ number_format($item['price'], 0, ',', '.') }} đ
                                            </td>
                                            <td>
                                                <!-- Form Cập nhật Số lượng -->
                                                <form action="{{ route('cart.update', $id) }}" method="POST" class="d-flex gap-1">
                                                    @csrf
                                                    @method('PATCH')
                                                    <input type="number" name="quantity" value="{{ $item['quantity'] }}" min="1" class="form-control form-control-sm text-center">
                                                    <button type="submit" class="btn btn-sm btn-outline-secondary" title="Cập nhật">
                                                        <i class="fa-solid fa-rotate"></i>
                                                    </button>
                                                </form>
                                            </td>
                                            <td class="fw-bold text-danger">
                                                {{ number_format($item['price'] * $item['quantity'], 0, ',', '.') }} đ
                                            </td>
                                            <td class="text-center">
                                                <!-- Form Xóa 1 sản phẩm -->
                                                <form action="{{ route('cart.remove', $id) }}" method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-link text-danger p-0" onclick="return confirm('Bạn có muốn xóa sản phẩm này?')" title="Xóa">
                                                        <i class="fa-solid fa-trash-can fs-5"></i>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="d-flex justify-content-between align-items-center pt-3 mt-3 border-top">
                            <!-- Form Xóa toàn bộ -->
                            <form action="{{ route('cart.clear') }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-outline-danger btn-sm" onclick="return confirm('Bạn chắc chắn muốn xóa toàn bộ giỏ hàng?')">
                                    <i class="fa-solid fa-trash-arrow-up me-1"></i> Xóa toàn bộ giỏ hàng
                                </button>
                            </form>

                            <a href="{{ route('home') }}" class="btn btn-outline-secondary btn-sm">
                                <i class="fa-solid fa-plus me-1"></i> Thêm sản phẩm khác
                            </a>
                        </div>
                    </div>
                </div>

                <!-- TÓM TẮT ĐƠN HÀNG & VOUCHER -->
                <div class="col-lg-4">
                    <div class="card border-0 shadow-sm rounded-4 p-4">
                        <h5 class="fw-bold mb-3 border-bottom pb-3 text-dark">Tóm Tắt Đơn Hàng</h5>
                        
                        <!-- Ô NHẬP VOUCHER -->
                        <div class="mb-3">
                            <label class="form-label small fw-semibold text-muted">Mã giảm giá / Voucher</label>
                            @if(session('voucher'))
                                <!-- Hiển thị khi ĐÃ ÁP DỤNG mã -->
                                <div class="d-flex justify-content-between align-items-center bg-light border border-success rounded p-2">
                                    <div>
                                        <span class="badge bg-success me-1">{{ session('voucher.code') }}</span>
                                        <small class="text-muted d-block" style="font-size: 11px;">{{ session('voucher.name') }}</small>
                                    </div>
                                    <form action="{{ route('cart.voucher.remove') }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-link text-danger p-0" title="Xóa mã">
                                            <i class="fa-solid fa-xmark fs-5"></i>
                                        </button>
                                    </form>
                                </div>
                            @else
                                <!-- Form NHẬP MÃ -->
                                <form action="{{ route('cart.voucher.apply') }}" method="POST" class="d-flex gap-2">
                                    @csrf
                                    <input type="text" name="voucher_code" class="form-control form-control-sm text-uppercase" placeholder="Nhập mã (VD: JAPAN10)" required>
                                    <button type="submit" class="btn btn-dark btn-sm px-3 fw-semibold">Áp dụng</button>
                                </form>
                            @endif
                        </div>

                        <hr>

                        <!-- CHI TIẾT TÍNH TIỀN -->
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Tạm tính:</span>
                            <span class="fw-semibold">{{ number_format($totalPrice, 0, ',', '.') }}đ</span>
                        </div>

                        @if(isset($discount) && $discount > 0)
                            <div class="d-flex justify-content-between mb-2 text-success">
                                <span>Giảm giá (Voucher):</span>
                                <span class="fw-bold">-{{ number_format($discount, 0, ',', '.') }}đ</span>
                            </div>
                        @endif

                        <div class="d-flex justify-content-between mb-3">
                            <span class="text-muted">Phí vận chuyển:</span>
                            <span class="text-success fw-semibold">Miễn phí</span>
                        </div>
                        
                        <hr>
                        
                        <div class="d-flex justify-content-between mb-4">
                            <span class="fw-bold fs-5">Tổng cộng:</span>
                            <span class="fw-bold fs-4 text-danger">{{ number_format($finalTotal ?? $totalPrice, 0, ',', '.') }}đ</span>
                        </div>

                        <a href="{{ route('checkout.index') }}" class="btn btn-danger btn-lg rounded-pill fw-bold w-100 shadow-sm text-decoration-none text-center">
                            <i class="fa-solid fa-credit-card me-2"></i>Tiến Hành Thanh Toán
                        </a>
                    </div>
                </div>
            </div>
        @endif
    </div>

    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>