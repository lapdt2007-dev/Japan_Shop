<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản Lý Đơn Hàng - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-light">

<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold text-dark mb-0"><i class="fa-solid fa-boxes-packing text-primary me-2"></i>Quản Lý Đơn Hàng</h3>
        <a href="/" class="btn btn-outline-secondary btn-sm"><i class="fa-solid fa-house me-1"></i> Xem Website</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fa-solid fa-circle-check me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- BỘ LỌC VÀ TÌM KIẾM -->
    <div class="card border-0 shadow-sm rounded-3 p-3 mb-4">
        <form action="{{ route('admin.orders.index') }}" method="GET" class="row g-2">
            <div class="col-md-5">
                <input type="text" name="keyword" class="form-control" placeholder="Tìm theo Mã đơn, Tên người nhận, SĐT..." value="{{ request('keyword') }}">
            </div>
            <div class="col-md-4">
                <select name="status" class="form-select">
                    <option value="">-- Tất cả trạng thái đơn --</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Chờ xử lý (Pending)</option>
                    <option value="processing" {{ request('status') == 'processing' ? 'selected' : '' }}>Đang xử lý (Processing)</option>
                    <option value="shipping" {{ request('status') == 'shipping' ? 'selected' : '' }}>Đang giao (Shipping)</option>
                    <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Hoàn thành (Completed)</option>
                    <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Đã hủy (Cancelled)</option>
                </select>
            </div>
            <div class="col-md-3 d-flex gap-2">
                <button type="submit" class="btn btn-primary w-100 fw-semibold"><i class="fa-solid fa-filter me-1"></i> Lọc</button>
                <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-secondary"><i class="fa-solid fa-rotate-right"></i></a>
            </div>
        </form>
    </div>

    <!-- BẢNG DANH SÁCH ĐƠN HÀNG -->
    <div class="card border-0 shadow-sm rounded-3 overflow-hidden">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-dark">
                    <tr>
                        <th>Mã Đơn</th>
                        <th>Khách Hàng</th>
                        <th>SĐT</th>
                        <th>Phương Thức</th>
                        <th>Tổng Tiền</th>
                        <th>Thanh Toán</th>
                        <th>Trạng Thái</th>
                        <th>Ngày Đặt</th>
                        <th class="text-center">Thao Tác</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orders as $item)
                        <tr>
                            <td class="fw-bold text-primary">#{{ $item->order_code }}</td>
                            <td class="fw-semibold">{{ $item->recipient_name }}</td>
                            <td>{{ $item->recipient_phone }}</td>
                            <td><span class="badge bg-light text-dark border">{{ $item->payment_method }}</span></td>
                            <td class="fw-bold text-danger">{{ number_format($item->total_amount, 0, ',', '.') }}đ</td>
                            <td>
                                @if($item->payment_status === 'paid')
                                    <span class="badge bg-success"><i class="fa-solid fa-check me-1"></i>Đã thanh toán</span>
                                @else
                                    <span class="badge bg-warning text-dark"><i class="fa-solid fa-clock me-1"></i>Chưa thanh toán</span>
                                @endif
                            </td>
                            <td>
                                @switch($item->order_status)
                                    @case('pending')
                                        <span class="badge bg-secondary">Chờ xử lý</span>
                                        @break
                                    @case('processing')
                                        <span class="badge bg-info text-dark">Đang chuẩn bị</span>
                                        @break
                                    @case('shipping')
                                        <span class="badge bg-primary">Đang giao hàng</span>
                                        @break
                                    @case('completed')
                                        <span class="badge bg-success">Hoàn thành</span>
                                        @break
                                    @case('cancelled')
                                        <span class="badge bg-danger">Đã hủy</span>
                                        @break
                                @endswitch
                            </td>
                            <td class="small text-muted">{{ date('d/m/Y H:i', strtotime($item->created_at)) }}</td>
                            <td class="text-center">
                                <a href="{{ route('admin.orders.show', $item->id) }}" class="btn btn-sm btn-outline-primary fw-semibold">
                                    <i class="fa-solid fa-eye me-1"></i> Chi tiết
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center py-4 text-muted">Chưa có đơn hàng nào!</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($orders->hasPages())
            <div class="card-footer bg-white border-0 pt-3">
                {{ $orders->withQueryString()->links('pagination::bootstrap-5') }}
            </div>
        @endif
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>