# Changelog (đợt rà soát & nâng cấp 09/10/2026)

## Bảo mật
- Trang `/checkout/success/{id}` không còn lộ tên/SĐT/địa chỉ của đơn người khác (kiểm tra quyền, trả 404).
- Đăng nhập: giới hạn 5 lần sai / phút theo email+IP, `session()->regenerate()` sau đăng nhập/đăng ký.
- Đăng ký: mật khẩu tối thiểu 8 ký tự; `role` bị gỡ khỏi `User::$fillable` (chống leo quyền admin).
- Thêm throttle cho đăng ký, đặt hàng, đánh giá, phản hồi.
- Escape ký tự `%` `_` trong các ô tìm kiếm LIKE.
- Xoá `ProductController::update` (dùng `$request->all()`, không có route).

## Lỗi logic đã sửa
- Voucher: kiểm tra lại điều kiện `min_order` mỗi lần xem giỏ và khi đặt hàng (trước đây giảm giỏ vẫn giữ mã).
- Đơn hàng giờ lưu đủ `sub_total`, `discount`, `voucher_code`, `email`.
- Giá khi đặt hàng lấy từ DB tại thời điểm đặt, không lấy từ session; khoá dòng sản phẩm theo thứ tự id (tránh deadlock).
- Giỏ hàng: chặn sản phẩm ngừng bán / hết hàng, giới hạn số lượng theo tồn kho, tự cập nhật giá & số lượng.
- Admin đổi trạng thái đơn: validate giá trị; hoàn kho đúng 1 lần; không mở lại đơn đã hủy; không hủy đơn đã hoàn thành.
- Admin sản phẩm: sửa lỗi 500 ở route `show` không tồn tại; `update` thêm rule `sale_price < price`.
- Review: sản phẩm phải tồn tại, người dùng phải có đơn hoàn thành, mỗi người 1 đánh giá / sản phẩm.
- Feedback: trước đây chỉ báo "đã ghi nhận" mà không lưu; nay lưu vào bảng `feedbacks`.
- Bộ lọc thanh toán ở "Đơn của tôi" khớp giá trị lưu trong DB (`COD`/`BANK`).
- Phân trang trang admin giữ nguyên bộ lọc (`withQueryString`).
- Nhiều trang trước đây không hiển thị lỗi `session('error')` / lỗi validate (giỏ hàng, chi tiết sản phẩm, trang chủ, toàn bộ trang admin) — đã thêm `partials/flash-errors`.
- Chặn lỗi `RouteServiceProvider::HOME` trỏ `/dashboard` (404) — nay là `/`.

## Đã xoá
- **Chatbot** (controller, view, route, cấu hình Gemini).
- Phần Breeze không dùng/không có route: controllers `Auth/*`, `ProfileController`, `routes/auth.php`, view profile/dashboard/forgot/reset/verify, layouts, components, test mẫu.
- View thừa/trùng/rỗng: `123/`, `12produc/`, `products/`, `checkout*.blade.php` ở gốc, `client/cart`, `layouts/header`, `welcome`; model `Voucher` rỗng.

## Cải tiến cấu trúc
- `CartService` + `config/vouchers.php` + `ShopException`.
- `Product::scopeActive/scopeFilter/resolvePrice/wasPurchasedBy`; Home/World/Product dùng chung scope.
- `ProductController::show` eager-load `reviews.user` (hết N+1).
- Bộ test: `tests/Feature/*` và `tests/Unit/PricingTest.php`; `phpunit.xml` dùng SQLite in-memory.
