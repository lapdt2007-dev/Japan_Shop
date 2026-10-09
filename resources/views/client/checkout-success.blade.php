<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đặt hàng thành công #{{ $order->order_code }}</title>
    <!-- Bootstrap 5 CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome Icon -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { background-color: #f4f6f9; }
        .success-card { max-width: 680px; margin: 50px auto; border: none; border-radius: 16px; box-shadow: 0 10px 30px rgba(0,0,0,0.08); }
        .success-icon { font-size: 70px; color: #198754; }
        .qr-card { background: #f8f9fa; border: 2px dashed #0d6efd; border-radius: 12px; }
        .qr-img { max-width: 230px; border-radius: 10px; border: 1px solid #dee2e6; }
    </style>
</head>
<body>

<div class="container">
    <div class="card success-card">
        <div class="card-body p-4 p-md-5 text-center">
            
            <!-- Biểu tượng thành công -->
            <div class="mb-3">
                <i class="fas fa-check-circle success-icon"></i>
            </div>
            
            <h3 class="fw-bold text-success">Đặt Hàng Thành Công!</h3>
            <p class="text-muted">Cảm ơn bạn đã mua sắm. Mã đơn hàng của bạn là: <strong class="text-dark">{{ $order->order_code }}</strong></p>

            <!-- Khối thông tin đơn hàng -->
            <div class="text-start bg-light p-4 rounded-3 my-4 border">
                <h6 class="fw-bold border-bottom pb-2 mb-3 text-primary">
                    <i class="fas fa-file-invoice me-2"></i>Thông tin đơn hàng
                </h6>
                <div class="row g-2">
                    <div class="col-4 text-muted">Người nhận:</div>
                    <div class="col-8 fw-semibold">{{ $order->recipient_name }}</div>

                    <div class="col-4 text-muted">Số điện thoại:</div>
                    <div class="col-8 fw-semibold">{{ $order->recipient_phone }}</div>

                    <div class="col-4 text-muted">Địa chỉ nhận:</div>
                    <div class="col-8 fw-semibold">{{ $order->shipping_address }}</div>

                    @if($order->note)
                        <div class="col-4 text-muted">Ghi chú:</div>
                        <div class="col-8 fst-italic">{{ $order->note }}</div>
                    @endif

                    <div class="col-4 text-muted">Phương thức:</div>
                    <div class="col-8">
                        @if(strtoupper($order->payment_method) === 'BANK')
                            <span class="badge bg-info text-dark">Chuyển khoản ngân hàng</span>
                        @else
                            <span class="badge bg-secondary">Thanh toán khi nhận hàng (COD)</span>
                        @endif
                    </div>

                    <div class="col-4 text-muted">Tổng thanh toán:</div>
                    <div class="col-8 text-danger fw-bold fs-5">
                        {{ number_format($order->total_amount, 0, ',', '.') }} VNĐ
                    </div>
                </div>
            </div>

            <!-- Khối VietQR tự động nếu chọn chuyển khoản BANK -->
            @if(strtoupper($order->payment_method) === 'BANK')
                <div class="qr-card p-4 my-4 text-center">
                    <h5 class="fw-bold text-primary mb-2">
                        <i class="fas fa-qrcode me-2"></i>Quét mã VietQR để thanh toán
                    </h5>
                    <p class="small text-muted mb-3">Mã QR đã tự động điền **Số tiền** và **Nội dung chuyển khoản**</p>
                    
                    <!-- 
                        Cấu hình ngân hàng VietQR:
                        Thay MB thành ngân hàng (VD: VCB, ACB, TPB...)
                        Thay 0123456789 thành số tài khoản thật 
                    -->
                    <img src="https://img.vietqr.io/image/MB-676767676767-compact2.png?amount={{ $order
                        ->total_amount }}&addInfo={{ $order->order_code }}&accountName=WIBU" 
                         alt="Mã QR Thanh Toán" 
                         class="qr-img img-fluid mb-3 shadow-sm">

                    <div class="p-3 bg-white rounded border text-start small">
                        <div class="d-flex justify-content-between mb-1">
                            <span class="text-muted">Ngân hàng:</span>
                            <span class="fw-bold">MBBank</span>
                        </div>
                        <div class="d-flex justify-content-between mb-1">
                            <span class="text-muted">Số tài khoản:</span>
                            <span class="fw-bold text-primary">676767676767</span>
                        </div>
                        <div class="d-flex justify-content-between mb-1">
                            <span class="text-muted">Chủ tài khoản:</span>
                            <span class="fw-bold">WIBU</span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span class="text-muted">Nội dung CK:</span>
                            <span class="fw-bold text-danger">{{ $order->order_code }}</span>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Nút điều hướng -->
            <div class="d-flex justify-content-center gap-3 mt-4">
                <a href="{{ url('/') }}" class="btn btn-outline-primary px-4 py-2 rounded-pill">
                    <i class="fas fa-home me-2"></i>Về trang chủ
                </a>
            </div>

        </div>
    </div>
</div>

</body>
</html>