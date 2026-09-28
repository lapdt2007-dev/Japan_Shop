# Japan Shop - Website Bán Hàng Nhật Bản (Nhóm 5)

<p align="center">
  <a href="https://laravel.com" target="_blank">
    <img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="350" alt="Laravel Logo">
  </a>
</p>

<p align="center">
  <img src="https://img.shields.io/badge/Laravel-FF2D20?style=for-the-badge&logo=laravel&logoColor=white" alt="Laravel Framework">
  <img src="https://img.shields.io/badge/PHP-777BB4?style=for-the-badge&logo=php&logoColor=white" alt="PHP">
  <img src="https://img.shields.io/badge/MySQL-4479A1?style=for-the-badge&logo=mysql&logoColor=white" alt="MySQL">
  <img src="https://img.shields.io/badge/Team-Group%205-blue?style=for-the-badge" alt="Nhóm 5">
</p>

---

## 🌸 Giới thiệu về dự án (About Japan Shop)

**Japan Shop** là dự án thương mại điện tử chuyên cung cấp các sản phẩm nội địa Nhật Bản chất lượng cao (mỹ phẩm, thực phẩm chức năng, đồ gia dụng, thời trang,...). Dự án được phát triển bởi **Nhóm 5** trên nền tảng **Laravel Framework**, hướng tới việc mang đến trải nghiệm mua sắm nhanh chóng, an toàn và tiện lợi cho người dùng.

### Các tính năng chính (Features)

- **Giao diện người dùng (Customer Front-end):**
  - Danh mục sản phẩm chuẩn Nhật Bản, tìm kiếm & bộ lọc thông minh.
  - Quản lý giỏ hàng, giỏ hàng tạm lưu và thanh toán trực tuyến.
  - Đăng ký / Đăng nhập tài khoản và lịch sử đơn hàng.
  - Đánh giá và bình luận sản phẩm.

- **Hệ thống quản trị (Admin Dashboard):**
  - Quản lý sản phẩm và kho hàng.
  - Quản lý đơn hàng, cập nhật trạng thái giao hàng.
  - Thống kê doanh thu, báo cáo chi tiết.

---

## 🚀 Hướng dẫn cài đặt (Installation Guide)

Để chạy dự án ở môi trường Local, vui lòng thực hiện các bước sau:

1. **Clone repository về máy:**
   ```bash
   git clone: ----updating-----
   cd japan-shop
   ```

2. **Cài đặt các thư viện PHP (Composer dependencies):**
   ```bash
   composer install
   ```

3. **Tạo file cấu hình môi trường `.env`:**
   ```bash
   cp .env.example .env
   ```

4. **Tạo mã khóa ứng dụng (Application Key):**
   ```bash
   php artisan key:generate
   ```

5. **Cấu hình Cơ sở dữ liệu:**
   Mở file `.env` và cập nhật các thông tin kết nối Database của bạn:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=japan_shop
   DB_USERNAME=root
   DB_PASSWORD=
   ```

6. **Chạy Migration và Seed dữ liệu mẫu:**
   ```bash
   php artisan migrate --seed
   ```

7. **Khởi chạy máy chủ phát triển (Local Server):**
   ```bash
   php artisan serve
   ```
   Sau đó truy cập ứng dụng tại: `http://127.0.0.1:8000`

---

## 👥 Thành viên thực hiện (Group 5 Members)

Dự án được xây dựng và phát triển bởi các thành viên **Nhóm 5**:

| STT | Họ và Tên | Vai trò / Nhiệm vụ | GitHub |
| :---: | :--- | :--- | :---: |
| 1 | Đinh Thành Lập | Leader / Backend Developer | https://github.com/lapdt2007-dev |
| 2 | Trần Tấn Đạt | Frontend Developer / UI-UX | https://github.com/datstyle  |
| 3 | Lê Nguyễn An Trường| Backend Developer / Database | https://github.com/antruong31|

---

## 🛠 Công nghệ sử dụng (Tech Stack)

- **Backend:** [PHP 8.x](https://www.php.net/), [Laravel Framework](https://laravel.com)
- **Database:** [MySQL](https://www.mysql.com/)
- **Frontend:** HTML5, CSS3, JavaScript, Bootstrap / Tailwind CSS, Blade Template Engine
- **Tools:** Git, GitHub, Composer, VS Code

---

## 📄 Giấy phép (License)

Dự án này được phát triển cho mục đích học tập và nghiên cứu. Sử dụng mã nguồn mở theo [MIT license](https://opensource.org/licenses/MIT).
