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
    <link rel="stylesheet" href="{{ asset('css/feedback.css') }}">
</head>

<body>

    @include('layouts.sidebar')

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

        @include('layouts.footer')

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>
