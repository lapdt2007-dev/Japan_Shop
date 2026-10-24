@php
    $isHome = request()->routeIs('home') && !request('category') && !request('search');
@endphp
<aside class="side-nav d-flex flex-column">
    <a href="{{ route('home') }}" class="{{ $isHome ? 'active' : '' }}">
        <i class="fa-solid fa-house"></i> Trang chủ
    </a>
    <a href="{{ route('products.index') }}" class="{{ request()->routeIs('products.index', 'client.products.show') ? 'active' : '' }}">
        <i class="fa-solid fa-bag-shopping"></i> Hàng hóa
    </a>
    <a href="{{ route('orders.index') }}" class="{{ request()->routeIs('orders.index') ? 'active' : '' }}">
        <i class="fa-solid fa-box-open"></i> Mua hàng
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
