@props(['product'])

<div class="card h-100 border-0 shadow-sm product-card bg-white">
    <!-- Ảnh + Link Chi Tiết -->
    <a href="{{ Route::has('client.products.show') ? route('client.products.show', $product->slug) : '#' }}" class="product-img-wrapper text-decoration-none">
        <span class="badge bg-danger product-badge">
            <i class="fa-solid fa-circle-check me-1"></i>Chính hãng
        </span>
        
        @php
            $imagePath = $product->thumbnail ?? $product->image;
            
            // Xử lý thông minh mọi trường hợp đường dẫn ảnh trong DB
            if (filter_var($imagePath, FILTER_VALIDATE_URL)) {
                $imageUrl = $imagePath; // Ảnh dạng link ngoài (http/https)
            } elseif (str_starts_with($imagePath, 'storage/')) {
                $imageUrl = asset($imagePath); // Đã có chữ storage/ sẵn
            } elseif (str_starts_with($imagePath, '/storage/')) {
                $imageUrl = asset(ltrim($imagePath, '/')); // Có dấu gạch chéo đầu
            } else {
                $imageUrl = asset('storage/' . ltrim($imagePath, '/')); // Chỉ có tên thư mục/file (ví dụ: products/abc.jpg)
            }
        @endphp

        <img src="{{ $imageUrl }}" class="product-img" alt="{{ $product->name }}" style="height: 180px; object-fit: cover; width: 100%;" onerror="this.src='https://via.placeholder.com/300x300?text=Japan+Shop'">
    </a>

    <!-- Nội dung sản phẩm -->
    <div class="card-body d-flex flex-column p-3">
        <div class="mb-2">
            <span class="badge bg-light text-dark border fw-normal">{{ $product->category->name ?? 'Nhật Bản' }}</span>
        </div>
        
        <h6 class="card-title fw-bold text-dark text-truncate mb-2" title="{{ $product->name }}">
            <a href="{{ Route::has('client.products.show') ? route('client.products.show', $product->slug) : '#' }}" class="text-decoration-none text-dark">
                {{ $product->name }}
            </a>
        </h6>
        
        <p class="card-text text-muted small flex-grow-1 text-clamp-2">
            {{ $product->description }}
        </p>

        <!-- Giá và Form Nút Thêm Vào Giỏ Hàng -->
        <div class="d-flex justify-content-between align-items-center pt-2 border-top mt-2">
            <div>
                @if($product->sale_price && $product->sale_price < $product->price)
                    <div class="price-tag">{{ number_format($product->sale_price, 0, ',', '.') }}đ</div>
                    <small class="text-muted text-decoration-line-through me-1">{{ number_format($product->price, 0, ',', '.') }}đ</small>
                @else
                    <div class="price-tag">{{ number_format($product->price, 0, ',', '.') }}đ</div>
                @endif
            </div>

            <!-- FORM THÊM GIỎ HÀNG -->
            @if(Route::has('cart.add'))
                <form action="{{ route('cart.add', $product->id) }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-danger btn-sm rounded-pill px-3 shadow-sm fw-semibold">
                        <i class="fa-solid fa-cart-plus me-1"></i> Thêm
                    </button>
                </form>
            @else
                <button type="button" class="btn btn-danger btn-sm rounded-pill px-3 shadow-sm fw-semibold">
                    <i class="fa-solid fa-cart-plus me-1"></i> Thêm
                </button>
            @endif
        </div>
    </div>
</div>