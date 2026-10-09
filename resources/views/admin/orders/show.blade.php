<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chi Tiết Đơn Hàng #{{ $order->order_code }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-light">

<div class="container py-4">
    <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-secondary btn-sm mb-3">
        <i class="fa-solid fa-arrow-left me-1"></i> Quay lại danh sách
    </a>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fa-solid fa-circle-check me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row g-4">
        <!-- CỘT TRÁI: THÔNG TIN CHI TIẾT SẢN PHẨM -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm rounded-3 p-4 mb-4">
                <h5 class="fw-bold border-bottom pb-3 mb-3">
                    Mã Đơn Hàng: <span class="text-primary">#{{ $order->order_code }}</span>
                </h5>

                <div class="table-responsive">
                    <table class="table align-middle">
                        <thead>
                            <tr>
                                <th>Sản phẩm</th>
                                <th>Đơn giá</th>
                                <th class="text-center">Số lượng</th>
                                <th class="text-end">Thành tiền</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($orderItems as $item)
                                <tr>
                                    <td class="fw-semibold">{{ $item->product_name }}</td>
                                    <td>{{ number_format($item->price, 0, ',', '.') }}đ</td>
                                    <td class="text-center">{{ $item->quantity }}</td>
                                    <td class="text-end fw-bold text-danger">
                                        {{ number_format($item->price * $item->quantity, 0, ',', '.') }}đ
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="border-top pt-3 text-end">
                    <h5 class="fw-bold">Tổng thanh toán: <span class="text-danger">{{ number_format($order->total_amount, 0, ',', '.') }} VNĐ</span></h5>
                </div>
            </div>

            <div class="card border-0 shadow-sm rounded-3 p-4">
                <h6 class="fw-bold text-secondary mb-3"><i class="fa-solid fa-truck-fast me-2"></i>Thông Tin Nhận Hàng</h6>
                <div class="row g-2">
                    <div class="col-md-4 text-muted">Người nhận:</div>
                    <div class="col-md-8 fw-semibold">{{ $order->recipient_name }}</div>

                    <div class="col-md-4 text-muted">Số điện thoại:</div>
                    <div class="col-md-8 fw-semibold">{{ $order->recipient_phone }}</div>

                    <div class="col-md-4 text-muted">Địa chỉ:</div>
                    <div class="col-md-8">{{ $order->shipping_address }}</div>

                    <div class="col-md-4 text-muted">Ghi chú từ khách:</div>
                    <div class="col-md-8 fst-italic text-secondary">{{ $order->note ?? 'Không có' }}</div>
                </div>
            </div>
        </div>

        <!-- CỘT PHẢI: FORM CẬP NHẬT TRẠNG THÁI -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm rounded-3 p-4 sticky-top" style="top: 20px;">
                <h5 class="fw-bold border-bottom pb-3 mb-3"><i class="fa-solid fa-pen-to-square me-2"></i>Cập Nhật Trạng Thái</h5>

                <form action="{{ route('admin.orders.updateStatus', $order->id) }}" method="POST">
                    @csrf
                    @method('PATCH')

                    <!-- Cập nhật Trạng thái Đơn hàng -->
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Trạng thái đơn hàng</label>
                        <select name="order_status" class="form-select">
                            <option value="pending" {{ $order->order_status == 'pending' ? 'selected' : '' }}>Chờ xử lý (Pending)</option>
                            <option value="processing" {{ $order->order_status == 'processing' ? 'selected' : '' }}>Đang xử lý (Processing)</option>
                            <option value="shipping" {{ $order->order_status == 'shipping' ? 'selected' : '' }}>Đang giao hàng (Shipping)</option>
                            <option value="completed" {{ $order->order_status == 'completed' ? 'selected' : '' }}>Hoàn thành (Completed)</option>
                            <option value="cancelled" {{ $order->order_status == 'cancelled' ? 'selected' : '' }}>Hủy đơn hàng (Cancelled)</option>
                        </select>
                    </div>

                    <!-- Cập nhật Trạng thái Thanh toán -->
                    <div class="mb-4">
                        <label class="form-label fw-semibold">Trạng thái thanh toán</label>
                        <select name="payment_status" class="form-select">
                            <option value="unpaid" {{ $order->payment_status == 'unpaid' ? 'selected' : '' }}>Chưa thanh toán (Unpaid)</option>
                            <option value="paid" {{ $order->payment_status == 'paid' ? 'selected' : '' }}>Đã thanh toán (Paid)</option>
                        </select>
                    </div>

                    <button type="submit" class="btn btn-primary w-100 fw-bold shadow-sm">
                        <i class="fa-solid fa-floppy-disk me-2"></i> Lưu Cập Nhật
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>