<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Chỉnh Sửa Sản Phẩm</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light p-4">

<div class="container bg-white p-4 rounded-3 shadow-sm" style="max-width: 650px;">
    <h3 class="fw-bold text-warning mb-4">✏️ Chỉnh Sửa Sản Phẩm #{{ $product->id }}</h3>

    <form action="{{ route('admin.products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <!-- Chọn Danh Mục -->
        <div class="mb-3">
            <label class="form-label fw-semibold">Danh mục sản phẩm <span class="text-danger">*</span></label>
            <select name="category_id" class="form-select" required>
                <option value="">-- Chọn danh mục --</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}" {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label fw-semibold">Tên sản phẩm <span class="text-danger">*</span></label>
            <input type="text" name="name" class="form-control" value="{{ old('name', $product->name) }}" required>
        </div>

        <!-- Giá gốc -->
        <div class="row mb-3">
            <div class="col-md-6">
                <label for="price" class="form-label fw-bold">Giá bán gốc (VNĐ) <span class="text-danger">*</span></label>
                <input type="number" class="form-control" id="price" name="price" value="{{ old('price', $product->price) }}" min="0" required>
            </div>
        <!-- Giá khuyến mãi -->
            <div class="col-md-6">
                <label for="sale_price" class="form-label fw-bold">Giá khuyến mãi (VNĐ)</label>
                <input type="number" class="form-control" id="sale_price" name="sale_price" value="{{ old('sale_price', $product->sale_price) }}" min="0" placeholder="Để trống nếu không giảm giá">
                <small class="text-muted">Giá khuyến mãi phải nhỏ hơn giá gốc (nếu có).</small>
            </div>
        </div>

        <!-- Trạng thái -->
        <div class="row mb-3">
            <div class="col-md-6">
                <label class="form-label fw-semibold">Trạng thái <span class="text-danger">*</span></label>
                <select name="status" class="form-select @error('status') is-invalid @enderror" required>
                    <option value="1" {{ old('status', $product->status) == 1 ? 'selected' : '' }}>Hiển thị</option>
                    <option value="0" {{ old('status', $product->status) == 0 ? 'selected' : '' }}>Ẩn / Dừng bán</option>
                </select>
                @error('status') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="col-md-6">
                <!-- Số lượng tồn kho -->
                <label class="form-label fw-semibold">Số lượng tồn kho <span class="text-danger">*</span></label>
                <input type="number" name="quantity" class="form-control" value="{{ old('quantity', $product->quantity) }}" required>
            </div>
        </div>

        <!-- Ảnh Thumbnail (nếu muốn cho sửa ảnh) -->
        <div class="mb-3">
            <label class="form-label fw-semibold">Ảnh đại diện mới (Nếu muốn thay đổi)</label>
            <input type="file" name="thumbnail" class="form-control" accept="image/*">
            @if($product->thumbnail)
                <div class="mt-2">
                    <img src="{{ asset('storage/' . $product->thumbnail) }}" width="60" class="rounded border">
                    <small class="text-muted d-block">Ảnh hiện tại</small>
                </div>
            @endif
        </div>

        <div class="mb-4">
            <label class="form-label fw-semibold">Mô tả sản phẩm</label>
            <textarea name="description" class="form-control" rows="4">{{ old('description', $product->description) }}</textarea>
        </div>

        <div class="d-flex justify-content-between">
            <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">Hủy bỏ</a>
            <button type="submit" class="btn btn-warning fw-bold px-4">Cập Nhật Sản Phẩm</button>
        </div>
    </form>
</div>

</body>
</html>