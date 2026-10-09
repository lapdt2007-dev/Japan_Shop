<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Thêm Sản Phẩm Mới</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light p-4">

<div class="container bg-white p-4 rounded-3 shadow-sm" style="max-width: 700px;">
    <h3 class="fw-bold text-success mb-4">➕ Thêm Sản Phẩm Mới</h3>

    <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <!-- Chọn Danh Mục -->
        <div class="mb-3">
            <label class="form-label fw-semibold">Danh mục sản phẩm <span class="text-danger">*</span></label>
            <select name="category_id" class="form-select @error('category_id') is-invalid @enderror" required>
                <option value="">-- Chọn danh mục --</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>
            @error('category_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <!-- Tên Sản Phẩm -->
        <div class="mb-3">
            <label class="form-label fw-semibold">Tên sản phẩm <span class="text-danger">*</span></label>
            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" placeholder="Nhập tên sản phẩm..." required>
            @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <!-- Giá Gốc & Giá Khuyến Mãi -->
        <div class="row mb-3">
            <div class="col-md-6">
                <label class="form-label fw-semibold">Giá gốc (VNĐ) <span class="text-danger">*</span></label>
                <input type="number" name="price" class="form-control @error('price') is-invalid @enderror" value="{{ old('price') }}" placeholder="VD: 200000" required>
                @error('price') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="col-md-6">
                <label class="form-label fw-semibold">Giá khuyến mãi (VNĐ)</label>
                <input type="number" name="sale_price" class="form-control @error('sale_price') is-invalid @enderror" value="{{ old('sale_price') }}" placeholder="Để trống nếu không giảm">
                @error('sale_price') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
        </div>

        <!-- Số Lượng & Trạng Thái -->
        <div class="row mb-3">
            <div class="col-md-6">
                <label class="form-label fw-semibold">Số lượng (Quantity) <span class="text-danger">*</span></label>
                <input type="number" name="quantity" class="form-control @error('quantity') is-invalid @enderror" value="{{ old('quantity', 10) }}" required>
                @error('quantity') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="col-md-6">
                <label class="form-label fw-semibold">Trạng thái <span class="text-danger">*</span></label>
                <select name="status" class="form-select">
                    <option value="1" {{ old('status') == '1' ? 'selected' : '' }}>Hiển thị</option>
                    <option value="0" {{ old('status') == '0' ? 'selected' : '' }}>Ẩn / Dừng bán</option>
                </select>
            </div>
        </div>

        <!-- Thumbnail Ảnh Đại Diện -->
        <div class="mb-3">
            <label class="form-label fw-semibold">Ảnh đại diện (Thumbnail)</label>
            <input type="file" name="thumbnail" class="form-control @error('thumbnail') is-invalid @enderror" accept="image/*">
            @error('thumbnail') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <!-- Mô tả -->
        <div class="mb-4">
            <label class="form-label fw-semibold">Mô tả sản phẩm</label>
            <textarea name="description" class="form-control" rows="3" placeholder="Mô tả ngắn về sản phẩm...">{{ old('description') }}</textarea>
        </div>

        <div class="d-flex justify-content-between">
            <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">Quay lại</a>
            <button type="submit" class="btn btn-success fw-bold px-4">Lưu Sản Phẩm</button>
        </div>
    </form>
</div>

</body>
</html>