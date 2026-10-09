<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $product->name }} - Japan Shop</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

        <!-- CSS dùng chung cho sidebar + layout (tách riêng, không lặp code) -->
    <link rel="stylesheet" href="{{ asset('css/sidebar-layout.css') }}">

    <style>
.star-rating i { cursor: pointer; transition: color 0.2s; }

        .main-product-img {
            width: 100%;
            height: 380px;
            object-fit: cover;
            border-radius: 10px;
            border: 1px solid #eee;
            cursor: zoom-in;
        }

        .thumb-img {
            width: 100%;
            height: 70px;
            object-fit: cover;
            border-radius: 8px;
            border: 2px solid transparent;
            cursor: pointer;
            transition: all 0.15s ease;
        }

        .thumb-img.active,
        .thumb-img:hover { border-color: var(--brand); }

        .qty-box {
            display: flex;
            align-items: center;
            border: 1px solid #ddd;
            border-radius: 8px;
            overflow: hidden;
            width: fit-content;
        }

        .qty-box button {
            width: 36px; height: 40px;
            border: none;
            background: #f5f5f5;
            font-weight: bold;
        }

        .qty-box input {
            width: 55px; height: 40px;
            border: none;
            text-align: center;
            border-left: 1px solid #ddd;
            border-right: 1px solid #ddd;
        }

        .seller-card { border-radius: 12px; }
        .seller-avatar {
            width: 56px; height: 56px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid #fff;
            box-shadow: 0 0 0 2px var(--brand);
        }
    </style>
</head>
<body class="bg-light">

    <!-- ============ SIDEBAR TRÁI ============ -->
    <aside class="side-nav d-flex flex-column">
        <a href="{{ route('home') }}">
            <i class="fa-solid fa-house"></i> Trang chủ
        </a>
        <a href="{{ route('products.index') }}" class="{{ request()->routeIs('products.index') ? 'active' : '' }}">
            <i class="fa-solid fa-bag-shopping"></i> Mua
        </a>
        <a href="{{ route('orders.index') }}" class="{{ request()->routeIs('orders.index') ? 'active' : '' }}">
            <i class="fa-solid fa-box-open"></i> Hàng hóa
        </a>
        <a href="{{ route('about.index') }}">
            <i class="fa-solid fa-circle-info"></i> Giới thiệu
        </a>
        <a href="{{ route('home') }}#footer-info">
            <i class="fa-solid fa-earth-asia"></i> Quốc tế
        </a>
        <a href="{{ route('home') }}#footer-info">
            <i class="fa-solid fa-comment-dots"></i> Phản hồi
        </a>
    </aside>

    <!-- ============ NỘI DUNG CHÍNH ============ -->
    <div class="main-wrapper">

        <!-- Header Navigation -->
        <nav class="navbar navbar-expand-lg navbar-dark bg-danger sticky-top">
            <div class="container-fluid px-4">
                <a class="navbar-brand fw-bold" href="{{ route('home') }}">🇯🇵 JAPAN SHOP</a>

                <form action="{{ route('products.index') }}" method="GET" class="flex-grow-1 mx-4" style="max-width: 500px;">
                    <div class="input-group">
                        <input type="text" name="search" class="form-control" placeholder="Tìm kiếm sản phẩm...">
                        <button class="btn btn-light" type="submit"><i class="fa-solid fa-magnifying-glass"></i></button>
                    </div>
                </form>

                <div class="d-flex align-items-center gap-2">
                    @php $cartCount = array_sum(array_column(session('cart', []), 'quantity')); @endphp
                    <a href="{{ route('cart.index') }}" class="btn btn-outline-light position-relative btn-sm me-2">
                        <i class="fa-solid fa-cart-shopping me-1"></i> Giỏ hàng
                        @if($cartCount > 0)
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-warning text-dark">{{ $cartCount }}</span>
                        @endif
                    </a>
                    <a href="{{ route('home') }}" class="btn btn-outline-light btn-sm">
                        <i class="fa-solid fa-arrow-left me-1"></i> Trang chủ
                    </a>
                </div>
            </div>
        </nav>

        <!-- Thông báo -->
        @if(session('success'))
            <div class="container mt-4">
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fa-solid fa-circle-check me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            </div>
        @endif

        <div class="container my-4">

            @php
                $imagePath = $product->thumbnail ?? $product->image;
                $mainImage = filter_var($imagePath, FILTER_VALIDATE_URL) ? $imagePath : asset('storage/' . $imagePath);

                $gallery = collect([$mainImage]);
                foreach ($product->images ?? [] as $img) {
                    $path = filter_var($img->image_path, FILTER_VALIDATE_URL) ? $img->image_path : asset('storage/' . $img->image_path);
                    $gallery->push($path);
                }
                $gallery = $gallery->unique()->values();

                $reviewCount = $product->reviews->count();
                $avgRating = $reviewCount > 0 ? round($product->reviews->avg('rating'), 1) : 5;
                $trustPercent = $reviewCount > 0 ? round(($avgRating / 5) * 100) : 100;
            @endphp

            <!-- ===== KHỐI CHI TIẾT SẢN PHẨM ===== -->
            <div class="card border-0 shadow-sm p-4 mb-4">
                <div class="row g-4">

                    <!-- ===== ẢNH SẢN PHẨM ===== -->
                    <div class="col-md-5">
                        <img id="mainImage" src="{{ $mainImage }}" class="main-product-img mb-2" alt="{{ $product->name }}" onerror="this.src='https://via.placeholder.com/500x500?text=Japan+Shop'">

                        @if($gallery->count() > 1)
                            <div class="row g-2">
                                @foreach($gallery->take(4) as $index => $img)
                                    <div class="col-3">
                                        <img src="{{ $img }}" class="thumb-img {{ $index === 0 ? 'active' : '' }}" onclick="document.getElementById('mainImage').src=this.src; document.querySelectorAll('.thumb-img').forEach(t=>t.classList.remove('active')); this.classList.add('active');" onerror="this.src='https://via.placeholder.com/150?text=SP'">
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>

                    <!-- ===== THÔNG TIN SẢN PHẨM ===== -->
                    <div class="col-md-5">
                        <span class="badge bg-secondary mb-2">{{ $product->category->name ?? 'Nhật Bản' }}</span>
                        <h4 class="fw-bold text-dark mb-2">{{ $product->name }}</h4>

                        <div class="d-flex align-items-center gap-2 mb-3">
                            <span class="text-warning">
                                @for($i = 1; $i <= 5; $i++)
                                    @if($i <= round($avgRating)) <i class="fa-solid fa-star"></i> @else <i class="fa-regular fa-star"></i> @endif
                                @endfor
                            </span>
                            <span class="text-muted small">({{ number_format($reviewCount) }} đánh giá)</span>
                        </div>

                        <div class="mb-3">
                            @if($product->sale_price && $product->sale_price < $product->price)
                                <h3 class="text-danger fw-bold d-inline-block me-2">{{ number_format($product->sale_price, 0, ',', '.') }} đ</h3>
                                <span class="text-muted text-decoration-line-through">{{ number_format($product->price, 0, ',', '.') }} đ</span>
                            @else
                                <h3 class="text-danger fw-bold">{{ number_format($product->price, 0, ',', '.') }} đ</h3>
                            @endif
                        </div>

                        <table class="table table-borderless table-sm small mb-3">
                            <tr>
                                <td class="text-muted" style="width:180px;">Tình trạng sản phẩm</td>
                                <td class="fw-semibold">Mới</td>
                            </tr>
                            <tr>
                                <td class="text-muted">Tình trạng hàng</td>
                                <td class="fw-semibold">{{ $product->quantity > 0 ? 'Còn hàng (' . $product->quantity . ')' : 'Hết hàng' }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted">Cước vận chuyển nội địa</td>
                                <td class="fw-semibold text-success">Miễn phí</td>
                            </tr>
                        </table>

                        <!-- FORM THÊM GIỎ HÀNG -->
                        <form action="{{ route('cart.add', $product->id) }}" method="POST">
                            @csrf
                            <label class="form-label small text-muted mb-1">Số lượng</label>
                            <div class="qty-box mb-3">
                                <button type="button" onclick="changeQty(-1)">-</button>
                                <input type="number" name="quantity" id="qtyInput" value="1" min="1" max="{{ max(1, $product->quantity) }}">
                                <button type="button" onclick="changeQty(1)">+</button>
                            </div>

                            <textarea name="note" class="form-control mb-3" rows="2" placeholder="Ghi chú..."></textarea>

                            <p class="small text-muted mb-3">
                                <i class="fa-solid fa-truck me-1"></i> Vận chuyển: <span class="text-success fw-semibold">Miễn phí</span>
                            </p>

                            <div class="d-flex gap-2">
                                <button type="submit" name="action" value="add" class="btn btn-lg fw-semibold flex-fill text-white" style="background:#f0ad2e;">
                                    <i class="fa-solid fa-cart-plus me-1"></i> Thêm giỏ hàng
                                </button>
                                <button type="submit" name="buy_now" value="1" class="btn btn-danger btn-lg fw-semibold flex-fill">
                                    <i class="fa-solid fa-bolt me-1"></i> Mua ngay
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- ===== CARD ĐÁNH GIÁ TỔNG QUAN (thay cho seller card) ===== -->
                    <div class="col-md-2">
                        <div class="seller-card bg-light p-3 text-center h-100">
                            <img src="https://i.pinimg.com/236x/c9/cf/b2/c9cfb24beca5b65d41abdc2c678e6038.jpg" class="seller-avatar mb-2" alt="Japan Shop">
                            <h6 class="fw-bold mb-1">Pháp sư Tran Dat</h6>
                            <span class="badge bg-warning text-dark mb-3">{{ number_format($reviewCount) }} đánh giá ({{ $trustPercent }}% uy tín)</span>

                            <div class="d-grid gap-2">
                                <a href="{{ route('about.index') }}" class="btn btn-sm btn-outline-secondary">
                                    <i class="fa-regular fa-id-card me-1"></i> Chi tiết
                                </a>
                                <a href="{{ route('products.index') }}" class="btn btn-sm btn-outline-danger">
                                    <i class="fa-regular fa-heart me-1"></i> Yêu thích
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ===== MÔ TẢ SẢN PHẨM ===== -->
            <div class="card border-0 shadow-sm p-4 mb-4">
                <h5 class="fw-bold mb-3">Mô tả sản phẩm</h5>
                <p class="text-muted mb-0" style="white-space: pre-line;">{{ $product->description }}</p>
            </div>

            <!-- ===== PHẦN ĐÁNH GIÁ SẢN PHẨM ===== -->
            <div class="card border-0 shadow-sm p-4 mb-5">
                <h5 class="fw-bold mb-4">Đánh giá cho sản phẩm này ({{ number_format($reviewCount) }})</h5>

                @auth
                    <form action="{{ route('product.review', $product->id) }}" method="POST" class="mb-4">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Chất lượng sản phẩm:</label>
                            <div id="star-rating" class="star-rating fs-3 text-warning">
                                <i class="fa-solid fa-star" data-value="1"></i>
                                <i class="fa-solid fa-star" data-value="2"></i>
                                <i class="fa-solid fa-star" data-value="3"></i>
                                <i class="fa-solid fa-star" data-value="4"></i>
                                <i class="fa-solid fa-star" data-value="5"></i>
                            </div>
                            <input type="hidden" name="rating" id="rating-input" value="5">
                            <span id="rating-text" class="text-muted small ms-2 fw-bold">Tuyệt vời (5/5 sao)</span>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Nhận xét của bạn:</label>
                            <textarea name="comment" rows="3" class="form-control" placeholder="Sản phẩm dùng thế nào bro ơi, viết vài dòng chia sẻ nhé..." required></textarea>
                        </div>
                        <button type="submit" class="btn btn-danger px-4 fw-semibold">Gửi đánh giá</button>
                    </form>
                @else
                    <div class="alert alert-warning">
                        Vui lòng <a href="{{ route('login') }}" class="fw-bold text-danger">đăng nhập</a> để tham gia đánh giá sản phẩm này nhé.
                    </div>
                @endauth

                <hr>

                <div class="review-list">
                    @forelse($product->reviews()->latest()->get() as $review)
                        <div class="card bg-light border-0 mb-3 p-3">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <strong class="text-dark"><i class="fa-solid fa-circle-user me-1 text-secondary"></i> {{ $review->user->name ?? 'Người dùng ẩn danh' }}</strong>
                                <small class="text-muted">{{ $review->created_at->diffForHumans() }}</small>
                            </div>
                            <div class="text-warning small mb-2">
                                @for($i = 1; $i <= 5; $i++)
                                    @if($i <= $review->rating) <i class="fa-solid fa-star"></i> @else <i class="fa-regular fa-star text-muted"></i> @endif
                                @endfor
                            </div>
                            <p class="mb-0 text-dark">{{ $review->comment }}</p>
                        </div>
                    @empty
                        <p class="text-muted fst-italic">Chưa có đánh giá nào cho sản phẩm này. Hãy là người đầu tiên đánh giá!</p>
                    @endforelse
                </div>
            </div>

            <!-- Sản phẩm liên quan -->
            @if(isset($relatedProducts) && $relatedProducts->count() > 0)
                <h5 class="fw-bold mb-4">Sản Phẩm Liên Quan</h5>
                <div class="row row-cols-1 row-cols-sm-2 row-cols-md-4 g-4">
                    @foreach($relatedProducts as $item)
                        <div class="col">
                            <x-product-card :product="$item" />
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function changeQty(delta) {
            const input = document.getElementById('qtyInput');
            let val = parseInt(input.value) || 1;
            val = Math.max(1, Math.min(parseInt(input.max) || 999, val + delta));
            input.value = val;
        }

        document.addEventListener('DOMContentLoaded', function() {
            const stars = document.querySelectorAll('#star-rating i');
            const ratingInput = document.getElementById('rating-input');
            const ratingText = document.getElementById('rating-text');

            const texts = {
                1: 'Tệ (1/5 sao)',
                2: 'Không hài lòng (2/5 sao)',
                3: 'Bình thường (3/5 sao)',
                4: 'Hài lòng (4/5 sao)',
                5: 'Tuyệt vời (5/5 sao)'
            };

            function updateStars(rating) {
                stars.forEach(star => {
                    const val = parseInt(star.getAttribute('data-value'));
                    if (val <= rating) {
                        star.classList.remove('fa-regular');
                        star.classList.add('fa-solid');
                    } else {
                        star.classList.remove('fa-solid');
                        star.classList.add('fa-regular');
                    }
                });
            }

            if (stars.length) {
                stars.forEach(star => {
                    star.addEventListener('mouseover', function() {
                        const val = parseInt(this.getAttribute('data-value'));
                        updateStars(val);
                        ratingText.textContent = texts[val];
                    });

                    star.addEventListener('click', function() {
                        const val = parseInt(this.getAttribute('data-value'));
                        ratingInput.value = val;
                        updateStars(val);
                        ratingText.textContent = texts[val];
                    });
                });

                const starContainer = document.getElementById('star-rating');
                if (starContainer) {
                    starContainer.addEventListener('mouseleave', function() {
                        const currentVal = parseInt(ratingInput.value);
                        updateStars(currentVal);
                        ratingText.textContent = texts[currentVal];
                    });
                }
            }
        });
    </script>
</body>
</html>