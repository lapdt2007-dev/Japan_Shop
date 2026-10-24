<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thêm sản phẩm - Kênh người bán</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/admin-product-form.css') }}">
</head>
<body>

<header class="topbar">
    <div class="topbar-inner">
        <a href="{{ route('admin.orders.index') }}" class="brand">Japan Shop</a>

        <form class="search-box" action="{{ route('admin.products.index') }}" method="GET" role="search">
            <input type="text" name="keyword" placeholder="Tìm theo tên sản phẩm" aria-label="Tìm sản phẩm">
            <button type="submit">Tìm kiếm</button>
        </form>

        <nav class="top-links">
            <a href="/"><i class="fa-solid fa-house"></i>Xem website</a>
        </nav>
    </div>
</header>

<div class="shell">
    <aside class="side">
        <h2>Kênh người bán</h2>
        <a href="{{ route('admin.orders.index') }}">Q.lý đơn hàng</a>
        <a href="{{ route('admin.products.index') }}">Q.lý sản phẩm</a>
        <a href="{{ route('admin.products.create') }}" class="active">Thêm sản phẩm</a>
    </aside>

    <main class="content">
        <div class="form-wrap">

            <div class="page-head" style="margin-bottom: 18px;">
                <div>
                    <h1>Thêm sản phẩm</h1>
                    <div class="total">Điền thông tin bên dưới. Sản phẩm sẽ hiện trên cửa hàng khi trạng thái là Hiển thị.</div>
                </div>
            </div>

            @if ($errors->any())
                <div class="alert alert-danger" role="alert">
                    Chưa lưu được sản phẩm. Vui lòng kiểm tra {{ $errors->count() }} lỗi bên dưới.
                </div>
            @endif

            <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data" id="productForm" class="form-panel">
                @csrf

                {{-- ===== Thông tin sản phẩm ===== --}}
                <section class="group">
                    <h2 class="group-title">Thông tin sản phẩm</h2>

                    <div class="field @error('category_id') has-error @enderror">
                        <label class="lbl" for="category_id">Danh mục sản phẩm <span class="req">*</span></label>
                        <select name="category_id" id="category_id" class="form-select" required>
                            <option value="">-- Chọn danh mục --</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('category_id') <div class="field-error">{{ $message }}</div> @enderror
                    </div>

                    <div class="field @error('name') has-error @enderror">
                        <label class="lbl" for="name">Tên sản phẩm <span class="req">*</span></label>
                        <input type="text" name="name" id="name" class="form-control" value="{{ old('name') }}"
                            placeholder="Nhập tên sản phẩm..." maxlength="255" required>
                        @error('name') <div class="field-error">{{ $message }}</div> @enderror
                    </div>
                </section>

                {{-- ===== Giá và kho ===== --}}
                <section class="group">
                    <h2 class="group-title">Giá và kho</h2>

                    <div class="row2">
                        <div class="field @error('price') has-error @enderror">
                            <label class="lbl" for="price">Giá gốc (VNĐ) <span class="req">*</span></label>
                            <div class="input-suffix">
                                <input type="number" name="price" id="price" class="form-control" value="{{ old('price') }}"
                                    placeholder="VD: 200000" min="0" required>
                                <span class="unit">đ</span>
                            </div>
                            @error('price') <div class="field-error">{{ $message }}</div> @enderror
                        </div>

                        <div class="field @error('sale_price') has-error @enderror">
                            <label class="lbl" for="sale_price">Giá khuyến mãi (VNĐ)</label>
                            <div class="input-suffix">
                                <input type="number" name="sale_price" id="sale_price" class="form-control" value="{{ old('sale_price') }}"
                                    placeholder="Để trống nếu không giảm" min="0">
                                <span class="unit">đ</span>
                            </div>
                            @error('sale_price')
                                <div class="field-error">{{ $message }}</div>
                            @else
                                <div class="hint">Phải nhỏ hơn giá gốc.</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row2">
                        <div class="field @error('quantity') has-error @enderror">
                            <label class="lbl" for="quantity">Số lượng trong kho <span class="req">*</span></label>
                            <input type="number" name="quantity" id="quantity" class="form-control" value="{{ old('quantity', 10) }}" min="0" required>
                            @error('quantity') <div class="field-error">{{ $message }}</div> @enderror
                        </div>

                        <div class="field @error('status') has-error @enderror">
                            <label class="lbl" for="status">Trạng thái <span class="req">*</span></label>
                            <select name="status" id="status" class="form-select">
                                <option value="1" {{ old('status') == '1' ? 'selected' : '' }}>Hiển thị</option>
                                <option value="0" {{ old('status') == '0' ? 'selected' : '' }}>Ẩn / Dừng bán</option>
                            </select>
                            @error('status') <div class="field-error">{{ $message }}</div> @enderror
                        </div>
                    </div>
                </section>

                {{-- ===== Ảnh và mô tả ===== --}}
                <section class="group">
                    <h2 class="group-title">Ảnh và mô tả</h2>

                    <div class="field @error('thumbnail') has-error @enderror">
                        <label class="lbl" for="thumbnail">Ảnh đại diện</label>
                        <input type="file" name="thumbnail" id="thumbnail" class="form-control" accept="image/*">
                        @error('thumbnail')
                            <div class="field-error">{{ $message }}</div>
                        @else
                            <div class="hint">JPG, PNG, WEBP hoặc GIF, tối đa 2MB.</div>
                        @enderror
                    </div>

                    <div class="field">
                        <label class="lbl" for="description">Mô tả sản phẩm</label>
                        <textarea name="description" id="description" class="form-control" rows="4"
                            placeholder="Mô tả ngắn về sản phẩm...">{{ old('description') }}</textarea>
                    </div>
                </section>

                <div class="form-actions">
                    <a href="{{ route('admin.products.index') }}" class="btn-back">Quay lại</a>
                    <button type="submit" class="btn-save" id="saveBtn">Lưu sản phẩm</button>
                </div>
            </form>

        </div>
    </main>
</div>

<script>
    // Tránh bấm lưu nhiều lần khi đang tải ảnh lên
    var form = document.getElementById('productForm');
    var saveBtn = document.getElementById('saveBtn');
    form.addEventListener('submit', function () {
        saveBtn.disabled = true;
        saveBtn.textContent = 'Đang lưu...';
    });
    window.addEventListener('pageshow', function () {
        saveBtn.disabled = false;
        saveBtn.textContent = 'Lưu sản phẩm';
    });
</script>
</body>
</html> 