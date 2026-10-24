<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Japan Shop') }}</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <script src="https://cdn.tailwindcss.com/3.4.17?plugins=forms"></script>
    <style>
        body { font-family: 'Inter', sans-serif; background: #f5f5f7; }
        /* Đổi màu nút/ô nhập của Breeze sang đỏ thương hiệu của web */
        .auth-card button[type=submit] { background: #e2231a !important; }
        .auth-card button[type=submit]:hover { background: #a71d2a !important; }
        .auth-card input:focus { border-color: #e2231a !important; box-shadow: 0 0 0 1px #e2231a !important; }
    </style>
</head>
<body class="antialiased text-gray-900">
    <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0">
        <a href="/" class="text-2xl font-bold" style="color:#e2231a">
            <i class="fa-solid fa-torii-gate mr-1"></i>JAPAN SHOP
        </a>

        <div class="auth-card w-full sm:max-w-md mt-6 px-6 py-6 bg-white border border-gray-200 shadow-sm sm:rounded-xl">
            {{ $slot }}
        </div>

        <a href="/" class="mt-6 text-sm text-gray-500 hover:text-gray-700">Về trang chủ</a>
    </div>
</body>
</html>