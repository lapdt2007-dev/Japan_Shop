<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thanh Toán - Japan Shop</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-light">

    <!-- Header Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-danger sticky-top">
        <div class="container">
            <a class="navbar-brand fw-bold" href="{{ route('home') }}">🇯🇵 JAPAN SHOP</a>
            <a href="{{ route('cart.index') }}" class="btn btn-outline-light btn-sm">
                <i class="fa-solid fa-cart-shopping me-1"></i> Quay lại giỏ hàng
            </a>
        </div>
    </nav>

    <div class="container my-5">
        <h3 class="fw-bold mb-4"><i class="fa-solid fa-credit-card text-danger me-2"></i>Thanh Toán Đơn Hàng</h3>

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fa-solid fa-triangle-exclamation me-2"></i>{{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <form action="{{ route('checkout.process') }}" method="POST">
            @csrf
            <div class="row g-4">
                
                <!-- Cột Trái: Thông tin giao hàng & Thanh toán -->
                <div class="col-lg-7">
                    <!-- Form thông tin giao hàng -->
                    <div class="card border-0 shadow-sm p-4 mb-4">
                        <h5 class="fw-bold border-bottom pb-3 mb-3 text-danger">
                            <i class="fa-solid fa-truck-fast me-2"></i>Thông Tin Giao Hàng
                        </h5>

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Họ và tên <span class="text-danger">*</span></label>
                                <input type="text" name="fullname" class="form-control @error('fullname') is-invalid @enderror" 
                                       value="{{ old('fullname', auth()->user()->name ?? '') }}" placeholder="Nguyễn Văn A">
                                @error('fullname')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Số điện thoại <span class="text-danger">*</span></label>
                                <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror" 
                                       value="{{ old('phone', auth()->user()->phone ?? '') }}" placeholder="0901234567">
                                @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12">
                                <label class="form-label fw-semibold">Địa chỉ Email</label>
                                <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" 
                                       value="{{ old('email', auth()->user()->email ?? '') }}" placeholder="email@example.com">
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12">
                                <label class="form-label fw-semibold">Địa chỉ nhận hàng <span class="text-danger">*</span></label>
                                <textarea name="address" rows="2" class="form-control @error('address') is-invalid @enderror" 
                                          placeholder="Số nhà, tên đường, phường/xã, quận/huyện, tỉnh/thành phố">{{ old('address') }}</textarea>
                                @error('address')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12">
                                <label class="form-label fw-semibold">Ghi chú đơn hàng (Tùy chọn)</label>
                                <textarea name="note" rows="2" class="form-control" 
                                          placeholder="Ghi chú thêm về thời gian giao hàng hoặc chỉ dẫn địa điểm...">{{ old('note') }}</textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Phương thức thanh toán -->
                    <div class="card border-0 shadow-sm p-4">
                        <h5 class="fw-bold border-bottom pb-3 mb-3 text-danger">
                            <i class="fa-solid fa-wallet me-2"></i>Phương Thức Thanh Toán
                        </h5>

                        <div class="form-check mb-3 p-3 border rounded">
                            <input class="form-check-input ms-0 me-2" type="radio" name="payment_method" id="cod" value="cod" checked>
                            <label class="form-check-label fw-bold cursor-pointer" for="cod">
                                <i class="fa-solid fa-money-bill-wave text-success me-2"></i>Thanh toán khi nhận hàng (COD)
                            </label>
                            <p class="text-muted small mb-0 mt-1 ms-4">Thanh toán bằng tiền mặt trực tiếp cho nhân viên giao hàng khi nhận sản phẩm.</p>
                        </div>

                        <div class="form-check p-3 border rounded">
                            <input class="form-check-input ms-0 me-2" type="radio" name="payment_method" id="bank" value="bank">
                            <label class="form-check-label fw-bold cursor-pointer" for="bank">
                                <i class="fa-solid fa-building-columns text-primary me-2"></i>Chuyển khoản ngân hàng (QR Code)
                            </label>
                            <p class="text-muted small mb-0 mt-1 ms-4">Thông tin tài khoản và mã QR sẽ hiển thị sau khi bạn xác nhận đặt hàng.</p>
                        </div>

                        @error('payment_method')
                            <div class="text-danger small mt-2">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Cột Phải: Tóm tắt đơn hàng -->
                <!-- TÓM TẮT ĐƠN HÀNG Ở CHECKOUT -->
                <div class="col-lg-5">
                    <div class="card border-0 shadow-sm p-4 sticky-lg-top" style="top: 90px;">
                        <h5 class="fw-bold border-bottom pb-3 mb-3 text-dark">Đơn Hàng Của Bạn</h5>

                        <!-- Danh sách sản phẩm rút gọn -->
                        <div class="cart-items-list mb-3" style="max-height: 280px; overflow-y: auto;">
                            @foreach($cart as $item)
                                <div class="d-flex align-items-center gap-3 mb-3 pb-2 border-bottom">
                                    <img src="{{ asset('storage/' . $item['image']) }}" 
                                     alt="{{ $item['name'] }}" 
                                     style="width: 60px; height: 60px; object-fit: cover;" 
                                     class="rounded border" 
                                     onerror="this.src='https://via.placeholder.com/60'">
                                    <div class="flex-grow-1">
                                        <h6 class="mb-0 text-truncate small fw-bold" style="max-width: 180px;">{{ $item['name'] }}</h6>
                                        <small class="text-muted">SL: {{ $item['quantity'] }} x {{ number_format($item['price'], 0, ',', '.') }}đ</small>
                                    </div>
                                    <div class="fw-bold text-dark small">
                                        {{ number_format($item['price'] * $item['quantity'], 0, ',', '.') }}đ
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- CHI TIẾT TÍNH TIỀN -->
                        <div class="d-flex justify-content-between mb-2 text-muted">
                            <span>Tạm tính:</span>
                            <span class="fw-semibold">{{ number_format($totalPrice, 0, ',', '.') }}đ</span>
                        </div>

                        <!-- HIỂN THỊ DÒNG GIẢM GIÁ NẾU CÓ VOUCHER -->
                        @if(isset($discount) && $discount > 0)
                            <div class="d-flex justify-content-between mb-2 text-success">
                                <span>
                                    Voucher: <span class="badge bg-success-subtle text-success border border-success">{{ $voucher['code'] }}</span>
                                </span>
                                <span class="fw-bold">-{{ number_format($discount, 0, ',', '.') }}đ</span>
                            </div>
                        @endif

                        <div class="d-flex justify-content-between mb-3 text-muted">
                            <span>Phí vận chuyển:</span>
                            <span class="text-success fw-semibold">Miễn phí</span>
                        </div>

                        <hr>

                        <div class="d-flex justify-content-between mb-4">
                            <span class="fw-bold fs-5">Tổng thanh toán:</span>
                            <!-- DÙNG BIẾN $finalTotal ĐÃ TRỪ GIẢM GIÁ -->
                            <span class="fw-bold fs-4 text-danger">{{ number_format($finalTotal, 0, ',', '.') }}đ</span>
                        </div>

                        <button type="submit" class="btn btn-danger btn-lg rounded-pill w-100 fw-bold shadow-sm">
                            <i class="fa-solid fa-check-circle me-2"></i>Xác Nhận Đặt Hàng
                        </button>
                    </div>
                </div>

            </div>
        </form>
    </div>

    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>