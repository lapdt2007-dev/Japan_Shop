<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Phản hồi - Japan Shop</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- CSS dùng chung cho sidebar + layout (tách riêng, không lặp code) -->
    <link rel="stylesheet" href="{{ asset('css/sidebar-layout.css') }}">

    <style>
        * {
            box-sizing: border-box;
        }
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #f5f5f7;
            color: #222;
        }
        .top-header {
            background: #e52d45;
            min-height: 54px;
            border-bottom: 1px solid #d51f36;
        }
        .brand-title {
            color: #fff !important;
            font-size: 1.2rem;
            font-weight: 700;
            text-decoration: none;
        }
        .home-return-btn {
            color: #fff;
            border: 1px solid rgba(255, 255, 255, .9);
            border-radius: 5px;
            padding: 5px 11px;
            font-size: .85rem;
            text-decoration: none;
        }
        .home-return-btn:hover {
            background: #fff;
            color: var(--brand);
        }
        .page-title {
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 8px;
        }
        .page-description {
            color: #777;
            max-width: 800px;
            line-height: 1.7;
        }
        .feedback-card {
            background: #fff;
            border: 1px solid #e7e7e7;
            border-radius: 12px;
            padding: 25px;
            height: 100%;
        }
        .card-title {
            font-size: 1.15rem;
            font-weight: 700;
            margin-bottom: 20px;
        }

        .contact-item {
            display: flex;
            gap: 14px;
            padding: 15px 0;
            border-bottom: 1px solid #eee;
        }

        .contact-item:last-child {
            border-bottom: 0;
        }

        .contact-icon {
            width: 42px;
            height: 42px;
            min-width: 42px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 9px;
            background: #fff0f2;
            color: var(--brand);
        }

        .contact-label {
            color: #999;
            font-size: .75rem;
            margin-bottom: 3px;
        }

        .contact-value {
            font-size: .9rem;
            font-weight: 600;
        }

        .form-label {
            font-size: .85rem;
            font-weight: 600;
        }

        .form-control,
        .form-select {
            border-radius: 8px;
            border: 1px solid #ddd;
            min-height: 45px;
            font-size: .9rem;
        }

        textarea.form-control {
            min-height: 130px;
            resize: vertical;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: var(--brand);
            box-shadow: 0 0 0 .15rem rgba(229, 45, 69, .12);
        }

        .rating {
            display: flex;
            flex-direction: row-reverse;
            justify-content: flex-end;
            gap: 5px;
        }

        .rating input {
            display: none;
        }

        .rating label {
            color: #d5d5d5;
            font-size: 1.6rem;
            cursor: pointer;
        }

        .rating label:hover,
        .rating label:hover~label,
        .rating input:checked~label {
            color: #ffb000;
        }

        .btn-feedback {
            background: var(--brand);
            border: none;
            color: #fff;
            padding: 11px 24px;
            border-radius: 8px;
            font-weight: 700;
        }

        .btn-feedback:hover {
            background: var(--brand-dark);
            color: #fff;
        }

        .quick-box {
            background: #fff;
            border: 1px solid #e7e7e7;
            border-radius: 10px;
            padding: 20px;
            height: 100%;
        }

        .quick-icon {
            width: 45px;
            height: 45px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            background: #fff0f2;
            color: var(--brand);
            margin-bottom: 12px;
        }

        .quick-box h6 {
            font-weight: 700;
        }

        .quick-box p {
            color: #777;
            font-size: .82rem;
            line-height: 1.6;
            margin-bottom: 0;
        }

        @media(max-width: 991px) {
            .page-title {
                font-size: 1.6rem;
            }
        }
    </style>
</head>

<body>

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

    <div class="main-wrapper">

        <header class="top-header">
            <div class="container-fluid px-3 px-lg-4">
                <div class="d-flex align-items-center justify-content-between" style="min-height:54px;">

                    <a href="{{ route('home') }}" class="brand-title">
                        JP JAPAN SHOP
                    </a>

                    <a href="{{ route('home') }}" class="home-return-btn">
                        <i class="fa-solid fa-arrow-left me-2"></i>
                        Về trang chủ
                    </a>

                </div>
            </div>
        </header>

        <main class="container-fluid px-4 px-lg-5 py-5">

            <div class="mb-4">
                <h1 class="page-title">
                    <i class="fa-solid fa-comments me-2" style="color:#e52d45;font-size:1.7rem;"></i>
                    Phản hồi & Liên hệ
                </h1>

                <p class="page-description">
                    Hãy chia sẻ cảm nhận của bạn về JAPAN SHOP hoặc liên hệ
                    với chúng tôi nếu bạn cần hỗ trợ. Ý kiến của bạn giúp
                    chúng tôi cải thiện chất lượng dịch vụ.
                </p>
            </div>

            @if (session('success'))
                <div class="alert alert-success">
                    <i class="fa-solid fa-circle-check me-2"></i>
                    {{ session('success') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger">
                    <strong>Vui lòng kiểm tra lại thông tin:</strong>
                    <ul class="mb-0 mt-2">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="row g-4">

                <div class="col-lg-4">
                    <div class="feedback-card">

                        <div class="card-title">
                            <i class="fa-solid fa-headset me-2" style="color:#e52d45;"></i>
                            Liên hệ với chúng tôi
                        </div>

                        <p class="text-muted small">
                            Đội ngũ JAPAN SHOP luôn sẵn sàng hỗ trợ
                            bạn trong quá trình mua sắm.
                        </p>

                        <div class="contact-item">
                            <div class="contact-icon">
                                <i class="fa-solid fa-location-dot"></i>
                            </div>
                            <div>
                                <div class="contact-label">Địa chỉ</div>
                                <div class="contact-value">
                                    TP. Hồ Chí Minh, Việt Nam
                                </div>
                            </div>
                        </div>

                        <div class="contact-item">
                            <div class="contact-icon">
                                <i class="fa-solid fa-phone"></i>
                            </div>
                            <div>
                                <div class="contact-label">Hotline</div>
                                <div class="contact-value">
                                    0123 456 789
                                </div>
                            </div>
                        </div>

                        <div class="contact-item">
                            <div class="contact-icon">
                                <i class="fa-solid fa-envelope"></i>
                            </div>
                            <div>
                                <div class="contact-label">Email</div>
                                <div class="contact-value">
                                    support@japanshop.vn
                                </div>
                            </div>
                        </div>

                        <div class="contact-item">
                            <div class="contact-icon">
                                <i class="fa-solid fa-clock"></i>
                            </div>
                            <div>
                                <div class="contact-label">Thời gian hỗ trợ</div>
                                <div class="contact-value">
                                    08:00 - 22:00 hàng ngày
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

                <div class="col-lg-8">
                    <div class="feedback-card">

                        <div class="card-title">
                            <i class="fa-solid fa-pen-to-square me-2" style="color:#e52d45;"></i>
                            Gửi phản hồi cho chúng tôi
                        </div>

                        <form action="{{ route('feedback.store') }}" method="POST">
                            @csrf

                            <div class="row g-3">

                                <div class="col-md-6">
                                    <label class="form-label">
                                        Họ và tên <span class="text-danger">*</span>
                                    </label>

                                    <input type="text" name="name" class="form-control"
                                        value="{{ old('name', auth()->user()->name ?? '') }}"
                                        placeholder="Nhập họ và tên" required>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">
                                        Email <span class="text-danger">*</span>
                                    </label>

                                    <input type="email" name="email" class="form-control"
                                        value="{{ old('email', auth()->user()->email ?? '') }}"
                                        placeholder="example@gmail.com" required>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Chủ đề</label>

                                    <select name="subject" class="form-select">
                                        <option value="Góp ý dịch vụ">Góp ý dịch vụ</option>
                                        <option value="Sản phẩm">Sản phẩm</option>
                                        <option value="Đơn hàng">Đơn hàng</option>
                                        <option value="Giao hàng">Giao hàng</option>
                                        <option value="Khác">Khác</option>
                                    </select>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Mức độ hài lòng</label>

                                    <div class="rating">

                                        <input type="radio" id="star5" name="rating" value="5" checked>
                                        <label for="star5">★</label>

                                        <input type="radio" id="star4" name="rating" value="4">
                                        <label for="star4">★</label>

                                        <input type="radio" id="star3" name="rating" value="3">
                                        <label for="star3">★</label>

                                        <input type="radio" id="star2" name="rating" value="2">
                                        <label for="star2">★</label>

                                        <input type="radio" id="star1" name="rating" value="1">
                                        <label for="star1">★</label>

                                    </div>
                                </div>

                                <div class="col-12">
                                    <label class="form-label">
                                        Nội dung phản hồi
                                        <span class="text-danger">*</span>
                                    </label>

                                    <textarea name="message" class="form-control" placeholder="Hãy chia sẻ ý kiến của bạn..." required>{{ old('message') }}</textarea>
                                </div>

                                <div class="col-12 text-end">
                                    <button type="submit" class="btn btn-feedback">
                                        <i class="fa-solid fa-paper-plane me-2"></i>
                                        Gửi phản hồi
                                    </button>
                                </div>

                            </div>
                        </form>

                    </div>
                </div>

            </div>

            <div class="row g-3 mt-4">

                <div class="col-md-4">
                    <div class="quick-box">
                        <div class="quick-icon">
                            <i class="fa-solid fa-box"></i>
                        </div>
                        <h6>Hỗ trợ đơn hàng</h6>
                        <p>
                            Kiểm tra đơn hàng, tình trạng giao hàng
                            hoặc các vấn đề liên quan đến sản phẩm.
                        </p>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="quick-box">
                        <div class="quick-icon">
                            <i class="fa-solid fa-star"></i>
                        </div>
                        <h6>Đánh giá dịch vụ</h6>
                        <p>
                            Những đánh giá của bạn giúp JAPAN SHOP
                            cải thiện chất lượng phục vụ.
                        </p>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="quick-box">
                        <div class="quick-icon">
                            <i class="fa-solid fa-comments"></i>
                        </div>
                        <h6>Hỗ trợ nhanh</h6>
                        <p>
                            Đội ngũ chăm sóc khách hàng luôn sẵn sàng
                            giải đáp thắc mắc của bạn.
                        </p>
                    </div>
                </div>

            </div>

        </main>

        <footer id="footer-info" class="bg-white border-top mt-4 pt-5 pb-3">
             <div class="container-fluid px-3 px-lg-4">
                <div class="row g-4 mb-4">
                    <div class="col-lg-4 col-md-6">
                        <h5 class="fw-bold text-danger mb-3"><i class="fa-solid fa-torii-gate me-2"></i>JAPAN SHOP</h5>
                        <p class="text-muted small">Chuyên cung cấp các mặt hàng nội địa Nhật Bản chất lượng cao: Đồ gia dụng, Bánh kẹo, Mỹ phẩm, Truyện tranh Anime và Quà lưu niệm.</p>
                    </div>
                    <div class="col-lg-2 col-md-6">
                        <h6 class="fw-bold mb-3">Danh Mục</h6>
                        <ul class="list-unstyled small text-muted">
                            @foreach($categories->take(4) as $category)
                                <li class="mb-2"><a href="{{ route('home', ['category' => $category->slug]) }}" class="text-decoration-none text-muted">{{ $category->name }}</a></li>
                            @endforeach
                        </ul>
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <h6 class="fw-bold mb-3">Chính Sách</h6>
                        <ul class="list-unstyled small text-muted">
                            <li class="mb-2"><a href="#" class="text-decoration-none text-muted">Chính sách vận chuyển</a></li>
                            <li class="mb-2"><a href="#" class="text-decoration-none text-muted">Chính sách đổi trả 1-1</a></li>
                            <li class="mb-2"><a href="#" class="text-decoration-none text-muted">Cam kết bảo mật</a></li>
                        </ul>
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <h6 class="fw-bold mb-3">Liên Hệ</h6>
                        <p class="text-muted small mb-2"><i class="fa-solid fa-location-dot text-danger me-2"></i> TP. Hồ Chí Minh, Việt Nam</p>
                        <p class="text-muted small mb-2"><i class="fa-solid fa-phone text-danger me-2"></i> 0123 456 789</p>
                        <p class="text-muted small"><i class="fa-solid fa-envelope text-danger me-2"></i> support@japanshop.vn</p>
                    </div>
                </div>
                <hr class="my-4 text-muted">
                <div class="text-center text-muted small">
                    © {{ date('Y') }} Đinh Thành Lập, Trần Tấn Đạt, Lê Nguyễn An Trường
                </div>
            </div>
        </footer>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>