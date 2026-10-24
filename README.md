# Japan Shop

Cửa hàng bán hàng Nhật (Laravel 9, Blade + Bootstrap).

## Cài đặt

```bash
composer install
cp .env.example .env          # rồi chỉnh DB_* cho đúng máy bạn
php artisan key:generate
php artisan migrate           # BẮT BUỘC sau khi cập nhật: có 2 migration mới (orders.email, feedbacks)
php artisan db:seed           # (tuỳ chọn) dữ liệu mẫu — CHỈ dùng khi phát triển
php artisan storage:link
php artisan serve
```

Tài khoản mẫu từ seeder: xem `database/seeders/DatabaseSeeder.php`.
**Đổi mật khẩu admin trước khi đưa lên production**, và đặt `APP_DEBUG=false`.

## Chạy test

```bash
php artisan test
```

Test dùng SQLite in-memory (cấu hình trong `phpunit.xml`) nên **không bao giờ đụng vào database thật**.
Cần bật extension `pdo_sqlite` trong `php.ini`.

## Kiến trúc chính

| Thành phần | Vai trò |
|---|---|
| `app/Services/CartService.php` | Toàn bộ logic giỏ hàng: tồn kho, giá, đồng bộ với DB, voucher |
| `config/vouchers.php` | Danh sách mã giảm giá |
| `app/Exceptions/ShopException.php` | Lỗi nghiệp vụ có thông điệp hiển thị trực tiếp cho khách |
| `Product::scopeActive / scopeFilter` | Lọc sản phẩm dùng chung cho trang chủ, hàng hoá, quốc tế |

## Quy tắc nghiệp vụ

- Giá, tồn kho và điều kiện voucher luôn được kiểm tra lại với DB khi xem giỏ và khi đặt hàng; không tin dữ liệu trong session.
- Nếu giỏ hàng thay đổi (đổi giá, hết hàng, voucher không còn hợp lệ) thì đơn **không** được tạo ngầm — khách được báo và xem lại.
- Đơn hàng đã hủy là trạng thái cuối (tồn kho đã hoàn đúng 1 lần); đơn đã hoàn thành không thể hủy.
- Chỉ khách có đơn **completed** chứa sản phẩm mới được đánh giá; mỗi người 1 đánh giá / sản phẩm.
- Trang `/checkout/success/{id}` chỉ chủ đơn (hoặc phiên vừa đặt, hoặc admin) xem được.

## Xử lý sự cố thường gặp

| Hiện tượng | Nguyên nhân thường gặp | Cách xử lý |
|---|---|---|
| Đặt hàng báo "Không thể đặt hàng lúc này" | Chưa chạy migration mới, hoặc lỗi DB khác | `php artisan migrate`; mở `storage/logs/laravel.log` xem dòng lỗi cuối. Khi `APP_DEBUG=true`, chi tiết lỗi hiện ngay trên thông báo |
| Tải ảnh sản phẩm báo lỗi | Ảnh > 2MB hoặc vượt `upload_max_filesize`/`post_max_size` của PHP; thư mục `storage` không có quyền ghi | Dùng ảnh nhỏ hơn / tăng giới hạn trong `php.ini` rồi restart Apache; cấp quyền ghi cho `storage` |
| Tải ảnh lên được nhưng không hiện ảnh | `public/storage` chưa là liên kết tới `storage/app/public` (hoặc là thư mục thật) | Xoá thư mục `public/storage` nếu nó là thư mục thật, rồi `php artisan storage:link` |
| Sau khi cập nhật code, trang lạ/lỗi lạ | Cache cũ | `php artisan optimize:clear` và `composer dump-autoload` |
