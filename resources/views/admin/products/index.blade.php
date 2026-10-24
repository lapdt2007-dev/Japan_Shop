<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý sản phẩm - Kênh người bán</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/admin-products.css') }}">
</head>
<body>

@php
    $tabs = [
        ''        => 'Tất cả',
        'visible' => 'Đang hiển thị',
        'hidden'  => 'Đã ẩn',
        'low'     => 'Sắp hết',
        'out'     => 'Hết hàng',
    ];
    $counts    = isset($productCounts) ? $productCounts : [];
    $current   = request('filter', '');
    $keyword   = request('keyword', '');
    $stockCap  = 50;   // từ 50 sản phẩm trở lên thì thanh đo đầy
    $lowLimit  = 5;    // từ 5 trở xuống tính là sắp hết
    $outCount  = $counts['out'] ?? null;
    $lowCount  = $counts['low'] ?? null;

    // Phân trang (giữ từ khóa và tab khi chuyển trang)
    $products->withQueryString();
    $cur  = $products->currentPage();
    $last = $products->lastPage();
    $from = max(1, $cur - 2);
    $to   = min($last, $cur + 2);
@endphp

<header class="topbar">
    <div class="topbar-inner">
        <a href="{{ route('admin.orders.index') }}" class="brand">Japan Shop</a>

        <form class="search-box" action="{{ route('admin.products.index') }}" method="GET" role="search">
            @if($current !== '')
                <input type="hidden" name="filter" value="{{ $current }}">
            @endif
            <input type="text" name="keyword" value="{{ $keyword }}" placeholder="Tìm theo tên sản phẩm" aria-label="Tìm sản phẩm">
            <button type="submit">Tìm kiếm</button>
        </form>

        <nav class="top-links">
            <a href="/"><i class="fa-solid fa-house"></i> Trang chủ</a>
        </nav>
    </div>
</header>

<div class="shell">
    <aside class="side">
        <h2>Kênh người bán</h2>
        <a href="{{ route('admin.orders.index') }}">Q.lý đơn hàng</a>
        <a href="{{ route('admin.products.index') }}" class="active">Q.lý sản phẩm</a>
        <a href="{{ route('admin.products.create') }}">Thêm sản phẩm</a>    
        <a href="{{ route('admin.feedback.index')}}">Phản hồi khách hàng</a>
    </aside>

    <main class="content">

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Đóng"></button>
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Đóng"></button>
            </div>
        @endif

        <div class="page-head">
            <div>
                <h1>Sản phẩm</h1>
                <div class="total tnum">{{ number_format($products->total()) }} sản phẩm{{ ($keyword !== '' || $current !== '') ? ' khớp bộ lọc' : ' trong kho' }}</div>
            </div>
            <a href="{{ route('admin.products.create') }}" class="btn-add"><i class="fa-solid fa-plus me-1"></i> Thêm sản phẩm mới</a>
        </div>

        {{-- Câu tóm tắt tình trạng kho (cần $productCounts từ controller) --}}
        @if($outCount !== null && $lowCount !== null)
            @if($outCount > 0 || $lowCount > 0)
                <p class="attn">
                    Có
                    @if($outCount > 0)
                        <a href="{{ route('admin.products.index', ['filter' => 'out']) }}">{{ $outCount }} sản phẩm hết hàng</a>@if($lowCount > 0) và @endif
                    @endif
                    @if($lowCount > 0)
                        <a class="amber" href="{{ route('admin.products.index', ['filter' => 'low']) }}">{{ $lowCount }} sản phẩm sắp hết</a>
                    @endif
                    cần nhập thêm.
                </p>
            @else
            @endif
        @endif

        <section class="panel">
            <nav class="tabs" aria-label="Lọc sản phẩm">
                @foreach($tabs as $value => $label)
                    <a href="{{ route('admin.products.index', array_filter(['filter' => $value, 'keyword' => $keyword], fn($v) => $v !== '' && $v !== null)) }}"
                       class="{{ (string)$current === (string)$value ? 'active' : '' }}">
                        {{ $label }}
                        @if(isset($counts[$value === '' ? 'all' : $value]))
                            <span class="count tnum">{{ $counts[$value === '' ? 'all' : $value] }}</span>
                        @endif
                    </a>
                @endforeach
            </nav>

            @if($keyword !== '')
                <div class="filter-note">
                    Kết quả cho "<strong>{{ $keyword }}</strong>"
                    <a href="{{ route('admin.products.index', array_filter(['filter' => $current])) }}">Xóa từ khóa</a>
                </div>
            @endif

            <div class="table-responsive">
                <table class="grid">
                    <thead>
                        <tr>
                            <th>Sản phẩm</th>
                            <th class="num">Giá bán</th>
                            <th class="num">Kho hàng</th>
                            <th>Đánh giá</th>
                            <th>Trạng thái</th>
                            <th>Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($products as $product)
                            @php
                                $qty = (int) $product->quantity;
                                $rowClass = $qty <= 0 ? 'is-out' : ($qty <= $lowLimit ? 'is-low' : '');
                                $fill = min(100, round($qty / $stockCap * 100));

                                // Ảnh có thể là link ngoài (seeder) hoặc file đã upload vào storage
                                $thumb = $product->thumbnail;
                                if (!$thumb) {
                                    $thumbUrl = null;
                                } elseif (filter_var($thumb, FILTER_VALIDATE_URL)) {
                                    $thumbUrl = $thumb;
                                } elseif (str_starts_with($thumb, '/storage/')) {
                                    $thumbUrl = asset(ltrim($thumb, '/'));
                                } elseif (str_starts_with($thumb, 'images/')) {
                                    $thumbUrl = asset($thumb);   // ảnh lưu sẵn trong public/images (chạy offline)
                                } elseif (str_starts_with($thumb, 'storage/')) {
                                    $thumbUrl = asset($thumb);
                                } else {
                                    $thumbUrl = asset('storage/' . ltrim($thumb, '/'));
                                }
                            @endphp
                            <tr class="{{ $rowClass }}">
                                <td>
                                    <div class="prod">
                                        @if($thumbUrl)
                                            <img class="thumb" src="{{ $thumbUrl }}" alt="" loading="lazy">
                                        @else
                                            <span class="thumb none" aria-label="Chưa có ảnh"><i class="fa-regular fa-image"></i></span>
                                        @endif
                                        <div>
                                            <div class="name">{{ $product->name }}</div>
                                            <div class="meta">{{ $product->category->name ?? 'Chưa phân loại' }}, mã #{{ $product->id }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="num tnum">
                                    @if($product->sale_price)
                                        <span class="price-now">{{ number_format($product->sale_price) }} đ</span>
                                        <span class="price-old">{{ number_format($product->price) }} đ</span>
                                    @else
                                        <span class="price-plain">{{ number_format($product->price) }} đ</span>
                                    @endif
                                </td>
                                <td class="num tnum">
                                    @if($qty <= 0)
                                        <div class="stock out"><span class="qty">Hết hàng</span></div>
                                    @else
                                        <div class="stock {{ $qty <= $lowLimit ? 'low' : '' }}">
                                            <span class="meter" aria-hidden="true"><span style="width: {{ $fill }}%"></span></span>
                                            <span class="qty">{{ $qty }}</span>
                                        </div>
                                        @if($qty <= $lowLimit)<span class="stock-note">Sắp hết</span>@endif
                                    @endif
                                </td>
                                <td class="tnum">
                                    @if(isset($product->reviews_count) && $product->reviews_count > 0)
                                        <span class="rating"><i class="fa-solid fa-star"></i>{{ number_format($product->reviews_avg_rating, 1) }}</span>
                                        <span class="rating-sub">{{ $product->reviews_count }} đánh giá</span>
                                    @else
                                        <span class="dim">Chưa có</span>
                                    @endif
                                </td>
                                <td>
                                    @if($product->status == 1)
                                        <span class="state on">Hiển thị</span>
                                    @else
                                        <span class="state off">Ẩn</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="acts">
                                        <a href="{{ route('admin.products.edit', $product->id) }}" class="btn-edit">Sửa</a>
                                        <form action="{{ route('admin.products.destroy', $product->id) }}" method="POST" class="js-delete" data-name="{{ $product->name }}">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn-del" title="Xóa sản phẩm" aria-label="Xóa {{ $product->name }}"><i class="fa-solid fa-trash-can"></i></button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6">
                                    <div class="empty">
                                        <i class="fa-regular fa-clipboard"></i>
                                        @if($keyword !== '' || $current !== '')
                                            <strong>Không có sản phẩm nào khớp bộ lọc</strong>
                                            <a href="{{ route('admin.products.index') }}">Xem tất cả sản phẩm</a>
                                        @else
                                            <strong>Kho đang trống</strong>
                                            <a href="{{ route('admin.products.create') }}">Thêm sản phẩm đầu tiên</a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($products->total() > 0)
                <nav class="pager" aria-label="Phân trang">
                    <span class="pager-info">Hiển thị {{ $products->firstItem() }}-{{ $products->lastItem() }} trên {{ number_format($products->total()) }} sản phẩm</span>

                    @if($products->hasPages())
                        <ul>
                            <li>
                                @if($products->onFirstPage())
                                    <span class="disabled" aria-disabled="true">Trước</span>
                                @else
                                    <a href="{{ $products->previousPageUrl() }}">Trước</a>
                                @endif
                            </li>

                            @if($from > 1)
                                <li><a href="{{ $products->url(1) }}">1</a></li>
                                @if($from > 2)<li><span class="gap">…</span></li>@endif
                            @endif

                            @for($i = $from; $i <= $to; $i++)
                                <li>
                                    @if($i === $cur)
                                        <span class="current" aria-current="page">{{ $i }}</span>
                                    @else
                                        <a href="{{ $products->url($i) }}">{{ $i }}</a>
                                    @endif
                                </li>
                            @endfor

                            @if($to < $last)
                                @if($to < $last - 1)<li><span class="gap">…</span></li>@endif
                                <li><a href="{{ $products->url($last) }}">{{ $last }}</a></li>
                            @endif

                            <li>
                                @if($products->hasMorePages())
                                    <a href="{{ $products->nextPageUrl() }}">Sau</a>
                                @else
                                    <span class="disabled" aria-disabled="true">Sau</span>
                                @endif
                            </li>
                        </ul>
                    @endif
                </nav>
            @endif
        </section>
    </main>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Ảnh không tải được thì hiện ô icon thay cho ảnh vỡ
    function thumbFallback(img) {
        var box = document.createElement('span');
        box.className = 'thumb none';
        box.innerHTML = '<i class="fa-regular fa-image"></i>';
        img.replaceWith(box);
    }
    document.querySelectorAll('img.thumb').forEach(function (img) {
        if (img.complete && img.naturalWidth === 0) { thumbFallback(img); return; }
        img.addEventListener('error', function () { thumbFallback(img); });
    });

    // Hỏi lại trước khi xóa sản phẩm
    document.querySelectorAll('form.js-delete').forEach(function (form) {
        form.addEventListener('submit', function (e) {
            var name = form.dataset.name || 'sản phẩm này';
            if (!confirm('Xóa "' + name + '"? Thao tác này không thể hoàn tác.')) {
                e.preventDefault();
            }
        });
    });
</script>
</body>
</html>