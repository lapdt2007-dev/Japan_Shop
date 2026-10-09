<div class="d-flex align-items-center gap-2">
    @auth
        <!-- DROPDOWN TÀI KHOẢN ĐÃ ĐĂNG NHẬP -->
        <div class="dropdown">
            <button class="btn btn-outline-primary dropdown-toggle fw-semibold" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="fa-solid fa-circle-user me-1"></i> {{ auth()->user()->name }}
            </button>
            <ul class="dropdown-menu dropdown-menu-end shadow-sm">
                <!-- Link dành riêng cho ADMIN -->
                @if(auth()->user()->role === 'admin')
                    <li>
                        <a class="dropdown-item text-danger fw-bold" href="{{ route('admin.orders.index') }}">
                            <i class="fa-solid fa-user-shield me-2"></i> Trang Quản Lý Admin
                        </a>
                    </li>
                    <li><a class="dropdown-item fw-semibold" href="{{ route('admin.products.index') }}">
                            <i class="fa-solid fa-boxes-stacked me-2"></i> Quản Lý Sản Phẩm
                        </a>
                    </li>
                    <li><hr class="dropdown-divider"></li>
                @endif

                <!-- Link dành cho Khách hàng -->
                <li>
                    <a class="dropdown-item" href="{{ route('cart.index') }}">
                        <i class="fa-solid fa-cart-shopping me-2"></i> Giỏ hàng của tôi
                    </a>
                </li>
                <li><hr class="dropdown-divider"></li>

                <!-- Nút Đăng xuất (Dùng POST form chuẩn Laravel) -->
                <li>
                    <form action="{{ route('logout') }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="dropdown-item text-danger fw-semibold">
                            <i class="fa-solid fa-right-from-bracket me-2"></i> Đăng xuất
                        </button>
                    </form>
                </li>
            </ul>
        </div>
    @else
        <!-- CHƯA ĐĂNG NHẬP -->
        <a href="{{ route('login') }}" class="btn btn-outline-primary fw-semibold">Đăng nhập</a>
        <a href="{{ route('register') }}" class="btn btn-primary fw-semibold">Đăng ký</a>
    @endauth
</div>