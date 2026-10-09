<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mua Hàng Của Tôi - Japan Shop</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

        <!-- CSS dùng chung cho sidebar + layout (tách riêng, không lặp code) -->
    <link rel="stylesheet" href="{{ asset('css/sidebar-layout.css') }}">

    <style>
.order-tabs { border-bottom: 1px solid #eee; background: #fff; }
        .order-tabs a {
            display: inline-block;
            padding: 14px 18px;
            color: #555;
            text-decoration: none;
            font-weight: 600;
            font-size: 0.92rem;
            border-bottom: 3px solid transparent;
            white-space: nowrap;
        }
        .order-tabs a.active { color: #dc3545; border-bottom-color: #dc3545; }
        .order-tabs a:hover { color: #dc3545; }

        .filter-card { background: #fff; border-radius: 10px; }

        .order-table thead th {
            background: #fafafa;
            font-weight: 600;
            font-size: 0.85rem;
            color: #555;
            white-space: nowrap;
        }

        .order-table td { vertical-align: middle; }

        .product-thumb {
            width: 52px; height: 52px;
            object-fit: cover;
            border-radius: 8px;
            border: 1px solid #eee;
        }

        .order-group-header {
            background: #f8f9fa;
            font-size: 0.85rem;
        }
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

    <nav class="navbar navbar-expand-lg navbar-dark bg-danger sticky-top">
        <div class="container">
            <a class="navbar-brand fw-bold" href="{{ route('home') }}">🇯🇵 JAPAN SHOP</a>
            <a href="{{ route('home') }}" class="btn btn-outline-light btn-sm">
                <i class="fa-solid fa-arrow-left me-1"></i> Về trang chủ
            </a>
        </div>
    </nav>

    <div class="container my-4">

        <h4 class="fw-bold mb-3"><i class="fa-solid fa-bag-shopping text-danger me-2"></i>Mua Hàng Của Tôi</h4>

        @php
            $tabs = [
                'all' => 'Tất cả',
                'unpaid' => 'Chờ thanh toán',
                'processing' => 'Chờ vận chuyển',
                'shipping' => 'Chờ giao hàng',
                'cancelled' => 'Yêu cầu hoàn tiền',
                'not_reviewed' => 'Chưa đánh giá',
                'reviewed' => 'Đã đánh giá',
            ];
        @endphp

        <!-- ===== TAB TRẠNG THÁI ===== -->
        <div class="order-tabs mb-3 overflow-auto">
            @foreach($tabs as $key => $label)
                <a href="{{ route('orders.index', ['tab' => $key]) }}" class="{{ $tab === $key ? 'active' : '' }}">
                    {{ $label }}
                </a>
            @endforeach
        </div>

        <!-- ===== BỘ LỌC TÌM KIẾM ===== -->
        <div class="filter-card shadow-sm p-3 mb-3">
            <form method="GET" action="{{ route('orders.index') }}" class="row g-3">
                <input type="hidden" name="tab" value="{{ $tab }}">

                <div class="col-md-4">
                    <label class="form-label small text-muted mb-1">Nhập đơn hàng</label>
                    <input type="text" name="keyword" class="form-control form-control-sm" placeholder="Tên sản phẩm / Số đơn hàng" value="{{ request('keyword') }}">
                </div>

                <div class="col-md-4">
                    <label class="form-label small text-muted mb-1">Thời gian đặt hàng</label>
                    <div class="d-flex gap-2">
                        <input type="date" name="date_from" class="form-control form-control-sm" value="{{ request('date_from') }}">
                        <input type="date" name="date_to" class="form-control form-control-sm" value="{{ request('date_to') }}">
                    </div>
                </div>

                <div class="col-md-4">
                    <label class="form-label small text-muted mb-1">Phương thức thanh toán</label>
                    <select name="payment_method" class="form-select form-select-sm">
                        <option value="all">Tất cả</option>
                        <option value="cod" {{ request('payment_method') === 'cod' ? 'selected' : '' }}>Thanh toán khi nhận hàng (COD)</option>
                        <option value="bank_transfer" {{ request('payment_method') === 'bank_transfer' ? 'selected' : '' }}>Chuyển khoản ngân hàng</option>
                        <option value="momo" {{ request('payment_method') === 'momo' ? 'selected' : '' }}>Ví MoMo</option>
                    </select>
                </div>

                <div class="col-md-4">
                    <label class="form-label small text-muted mb-1">Trạng thái đơn hàng</label>
                    <select name="status" class="form-select form-select-sm">
                        <option value="all">Tất cả</option>
                        <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Chờ xử lý</option>
                        <option value="processing" {{ request('status') === 'processing' ? 'selected' : '' }}>Đang chuẩn bị</option>
                        <option value="shipping" {{ request('status') === 'shipping' ? 'selected' : '' }}>Đang giao hàng</option>
                        <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Hoàn thành</option>
                        <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Đã hủy</option>
                    </select>
                </div>

                <div class="col-md-4">
                    <label class="form-label small text-muted mb-1">Số tiền</label>
                    <div class="d-flex gap-2 align-items-center">
                        <input type="number" name="price_min" class="form-control form-control-sm" placeholder="Số tiền tối thiểu" value="{{ request('price_min') }}">
                        <span class="text-muted">-</span>
                        <input type="number" name="price_max" class="form-control form-control-sm" placeholder="Số tiền tối đa" value="{{ request('price_max') }}">
                    </div>
                </div>

                <div class="col-md-4 d-flex align-items-end justify-content-end gap-2">
                    <a href="{{ route('orders.index', ['tab' => $tab]) }}" class="btn btn-outline-secondary btn-sm">Xóa các tùy chọn</a>
                    <button type="submit" class="btn btn-danger btn-sm px-4">
                        <i class="fa-solid fa-magnifying-glass me-1"></i> Tìm kiếm
                    </button>
                </div>
            </form>
        </div>

        <!-- ===== BẢNG ĐƠN HÀNG ===== -->
        <div class="filter-card shadow-sm p-0">
            @if($orders->isEmpty())
                <div class="text-center py-5">
                    <i class="fa-solid fa-box-open fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted mb-1">Chưa có đơn hàng nào</h5>
                    <p class="text-muted small mb-3">Hãy đặt hàng để hiện tại đây nha anh</p>
                    <a href="{{ route('home') }}" class="btn btn-danger btn-sm px-4">Mua sắm ngay</a>
                </div>
            @else
                @php
                    $statusMap = [
                        'pending' => ['label' => 'Chờ xử lý', 'class' => 'bg-secondary text-white'],
                        'processing' => ['label' => 'Đang chuẩn bị', 'class' => 'bg-info text-dark'],
                        'shipping' => ['label' => 'Đang giao hàng', 'class' => 'bg-primary text-white'],
                        'completed' => ['label' => 'Hoàn thành', 'class' => 'bg-success text-white'],
                        'cancelled' => ['label' => 'Đã hủy', 'class' => 'bg-danger text-white'],
                    ];
                @endphp

                @foreach($orders as $order)
                    @php $currentStatus = $statusMap[$order->order_status ?? 'pending'] ?? $statusMap['pending']; @endphp

                    <div class="order-group-header px-3 py-2 d-flex justify-content-between align-items-center border-bottom">
                        <div>
                            <span class="text-muted">Mã đơn:</span>
                            <strong class="text-danger">#{{ $order->order_code ?? $order->id }}</strong>
                            <span class="text-muted ms-3"><i class="fa-regular fa-clock me-1"></i>{{ date('d/m/Y H:i', strtotime($order->created_at)) }}</span>
                        </div>
                        <span class="badge {{ $currentStatus['class'] }} px-3 py-2">{{ $currentStatus['label'] }}</span>
                    </div>

                    <div class="table-responsive">
                        <table class="table order-table mb-0">
                            <thead>
                                <tr>
                                    <th style="width:32%">Hàng hóa</th>
                                    <th class="text-end">Đơn giá</th>
                                    <th class="text-center">Số lượng</th>
                                    <th class="text-end">Tổng số tiền</th>
                                    <th>Người bán</th>
                                    <th>Trạng thái đơn hàng</th>
                                    <th class="text-center">Thao tác giao dịch</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($order->items as $item)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center gap-2">
                                                @php
                                                    $prodImg = $item->product->thumbnail ?? '';
                                                    $prodImgUrl = $prodImg
                                                        ? (filter_var($prodImg, FILTER_VALIDATE_URL) ? $prodImg : asset('storage/' . ltrim($prodImg, '/')))
                                                        : 'data:image/svg+xml,%3Csvg xmlns=%22http://www.w3.org/2000/svg%22 width=%22300%22 height=%22300%22%3E%3Crect width=%22100%25%22 height=%22100%25%22 fill=%22%23f1f1f1%22/%3E%3Ctext x=%2250%25%22 y=%2250%25%22 font-family=%22sans-serif%22 font-size=%2216%22 fill=%22%23999%22 text-anchor=%22middle%22 dy=%22.3em%22%3EJapan Shop%3C/text%3E%3C/svg%3E';
                                                @endphp
                                                <img src="{{ $prodImgUrl }}" class="product-thumb" alt="" onerror="this.onerror=null;this.src='data:image/svg+xml,%3Csvg xmlns=%22http://www.w3.org/2000/svg%22 width=%22300%22 height=%22300%22%3E%3Crect width=%22100%25%22 height=%22100%25%22 fill=%22%23f1f1f1%22/%3E%3Ctext x=%2250%25%22 y=%2250%25%22 font-family=%22sans-serif%22 font-size=%2216%22 fill=%22%23999%22 text-anchor=%22middle%22 dy=%22.3em%22%3EJapan Shop%3C/text%3E%3C/svg%3E'">
                                                <span class="small fw-semibold">{{ $item->product->name ?? 'Sản phẩm đã xóa' }}</span>
                                            </div>
                                        </td>
                                        <td class="text-end small">{{ number_format($item->price, 0, ',', '.') }}đ</td>
                                        <td class="text-center small">{{ $item->quantity }}</td>
                                        <td class="text-end small fw-bold text-danger">{{ number_format($item->price * $item->quantity, 0, ',', '.') }}đ</td>
                                        <td class="small">Japan Shop</td>
                                        <td class="small">{{ $currentStatus['label'] }}</td>
                                        <td class="text-center">
                                            @if($order->order_status === 'completed' && $item->product && !in_array($item->product->id, $reviewedProductIds))
                                                <a href="{{ route('client.products.show', $item->product->slug) }}" class="btn btn-sm btn-outline-danger">Đánh giá</a>
                                            @elseif($order->order_status === 'completed')
                                                <span class="badge bg-light text-success border">Đã đánh giá</span>
                                            @else
                                                <a href="{{ route('client.products.show', $item->product->slug ?? '') }}" class="btn btn-sm btn-outline-secondary">Xem sản phẩm</a>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-end px-3 py-2 border-bottom">
                        <span class="text-muted small me-2">Tổng thanh toán đơn hàng:</span>
                        <strong class="text-danger">{{ number_format($order->total_amount ?? 0, 0, ',', '.') }}đ</strong>
                    </div>
                @endforeach
            @endif
        </div>
    </div>

    </div><!-- /.main-wrapper -->

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>