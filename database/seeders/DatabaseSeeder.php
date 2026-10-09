<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\User;
use App\Models\UserAddress;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\CartItem;
use App\Models\Order;
use App\Models\OrderItem;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Tạo 1 Admin + 20 Khách hàng (>= 20 Users)
        User::create([
            'name'     => 'Quản Trị Viên',
            'email'    => 'admin@gmail.com',
            'password' => Hash::make('password'),
            'phone'    => '0901234567',
            'role'     => 'admin',
        ]);

        $users = [];
        for ($i = 1; $i <= 20; $i++) {
            $users[] = User::create([
                'name'     => "Khách Hàng $i",
                'email'    => "khachhang$i@gmail.com",
                'password' => Hash::make('password'),
                'phone'    => '0987' . str_pad($i, 6, '0', STR_PAD_LEFT),
                'role'     => 'customer',
            ]);
        }

        // 2. Tạo 20 Địa chỉ nhận hàng (>= 20 User Addresses)
        foreach ($users as $index => $user) {
            UserAddress::create([
                'user_id'        => $user->id,
                'recipient_name' => $user->name,
                'phone'          => $user->phone,
                'address_detail' => "Số " . ($index + 1) . " Đường Lê Lợi, Phường Bến Nghé, Quận 1, TP.HCM",
                'is_default'     => true,
            ]);
        }

        // 3. Tạo 9 Danh mục đúng yêu cầu
        $categoriesData = [
            ['name' => 'Manga', 'slug' => 'manga'],
            ['name' => 'Nội thất', 'slug' => 'noi-that'],
            ['name' => 'Thể thao', 'slug' => 'the-thao'],
            ['name' => 'Đồ chơi', 'slug' => 'do-choi'],
            ['name' => 'Thời trang', 'slug' => 'thoi-trang'],
            ['name' => 'Thực phẩm', 'slug' => 'thuc-pham'],
            ['name' => 'Mỹ phẩm', 'slug' => 'my-pham'],
            ['name' => 'Văn phòng phẩm', 'slug' => 'van-phong-pham'],
            ['name' => 'Điện tử', 'slug' => 'dien-tu'],
        ];

        $categories = [];
        foreach ($categoriesData as $cat) {
            $categories[] = Category::create($cat);
        }

        // 4. Tạo 22 Sản phẩm mẫu (>= 20 Products)
        $sampleProducts = [
            // Manga
            ['cat' => 'manga', 'name' => 'Truyện Tranh Chainsaw Man - Tập 1', 'price' => 45000, 'img' => 'https://images.unsplash.com/photo-1607604276583-eef5d076aa5f?w=500'],
            ['cat' => 'manga', 'name' => 'Truyện Tranh Jujutsu Kaisen - Tập 15', 'price' => 50000, 'img' => 'https://images.unsplash.com/photo-1544716278-ca5e3f4abd8c?w=500'],
            ['cat' => 'manga', 'name' => 'Truyện Tranh One Piece - Tập 100', 'price' => 35000, 'img' => 'https://images.unsplash.com/photo-1532012197267-da84d127e765?w=500'],
            // Nội thất
            ['cat' => 'noi-that', 'name' => 'Ghế Công Thức Ergonomic Cao Cấp', 'price' => 2500000, 'img' => 'https://images.unsplash.com/photo-1580481072645-022f9a6d83d0?w=500'],
            ['cat' => 'noi-that', 'name' => 'Bàn Làm Việc Gỗ Sồi Tự Nhiên', 'price' => 1800000, 'img' => 'https://images.unsplash.com/photo-1518455027359-f3f8164ba6bd?w=500'],
            // Thể thao
            ['cat' => 'the-thao', 'name' => 'Bóng Đá Thi Đấu Molten Chuẩn FIFA', 'price' => 650000, 'img' => 'https://images.unsplash.com/photo-1614632537190-23e4146777db?w=500'],
            ['cat' => 'the-thao', 'name' => 'Vợt Cầu Lông Yonex Astrox 88D', 'price' => 3200000, 'img' => 'https://images.unsplash.com/photo-1626225967045-9410dd9913d9?w=500'],
            // Đồ chơi
            ['cat' => 'do-choi', 'name' => 'Bộ Lắp Ráp Lego Technic Xe Đua', 'price' => 1250000, 'img' => 'https://images.unsplash.com/photo-1587654780291-39c9404d746b?w=500'],
            ['cat' => 'do-choi', 'name' => 'Mô Hình Gundam RX-78-2 HG 1/144', 'price' => 420000, 'img' => 'https://images.unsplash.com/photo-1608889175123-8ee362201f81?w=500'],
            // Thời trang
            ['cat' => 'thoi-trang', 'name' => 'Áo Phông Oversize Unisex Premium', 'price' => 220000, 'img' => 'https://images.unsplash.com/photo-1521572267360-ee0c2909d518?w=500'],
            ['cat' => 'thoi-trang', 'name' => 'Quần Jean Nam Form Dáng Slimfit', 'price' => 450000, 'img' => 'https://images.unsplash.com/photo-1542272604-780c36856842?w=500'],
            // Thực phẩm
            ['cat' => 'thuc-pham', 'name' => 'Thùng 24 Lon Nước Ngọt Coca Cola', 'price' => 210000, 'img' => 'https://images.unsplash.com/photo-1622483767028-3f66f32aef97?w=500'],
            ['cat' => 'thuc-pham', 'name' => 'Hộp Hạt Dinh Dưỡng Mix 5 Loại Hạt', 'price' => 185000, 'img' => 'https://images.unsplash.com/photo-1599599810769-bcde5a160d32?w=500'],
            // Mỹ phẩm
            ['cat' => 'my-pham', 'name' => 'Kem Chống Nắng La Roche-Posay 50ml', 'price' => 395000, 'img' => 'https://images.unsplash.com/photo-1556228720-195a672e8a03?w=500'],
            ['cat' => 'my-pham', 'name' => 'Sữa Rửa Mặt CeraVe Foaming Cleanser', 'price' => 320000, 'img' => 'https://images.unsplash.com/photo-1620916566398-39f1143ab7be?w=500'],
            // Văn phòng phẩm
            ['cat' => 'van-phong-pham', 'name' => 'Bộ 10 Bút Gel Thiên Long Mực Xanh', 'price' => 60000, 'img' => 'https://cdn.hstatic.net/products/1000230347/artboard_1_copy_4a1b92a3739a4f8a9944acd970903037_master.jpg'],
            ['cat' => 'van-phong-pham', 'name' => 'Sổ Tay Lò Xo A5 Cìa Cứng 200 Trang', 'price' => 45000, 'img' => 'https://images.unsplash.com/photo-1544816155-12df9643f363?w=500'],
            // Điện tử
            ['cat' => 'dien-tu', 'name' => 'Tai Nghe Bluetooth Không Dây TWS', 'price' => 590000, 'img' => 'https://images.unsplash.com/photo-1590658268037-6bf12165a8df?w=500'],
            ['cat' => 'dien-tu', 'name' => 'Chuột Không Dây Logitech Silent', 'price' => 350000, 'img' => 'https://images.unsplash.com/photo-1615663245857-ac93bb7c39e7?w=500'],
            ['cat' => 'dien-tu', 'name' => 'Bàn Phím Cơ AKKO 3087 Silent', 'price' => 1350000, 'img' => 'https://images.unsplash.com/photo-1587829741301-dc798b83add3?w=500'],
            ['cat' => 'dien-tu', 'name' => 'Sạc Dự Phòng Anker 20000mAh Sạc Nhanh', 'price' => 780000, 'img' => 'https://images.unsplash.com/photo-1609592424074-12966e342a17?w=500'],
            ['cat' => 'dien-tu', 'name' => 'Màn Hình Máy Tính Dell 24 inch FHD', 'price' => 3250000, 'img' => 'https://vitinhhoanggia.com.vn/wp-content/uploads/2019/03/16036867526273-1.jpg'],
        ];

        $products = [];
        foreach ($sampleProducts as $p) {
            $cat = Category::where('slug', $p['cat'])->first();
            $products[] = Product::create([
                'category_id' => $cat->id,
                'name'        => $p['name'],
                'slug'        => Str::slug($p['name']),
                'thumbnail'   => $p['img'],
                'description' => "Mô tả chi tiết sản phẩm {$p['name']}. Hàng chính hãng, bảo hành 12 tháng.",
                'price'       => $p['price'],
                'sale_price'  => $p['price'] * 0.9,
                'quantity'    => rand(10, 100),
                'status'      => true,
            ]);
        }

        // 5. Tạo 22 Ảnh phụ cho sản phẩm (>= 20 Product Images)
        foreach ($products as $product) {
            ProductImage::create([
                'product_id' => $product->id,
                'image_path' => $product->thumbnail,
            ]);
        }

        // 6. Tạo 20 Giỏ hàng mẫu (>= 20 Cart Items)
        foreach ($users as $index => $user) {
            CartItem::create([
                'user_id'    => $user->id,
                'product_id' => $products[$index % count($products)]->id,
                'quantity'   => rand(1, 3),
            ]);
        }

        // 7. Tạo 20 Đơn hàng mẫu (>= 20 Orders)
        $orders = [];
        foreach ($users as $index => $user) {
            $orders[] = Order::create([
                'order_code'       => 'DH' . (10000 + $index),
                'user_id'          => $user->id,
                'recipient_name'   => $user->name,
                'recipient_phone'  => $user->phone,
                'shipping_address' => "Số " . ($index + 1) . " Đường Lê Lợi, Quận 1, TP.HCM",
                'total_amount'     => $products[$index % count($products)]->price,
                'payment_method'   => 'COD',
                'payment_status'   => $index % 2 == 0 ? 'paid' : 'unpaid',
                'order_status'     => 'pending',
                'note'             => 'Giao hàng giờ hành chính',
            ]);
        }

        // 8. Tạo 20 Chi tiết đơn hàng mẫu (>= 20 Order Items)
        foreach ($orders as $index => $order) {
            $p = $products[$index % count($products)];
            OrderItem::create([
                'order_id'     => $order->id,
                'product_id'   => $p->id,
                'product_name' => $p->name,
                'price'        => $p->price,
                'quantity'     => rand(1, 2),
            ]);
        }
    }
}