<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Quản Lý Sản Phẩm - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-light p-4">

<div class="container bg-white p-4 rounded-3 shadow-sm">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold mb-0 text-primary">📦 Quản Lý Sản Phẩm & Tồn Kho</h3>
        <div>
            <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-secondary me-2">Đơn hàng</a>
            <a href="{{ route('admin.products.create') }}" class="btn btn-success fw-bold">
                <i class="fa-solid fa-plus me-1"></i> Thêm Sản Phẩm Mới
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="table-responsive">
        <table class="table table-hover align-middle border">
            <thead class="table-dark">
                <tr>
                    <th>#ID</th>
                    <th>Ảnh</th>
                    <th>Tên sản phẩm</th>
                    <th>Danh mục</th>
                    <th>Giá bán</th>
                    <th>Số lượng</th>
                    <th>Trạng thái</th>
                    <th class="text-center">Thao tác</th>
                </tr>
            </thead>
            <tbody>
                @forelse($products as $product)
                    <tr>
                        <td><strong>#{{ $product->id }}</strong></td>
                        <td>
                            @if($product->thumbnail)
                                <img src="{{ asset('storage/' . $product->thumbnail) }}" alt="{{ $product->name }}" width="50" height="50" class="rounded object-fit-cover border">
                            @else
                                <span class="badge bg-secondary">Chưa có ảnh</span>
                            @endif
                        </td>
                        <td class="fw-semibold">{{ $product->name }}</td>
                        <td><span class="badge bg-info text-dark">{{ $product->category->name ?? 'Chưa phân loại' }}</span></td>
                        <td>
                            @if($product->sale_price)
                                <span class="text-danger fw-bold me-1">{{ number_format($product->sale_price) }} đ</span>
                                <small class="text-muted text-decoration-line-through">{{ number_format($product->price) }} đ</small>
                            @else
                                <span class="fw-bold text-dark">{{ number_format($product->price) }} đ</span>
                            @endif
                        </td>
                        <td><span class="fw-bold">{{ $product->quantity }}</span></td>
                        <td>
                            @if($product->status == 1)
                                <span class="badge bg-success">Hiển thị</span>
                            @else
                                <span class="badge bg-secondary">Ẩn</span>
                            @endif
                        </td>
                        <td class="text-center">
                            <a href="{{ route('admin.products.edit', $product->id) }}" class="btn btn-sm btn-warning me-1">
                                <i class="fa-solid fa-pen"></i> Sửa
                            </a>
                            <form action="{{ route('admin.products.destroy', $product->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Bro có chắc muốn xóa sản phẩm này?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger">
                                    <i class="fa-solid fa-trash"></i> Xóa
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center text-muted py-4">Chưa có sản phẩm nào trong CSDL.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-3">
        {{ $products->links() }}
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>