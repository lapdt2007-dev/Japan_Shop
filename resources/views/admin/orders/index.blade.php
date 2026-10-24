<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý đơn hàng - Kênh người bán</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/admin-orders.css') }}">
</head>
<body>

@php
    $tabs = [
        ''           => 'Tất cả',
        'pending'    => 'Chờ xử lý',
        'processing' => 'Đang xử lý',
        'shipping'   => 'Đang giao',
        'completed'  => 'Hoàn thành',
        'cancelled'  => 'Đã hủy',
    ];
    $statusLabel = [
        'pending'    => 'Chờ xử lý',
        'processing' => 'Đang xử lý',
        'shipping'   => 'Đang giao',
        'completed'  => 'Hoàn thành',
        'cancelled'  => 'Đã hủy',
    ];
    $counts   = isset($statusCounts) ? collect($statusCounts) : collect();
    $current  = request('status', '');
    $keyword  = request('keyword', '');
    $pending  = $counts->isNotEmpty() ? (int) $counts->get('pending', 0) : null;

    // Chuẩn bị phân trang (giữ từ khóa và tab khi chuyển trang)
    $orders->withQueryString();
    $cur  = $orders->currentPage();
    $last = $orders->lastPage();
    $from = max(1, $cur - 2);
    $to   = min($last, $cur + 2);
@endphp

<header class="topbar">
    <div class="topbar-inner">
        <a href="{{ route('admin.orders.index') }}" class="brand">Japan Shop</a>

        <form class="search-box" action="{{ route('admin.orders.index') }}" method="GET" role="search">
            @if($current !== '')
                <input type="hidden" name="status" value="{{ $current }}">
            @endif
            <input type="text" name="keyword" value="{{ $keyword }}" placeholder="Tìm theo mã đơn, tên người nhận, số điện thoại" aria-label="Tìm đơn hàng">
            <button type="submit">Tìm kiếm</button>
        </form>

        <nav class="top-links">
            <a href="/"><i class="fa-solid fa-house"></i> Trang chủ</a>
        </nav>
    </div>
</header>

<div class="shell">
    <aside class="side">
        <h2>Kênh người bán</h2>
        <a href="{{ route('admin.orders.index') }}" class="active">Q.lý đơn hàng</a>
        <a href="{{ route('admin.products.index') }}">Q.lý sản phẩm</a>
        <a href="{{ route('admin.products.create') }}">Thêm sản phẩm</a>
        <a href="{{ route('admin.feedback.index')}}">Phản hồi khách hàng</a>
    </aside>

    <main class="content">

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Đóng"></button>
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Đóng"></button>
            </div>
        @endif

        <div class="page-head">
            <div>
                <h1>Đơn hàng</h1>
                <div class="total tnum">{{ number_format($orders->total()) }} đơn hàng{{ ($keyword !== '' || $current !== '') ? ' khớp bộ lọc' : '' }}</div>
            </div>
        </div>

        {{-- Câu nhắc việc: cần $statusCounts từ controller --}}
        @if($pending !== null)
            @if($pending > 0)
                <p class="attn">
                    Có <a href="{{ route('admin.orders.index', ['status' => 'pending']) }}">{{ $pending }} đơn chờ xử lý</a> đang đợi bạn xác nhận.
                </p>
            @else
                <p class="attn ok">Không còn đơn nào chờ xử lý.</p>
            @endif
        @endif

        <section class="panel">
            <nav class="tabs" aria-label="Lọc theo trạng thái đơn">
                @foreach($tabs as $value => $label)
                    <a href="{{ route('admin.orders.index', array_filter(['status' => $value, 'keyword' => $keyword], fn($v) => $v !== '' && $v !== null)) }}"
                       class="{{ (string)$current === (string)$value ? 'active' : '' }}">
                        {{ $label }}
                        @if($value === '' && $counts->isNotEmpty())
                            <span class="count tnum">{{ $counts->sum() }}</span>
                        @elseif($value !== '' && $counts->has($value))
                            <span class="count tnum">{{ $counts->get($value) }}</span>
                        @endif
                    </a>
                @endforeach
            </nav>

            @if($keyword !== '')
                <div class="filter-note">
                    Kết quả cho "<strong>{{ $keyword }}</strong>"
                    <a href="{{ route('admin.orders.index', array_filter(['status' => $current])) }}">Xóa từ khóa</a>
                </div>
            @endif

            <div class="table-responsive">
                <table class="grid">
                    <thead>
                        <tr>
                            <th>Mã đơn</th>
                            <th>Khách hàng</th>
                            <th>Thanh toán</th>
                            <th class="num">Tổng tiền</th>
                            <th>Trạng thái</th>
                            <th>Ngày đặt</th>
                            <th>Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($orders as $order)
                            <tr data-href="{{ route('admin.orders.show', $order->id) }}">
                                <td>
                                    <a class="code" href="{{ route('admin.orders.show', $order->id) }}">#{{ $order->order_code }}</a>
                                </td>
                                <td>
                                    <span class="who">{{ $order->recipient_name }}</span>
                                    <span class="sub tnum">{{ $order->recipient_phone }}</span>
                                </td>
                                <td>
                                    <span class="pay-method">{{ $order->payment_method }}</span>
                                    @if($order->payment_status === 'paid')
                                        <span class="pay-state paid">Đã thanh toán</span>
                                    @else
                                        <span class="pay-state unpaid">Chưa thanh toán</span>
                                    @endif
                                </td>
                                <td class="num money tnum">{{ number_format($order->total_amount, 0, ',', '.') }}đ</td>
                                <td>
                                    <span class="pill {{ $order->order_status }}">{{ $statusLabel[$order->order_status] ?? $order->order_status }}</span>
                                </td>
                                <td class="text-nowrap tnum">
                                    {{ \Carbon\Carbon::parse($order->created_at)->format('d/m/Y') }}
                                    <span class="sub">{{ \Carbon\Carbon::parse($order->created_at)->format('H:i') }}</span>
                                </td>
                                <td>
                                    <a class="btn-view" href="{{ route('admin.orders.show', $order->id) }}">Chi tiết</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7">
                                    <div class="empty">
                                        <i class="fa-regular fa-clipboard"></i>
                                        @if($keyword !== '' || $current !== '')
                                            <strong>Không có đơn hàng nào khớp bộ lọc</strong>
                                            <a href="{{ route('admin.orders.index') }}">Xem tất cả đơn hàng</a>
                                        @else
                                            <strong>Chưa có đơn hàng nào</strong>
                                            Đơn mới sẽ hiện ở đây khi khách đặt hàng.
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($orders->total() > 0)
                <nav class="pager" aria-label="Phân trang">
                    <span class="pager-info">Hiển thị {{ $orders->firstItem() }}-{{ $orders->lastItem() }} trên {{ number_format($orders->total()) }} đơn hàng</span>

                    @if($orders->hasPages())
                        <ul>
                            <li>
                                @if($orders->onFirstPage())
                                    <span class="disabled" aria-disabled="true">Trước</span>
                                @else
                                    <a href="{{ $orders->previousPageUrl() }}">Trước</a>
                                @endif
                            </li>

                            @if($from > 1)
                                <li><a href="{{ $orders->url(1) }}">1</a></li>
                                @if($from > 2)<li><span class="gap">…</span></li>@endif
                            @endif

                            @for($i = $from; $i <= $to; $i++)
                                <li>
                                    @if($i === $cur)
                                        <span class="current" aria-current="page">{{ $i }}</span>
                                    @else
                                        <a href="{{ $orders->url($i) }}">{{ $i }}</a>
                                    @endif
                                </li>
                            @endfor

                            @if($to < $last)
                                @if($to < $last - 1)<li><span class="gap">…</span></li>@endif
                                <li><a href="{{ $orders->url($last) }}">{{ $last }}</a></li>
                            @endif

                            <li>
                                @if($orders->hasMorePages())
                                    <a href="{{ $orders->nextPageUrl() }}">Sau</a>
                                @else
                                    <span class="disabled" aria-disabled="true">Sau</span>
                                @endif
                            </li>
                        </ul>
                    @endif
                </nav>
            @endif
        </section>
    </main>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Bấm vào cả dòng để mở chi tiết đơn (bỏ qua khi bấm vào link/nút bên trong)
    document.querySelectorAll('tr[data-href]').forEach(function (row) {
        row.addEventListener('click', function (e) {
            if (e.target.closest('a, button')) return;
            window.location = row.dataset.href;
        });
    });
</script>
</body>
</html>