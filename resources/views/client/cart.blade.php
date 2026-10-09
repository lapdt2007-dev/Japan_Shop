<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Giỏ Hàng - Japan Shop</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-light">

<div class="container py-5">
    <a href="{{ route('products.index') }}" class="btn btn-outline-secondary btn-sm mb-4">
        <i class="fa-solid fa-arrow-left me-1"></i> Tiếp tục mua hàng
    </a>

    <h3 class="fw-bold mb-4"><i class="fa-solid fa-cart-shopping me-2"></i>Giỏ Hàng Của Bạn</h3>

    @if(session('cart') && count(session('cart')) > 0)
        <div class="card border-0 shadow-sm rounded-3 p-3 mb-4">
            <div class="table-responsive">
                <table class="table align-middle">
                    <thead>
                        <tr>
                            <th>Sản phẩm</th>
                            <th>Đơn giá</th>
                            <th style="width: 150px;">Số lượng</th>
                            <th>Thành tiền</th>
                            <th>Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $total = 0; @endphp
                        @foreach(session('cart') as $id => $details)
                            @php 
                                $subtotal = $details['price'] * $details['quantity']; 
                                $total += $subtotal;
                            @endphp
                            <tr>
                                <td class="fw-bold">{{ $details['name'] }}</td>
                                <td>{{ number_format($details['price'], 0, ',', '.') }}đ</td>
                                <td>
                                    <form action="{{ route('cart.update') }}" method="POST" class="d-flex gap-1">
                                        @csrf
                                        <input type="hidden" name="id" value="{{ $id }}">
                                        <input type="number" name="quantity" value="{{ $details['quantity'] }}" min="1" class="form-control form-control-sm text-center">
                                        <button type="submit" class="btn btn-sm btn-outline-primary"><i class="fa-solid fa-rotate"></i></button>
                                    </form>
                                </td>
                                <td class="fw-bold text-danger">{{ number_format($subtotal, 0, ',', '.') }}đ</td>
                                <td>
                                    <form action="{{ route('cart.remove', $id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-outline-danger"><i class="fa-solid fa-trash"></i></button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-between align-items-center border-top pt-3 mt-3">
                <h5 class="fw-bold mb-0">Tổng tiền: <span class="text-danger fs-4">{{ number_format($total, 0, ',', '.') }} VNĐ</span></h5>
                <a href="{{ route('checkout.index') }}" class="btn btn-success btn-lg fw-bold">
                    Tiến Hành Đặt Hàng <i class="fa-solid fa-arrow-right ms-1"></i>
                </a>
            </div>
        </div>
    @else
        <div class="card border-0 shadow-sm text-center py-5">
            <i class="fa-solid fa-cart-flatbed fs-1 text-muted mb-3"></i>
            <h5>Giỏ hàng của bạn đang trống!</h5>
            <a href="{{ route('products.index') }}" class="btn btn-primary mt-2">Mua sắm ngay</a>
        </div>
    @endif
</div>

</body>
</html>