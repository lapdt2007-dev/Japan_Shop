<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Giới Thiệu - Japan Shop</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

        <!-- CSS dùng chung cho sidebar + layout (tách riêng, không lặp code) -->
    <link rel="stylesheet" href="{{ asset('css/sidebar-layout.css') }}">

    <style>
.about-banner {
            background: #fff;
            border-radius: 16px;
        }

        .feature-icon {
            width: 64px; height: 64px;
            border-radius: 16px;
            display: flex; align-items: center; justify-content: center;
            background: #fff5f4;
            font-size: 1.6rem;
            color: var(--brand);
            margin: 0 auto 12px;
        }

        .feature-box { transition: transform 0.2s ease; }
        .feature-box:hover { transform: translateY(-4px); }
    </style>
</head>
<body class="bg-light">

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

        <div class="container my-5">

            <!-- ===== BANNER GIỚI THIỆU ===== -->
            <div class="about-banner p-4 p-md-5 mb-5">
                <h3 class="fw-bold mb-4">Những Thông Tin Cho Người Dùng Mới</h3>
                <div class="row align-items-center g-4">
                    <div class="col-md-7">
                        <h5 class="fw-bold mb-3">Japan Shop Là Gì?</h5>
                        <p class="text-secondary">
                            Japan Shop là website chuyên cung cấp các mặt hàng nội địa Nhật Bản chính hãng: đồ gia dụng, bánh kẹo, mỹ phẩm, truyện tranh anime, đồ chơi và quà lưu niệm. Chúng tôi cam kết mang đến sản phẩm chất lượng với mức giá hợp lý nhất, kèm dịch vụ giao hàng nhanh chóng trên toàn quốc.
                        </p>
                        <a href="{{ route('products.index') }}" class="btn btn-danger fw-bold px-4 mt-2">
                            <i class="fa-solid fa-bag-shopping me-1"></i> Mua Hàng
                        </a>
                    </div>
                    <div class="col-md-5 text-center">
                        <img src="https://i.pinimg.com/736x/87/d5/b3/87d5b33980c2ae8037c95f44cb91e514.jpg" alt="Japan Shop" class="img-fluid rounded-4 shadow-sm" style="max-height: 320px; object-fit: cover; width: 100%;" onerror="this.onerror=null;this.src='https://via.placeholder.com/700x420?text=Japan+Shop'">
                    </div>
                </div>
            </div>

            <!-- ===== TẠI SAO CHỌN SHOP ===== -->
            <h4 class="fw-bold mb-2">Tại Sao Nên Chọn Japan Shop?</h4>
            <p class="text-muted mb-5">Với nhiều năm kinh nghiệm trong lĩnh vực phân phối hàng Nhật nội địa, chúng tôi không ngừng nâng cao chất lượng dịch vụ để mang đến trải nghiệm mua sắm hài lòng nhất cho khách hàng.</p>

            <div class="row g-4 text-center mb-4">
                <div class="col-md-4 feature-box">
                    <div class="feature-icon"><i class="fa-solid fa-tags"></i></div>
                    <p class="fw-semibold mb-0">Giá tốt nhất thị trường</p>
                </div>
                <div class="col-md-4 feature-box">
                    <div class="feature-icon"><i class="fa-solid fa-truck-fast"></i></div>
                    <p class="fw-semibold mb-0">Giao hàng siêu tốc toàn quốc</p>
                </div>
                <div class="col-md-4 feature-box">
                    <div class="feature-icon"><i class="fa-solid fa-shield-halved"></i></div>
                    <p class="fw-semibold mb-0">Cam kết hàng chính hãng</p>
                </div>
            </div>

            <div class="row g-4 text-center mb-5">
                <div class="col-md-4 feature-box">
                    <div class="feature-icon"><i class="fa-solid fa-cart-shopping"></i></div>
                    <p class="fw-semibold mb-0">Mua sắm trực tuyến dễ dàng</p>
                </div>
                <div class="col-md-4 feature-box">
                    <div class="feature-icon"><i class="fa-solid fa-headset"></i></div>
                    <p class="fw-semibold mb-0">Chăm sóc khách hàng 24/7</p>
                </div>
                <div class="col-md-4 feature-box">
                    <div class="feature-icon"><i class="fa-solid fa-globe"></i></div>
                    <p class="fw-semibold mb-0">Giao diện thuần Việt, dễ dùng</p>
                </div>
            </div>

            <!-- ===== QUY TRÌNH MUA SẮM ===== -->
            <div class="card border-0 shadow-sm p-4 p-md-5 mb-5">
                <h4 class="fw-bold mb-4">Quy Trình Mua Sắm Tại Web</h4>

                <div class="row g-4">
                    @php
                        $steps = [
                            ['bg' => '#fff3cd', 'icon' => 'fa-magnifying-glass', 'color' => '#0d6efd', 'title' => 'Tìm Kiếm Sản Phẩm', 'desc' => 'Bước 1: Tìm kiếm hoặc duyệt sản phẩm bạn muốn mua trong mục Hàng hóa.', 'img' => 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcScdMmuhstag8GgJB0mJzUCyz1XVe6biUPYhiCyAQ5dgZpmcnIhh3XFJnE&s=10'],
                            ['bg' => '#fde2ea', 'icon' => 'fa-cart-plus', 'color' => '#6f42c1', 'title' => 'Thêm Vào Giỏ', 'desc' => 'Bước 2: Chọn số lượng và thêm sản phẩm ưng ý vào giỏ hàng.', 'img' => 'https://ecoship.vn/static/step/step-image2.png'],
                            ['bg' => '#cfe8fb', 'icon' => 'fa-credit-card', 'color' => '#0d6efd', 'title' => 'Xác Nhận Đơn Hàng', 'desc' => 'Bước 3: Kiểm tra giỏ hàng, điền thông tin người nhận và địa chỉ giao hàng.', 'img' => 'https://acb.com.vn/acbwebsite/media/cac-dieu-kien-thanh-toan-quoc-te-se-la-nen-tang-de-cac-doanh-nghiep-chon-lua-phuong-thuc-thanh-toan-khi-giao-dich-quoc-te'],
                            ['bg' => '#d7f0dd', 'icon' => 'fa-file-invoice', 'color' => '#198754', 'title' => 'Chọn Thanh Toán', 'desc' => 'Bước 4: Lựa chọn phương thức thanh toán (COD, chuyển khoản, ví điện tử).', 'img' => 'https://ecoship.vn/static/step/step-image3.png'],
                            ['bg' => '#cdeef0', 'icon' => 'fa-truck-fast', 'color' => '#dc3545', 'title' => 'Thanh Toán', 'desc' => 'Bước 5: Hoàn tất thanh toán, đơn hàng được xác nhận và chuyển đi xử lý.', 'img' => 'https://ecoship.vn/static/step/step-image5.png'],
                            ['bg' => '#ffe4d6', 'icon' => 'fa-box', 'color' => '#fd7e14', 'title' => 'Nhận Hàng', 'desc' => 'Bước 6: Đóng gói cẩn thận và giao hàng tận tay khách trên toàn quốc.', 'img' => 'https://cdn.eva.vn/upload/4-2024/images/thuynt/1729498104-676-thumbnail-width753height564-auto-crop-watermark.jpg'],
                        ];
                    @endphp

                    @foreach($steps as $step)
                        <div class="col-md-4">
                            <div class="d-flex align-items-start gap-3">
                                <!-- Khối ảnh lớn bên trái -->
                                <div class="flex-shrink-0" style="width:205px; height:205px; border-radius:14px; overflow:hidden; background: {{ $step['bg'] }};">
                                    <img src="{{ $step['img'] }}" alt="{{ $step['title'] }}" style="width:100%; height:100%; object-fit:cover;" onerror="this.onerror=null;this.src='https://via.placeholder.com/300x300?text=Japan+Shop'">
                                </div>
                                <!-- Icon vừa + tiêu đề + mô tả xếp dọc bên phải -->
                                <div>
                                    <div class="d-flex align-items-center justify-content-center mb-2" style="width:38px; height:38px; border-radius:10px; background:#fff; box-shadow: 0 2px 8px rgba(0,0,0,0.08);">
                                        <i class="fa-solid {{ $step['icon'] }}" style="font-size: 1.15rem; color: {{ $step['color'] }};"></i>
                                    </div>
                                    <h6 class="fw-bold mb-1">{{ $step['title'] }}</h6>
                                    <p class="text-muted small mb-0">{{ $step['desc'] }}</p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>