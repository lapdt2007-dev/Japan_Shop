<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Phản Hồi Khách Hàng - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-light">

<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold text-dark mb-0">
            <i class="fa-solid fa-comments text-primary me-2"></i>Phản Hồi Khách Hàng
            <span class="badge bg-secondary fs-6 ms-2">{{ $feedbacks->total() }}</span>
        </h3>
        <div>
            <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-secondary btn-sm me-1">Đơn hàng</a>
            <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary btn-sm me-1">Sản phẩm</a>
            <a href="/" class="btn btn-outline-secondary btn-sm"><i class="fa-solid fa-house me-1"></i> Xem Website</a>
        </div>
    </div>

    @include('partials.flash-errors')
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fa-solid fa-circle-check me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card border-0 shadow-sm rounded-3 p-3 mb-4">
        <form action="{{ route('admin.feedback.index') }}" method="GET" class="row g-2 align-items-center">
            <div class="col-md-6">
                <input type="text" name="keyword" class="form-control" placeholder="Tìm theo tên, email, chủ đề, nội dung..."
                       value="{{ request('keyword') }}">
            </div>
            <div class="col-md-3">
                <select name="rating" class="form-select">
                    <option value="">-- Tất cả số sao --</option>
                    @for($i = 5; $i >= 1; $i--)
                        <option value="{{ $i }}" {{ (int) request('rating') === $i ? 'selected' : '' }}>{{ $i }} sao</option>
                    @endfor
                </select>
            </div>
            <div class="col-md-3 d-flex gap-2">
                <button class="btn btn-primary flex-grow-1"><i class="fa-solid fa-magnifying-glass me-1"></i>Lọc</button>
                <a href="{{ route('admin.feedback.index') }}" class="btn btn-outline-secondary">Xóa lọc</a>
            </div>
        </form>
        <div class="small text-muted mt-2">
            Điểm đánh giá trung bình: <strong>{{ $averageRating ?: '—' }}</strong> / 5
        </div>
    </div>

    <div class="card border-0 shadow-sm rounded-3">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th style="width:140px">Thời gian</th>
                        <th style="width:220px">Người gửi</th>
                        <th>Nội dung</th>
                        <th style="width:120px">Đánh giá</th>
                        <th style="width:110px" class="text-center">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                @forelse($feedbacks as $fb)
                    <tr>
                        <td class="small text-muted">{{ $fb->created_at->format('d/m/Y H:i') }}</td>
                        <td>
                            <div class="fw-semibold">{{ $fb->name }}
                                @if($fb->user_id)
                                    <span class="badge bg-info text-dark ms-1">Thành viên</span>
                                @endif
                            </div>
                            <a href="mailto:{{ $fb->email }}" class="small">{{ $fb->email }}</a>
                        </td>
                        <td>
                            @if($fb->subject)
                                <div class="fw-semibold">{{ $fb->subject }}</div>
                            @endif
                            <div style="white-space: pre-line;">{{ $fb->message }}</div>
                        </td>
                        <td>
                            @if($fb->rating)
                                @for($i = 1; $i <= 5; $i++)
                                    <i class="fa-star {{ $i <= $fb->rating ? 'fa-solid text-warning' : 'fa-regular text-muted' }}"></i>
                                @endfor
                            @else
                                <span class="text-muted">—</span>
                            @endif
                        </td>
                        <td class="text-center">
                            <a href="mailto:{{ $fb->email }}" class="btn btn-sm btn-outline-primary" title="Trả lời qua email">
                                <i class="fa-solid fa-reply"></i>
                            </a>
                            <form action="{{ route('admin.feedback.destroy', $fb->id) }}" method="POST" class="d-inline"
                                  onsubmit="return confirm('Xóa phản hồi này?');">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger" title="Xóa"><i class="fa-solid fa-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="text-center text-muted py-5">Chưa có phản hồi nào.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
        @if($feedbacks->hasPages())
            <div class="p-3">{{ $feedbacks->links('pagination::bootstrap-5') }}</div>
        @endif
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>