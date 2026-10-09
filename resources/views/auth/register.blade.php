<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng Ký - Japan Shop</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-light d-flex align-items-center justify-content-center min-vh-100 py-4">

<div class="card border-0 shadow-sm rounded-3 p-4" style="max-width: 420px; width: 100%;">
    <div class="text-center mb-3">
        <a href="{{ route('home') }}" class="text-decoration-none">
            <h3 class="fw-bold text-danger">🇯🇵 JAPAN SHOP</h3>
        </a>
        <p class="text-muted small">Tạo tài khoản mới để mua sắm</p>
    </div>

    <form action="{{ route('register') }}" method="POST">
        @csrf
        
        <!-- Họ và tên -->
        <div class="mb-3">
            <label class="form-label fw-semibold">Họ và tên</label>
            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" placeholder="Nhập họ và tên...">
            @error('name') 
                <div class="invalid-feedback">{{ $message }}</div> 
            @enderror
        </div>

        <!-- Email -->
        <div class="mb-3">
            <label class="form-label fw-semibold">Email</label>
            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" placeholder="email@example.com">
            @error('email') 
                <div class="invalid-feedback">{{ $message }}</div> 
            @enderror
        </div>

        <!-- Số điện thoại -->
        <div class="mb-3">
            <label class="form-label fw-semibold">Số điện thoại</label>
            <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror" value="{{ old('phone') }}" placeholder="Nhập số điện thoại...">
            @error('phone') 
                <div class="invalid-feedback">{{ $message }}</div> 
            @enderror
        </div>
        

        <!-- Mật khẩu -->
        <div class="mb-3">
            <label class="form-label fw-semibold">Mật khẩu</label>
            <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" placeholder="Tối thiểu 6 ký tự">
            @error('password') 
                <div class="invalid-feedback">{{ $message }}</div> 
            @enderror
        </div>

        <!-- Xác nhận mật khẩu -->
        <div class="mb-3">
            <label class="form-label fw-semibold">Xác nhận mật khẩu</label>
            <input type="password" name="password_confirmation" class="form-control" placeholder="Nhập lại mật khẩu...">
        </div>

        <button type="submit" class="btn btn-danger w-100 fw-bold py-2 mb-3 shadow-sm">Đăng Ký</button>

        <p class="text-center mb-0 text-muted small">Đã có tài khoản? <a href="{{ route('login') }}" class="text-danger text-decoration-none fw-semibold">Đăng nhập ngay</a></p>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>