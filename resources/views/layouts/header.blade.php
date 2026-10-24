<header class="top-header py-3 sticky-top">
            <div class="container-fluid px-3 px-lg-4">
                <div class="d-flex align-items-center gap-3 flex-wrap">

                    <a href="{{ route('home') }}" class="text-danger fw-bold fs-4 text-decoration-none flex-shrink-0">
                        <i class="fa-solid fa-torii-gate me-1"></i>JAPAN SHOP
                    </a>

                    <!-- Ô tìm kiếm -->
                    <form action="{{ route('home') }}" method="GET" class="flex-grow-1" style="max-width: 620px;">
                        <div class="input-group search-pill">
                            <input type="text" name="search" class="form-control ps-3" placeholder="Tìm kiếm sản phẩm, thương hiệu..." value="{{ request('search') }}">
                            <button class="btn btn-search" type="submit">
                                <i class="fa-solid fa-magnifying-glass me-1"></i> Tìm kiếm
                            </button>
                        </div>
                    </form>

                    <!-- Menu icon tiện ích (giống mẫu: Mua / Hàng hóa / Chat / Shop) -->
                    <div class="d-none d-xl-flex align-items-center gap-4">
                        <a href="{{ route('products.index') }}" class="header-icon-link">
                            <i class="fa-solid fa-bag-shopping"></i> Mua sắm
                        </a>
                        <div class="dropdown">
                            <button class="header-icon-link border-0 bg-transparent" type="button" data-bs-toggle="dropdown">
                                <i class="fa-solid fa-layer-group"></i> Danh mục
                            </button>
                            <ul class="dropdown-menu shadow-sm">
                                @foreach($categories as $category)
                                    <li><a class="dropdown-item" href="{{ route('home', ['category' => $category->slug]) }}">{{ $category->name }}</a></li>
                                @endforeach
                            </ul>
                        </div>
                        <a href="{{ route('feedback.index') }}" class="header-icon-link">
                            <i class="fa-solid fa-headset"></i> Hỗ trợ
                        </a>
                        <a href="{{ route('about.index') }}" class="header-icon-link">
                            <i class="fa-solid fa-shop"></i> Về chúng tôi
                        </a>
                    </div>

                    <!-- Icon tiện ích -->
                    <div class="d-flex align-items-center gap-4 ms-auto">
                        <a href="{{ Route::has('cart.index') ? route('cart.index') : '#' }}" class="header-icon-link position-relative">
                            <i class="fa-solid fa-cart-shopping"></i>
                            @php $cartCount = array_sum(array_column(session('cart', []), 'quantity')); @endphp
                            @if($cartCount > 0)
                                <span class="position-absolute translate-middle badge rounded-pill bg-danger" style="top:0; left:22px; font-size:0.6rem;">{{ $cartCount }}</span>
                            @endif
                            Giỏ hàng
                        </a>

                        @auth
                            <div class="dropdown">
                                <button class="header-icon-link border-0 bg-transparent" type="button" data-bs-toggle="dropdown">
                                    <i class="fa-solid fa-circle-user"></i>
                                    {{ Str::limit(auth()->user()->name, 10) }}
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end shadow-sm">
                                    @if(auth()->user()->role === 'admin')
                                        <li><a class="dropdown-item text-danger fw-bold" href="{{ route('admin.orders.index') }}"><i class="fa-solid fa-user-shield me-2"></i>Trang Quản Lý Admin</a></li>
                                        <li><a class="dropdown-item fw-semibold" href="{{ route('admin.products.index') }}"><i class="fa-solid fa-boxes-stacked me-2"></i>Quản Lý Sản Phẩm</a></li>
                                        <li><hr class="dropdown-divider"></li>
                                    @endif
                                    <li><a class="dropdown-item" href="{{ route('cart.index') }}"><i class="fa-solid fa-cart-shopping me-2"></i>Giỏ hàng của tôi</a></li>
                                    <li><a class="dropdown-item" href="{{ route('orders.index') }}"><i class="fa-solid fa-clipboard-list me-2"></i>Lịch sử mua hàng</a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li>
                                        <form action="{{ route('logout') }}" method="POST">
                                            @csrf
                                            <button type="submit" class="dropdown-item text-danger fw-semibold"><i class="fa-solid fa-right-from-bracket me-2"></i>Đăng xuất</button>
                                        </form>
                                    </li>
                                </ul>
                            </div>
                        @else
                            <a href="{{ route('login') }}" class="header-icon-link">
                                <i class="fa-solid fa-right-to-bracket"></i> Đăng nhập
                            </a>
                        @endauth
                    </div>
                </div>
            </div>
        </header>