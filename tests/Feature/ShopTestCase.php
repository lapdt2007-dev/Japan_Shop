<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

/**
 * Lớp nền cho các test của cửa hàng (dùng SQLite in-memory, xem phpunit.xml).
 */
abstract class ShopTestCase extends TestCase
{
    use RefreshDatabase;

    protected function makeProduct(array $overrides = []): Product
    {
        $category = Category::firstOrCreate(['slug' => 'manga'], ['name' => 'Manga']);

        return Product::create(array_merge([
            'category_id' => $category->id,
            'name'        => 'Sản phẩm ' . uniqid(),
            'slug'        => 'san-pham-' . uniqid(),
            'price'       => 100000,
            'sale_price'  => null,
            'quantity'    => 10,
            'status'      => 1,
        ], $overrides));
    }

    protected function makeAdmin(): User
    {
        $admin = User::factory()->create();
        $admin->forceFill(['role' => 'admin'])->save();

        return $admin;
    }

    protected function checkoutPayload(array $overrides = []): array
    {
        return array_merge([
            'fullname'       => 'Nguyễn Văn A',
            'phone'          => '0901234567',
            'email'          => 'a@example.com',
            'address'        => '1 Lê Lợi, Quận 1, TP.HCM',
            'payment_method' => 'cod',
        ], $overrides);
    }

    /** Tạo nhanh 1 đơn hàng trong DB. */
    protected function makeOrder(Product $product, int $quantity = 2, array $overrides = []): int
    {
        $orderId = DB::table('orders')->insertGetId(array_merge([
            'order_code'       => 'ORD-' . strtoupper(substr(uniqid(), -8)),
            'user_id'          => null,
            'recipient_name'   => 'Khách',
            'recipient_phone'  => '0900000000',
            'shipping_address' => 'Địa chỉ',
            'total_amount'     => $product->price * $quantity,
            'payment_method'   => 'COD',
            'payment_status'   => 'unpaid',
            'order_status'     => 'pending',
            'created_at'       => now(),
            'updated_at'       => now(),
        ], $overrides));

        DB::table('order_items')->insert([
            'order_id'     => $orderId,
            'product_id'   => $product->id,
            'product_name' => $product->name,
            'price'        => $product->price,
            'quantity'     => $quantity,
            'created_at'   => now(),
            'updated_at'   => now(),
        ]);

        return $orderId;
    }
}
