<?php

namespace Tests\Feature;

use App\Models\Product;
use Illuminate\Support\Facades\DB;

class CheckoutTest extends ShopTestCase
{
    public function test_checkout_creates_order_decrements_stock_and_clears_cart(): void
    {
        $product = $this->makeProduct(['price' => 100000, 'quantity' => 10]);
        $this->post(route('cart.add', $product->id), ['quantity' => 3]); // 300k
        $this->post(route('cart.voucher.apply'), ['voucher_code' => 'JAPAN50K']);

        $response = $this->post(route('checkout.process'), $this->checkoutPayload());

        $order = DB::table('orders')->first();
        $this->assertNotNull($order);
        $response->assertRedirect(route('checkout.success', $order->id));

        $this->assertEquals(300000, $order->sub_total);
        $this->assertEquals(50000, $order->discount);
        $this->assertEquals(250000, $order->total_amount);
        $this->assertSame('JAPAN50K', $order->voucher_code);
        $this->assertSame('a@example.com', $order->email);
        $this->assertEquals(7, Product::find($product->id)->quantity);
        $this->assertEmpty(session('cart', []));
        $this->assertNull(session('voucher'));
    }

    public function test_checkout_is_rejected_when_stock_is_not_enough_and_nothing_is_written(): void
    {
        $product = $this->makeProduct(['quantity' => 5]);
        $this->post(route('cart.add', $product->id), ['quantity' => 4]);

        // Có người khác mua mất hàng sau khi khách đã thêm vào giỏ
        $product->update(['quantity' => 2]);

        $this->post(route('checkout.process'), $this->checkoutPayload())
            ->assertRedirect(route('checkout.index'))
            ->assertSessionHas('error');

        $this->assertSame(0, DB::table('orders')->count());
        $this->assertEquals(2, Product::find($product->id)->quantity);
    }

    public function test_tampered_session_price_is_never_used_to_charge_the_customer(): void
    {
        $product = $this->makeProduct(['price' => 100000]);
        $this->post(route('cart.add', $product->id));

        // Giả lập session bị sửa giá xuống 1đ
        $cart = session('cart');
        $cart[$product->id]['price'] = 1;
        $this->withSession(['cart' => $cart]);

        // Lần đặt đầu: hệ thống phát hiện lệch giá, sửa lại giỏ và dừng để khách xem lại
        $this->post(route('checkout.process'), $this->checkoutPayload())
            ->assertRedirect(route('checkout.index'))
            ->assertSessionHas('error');

        $this->assertSame(0, DB::table('orders')->count());
        $this->assertSame(100000, session('cart')[$product->id]['price']);

        // Lần đặt thứ hai: đơn được tạo với giá thật từ DB
        $this->post(route('checkout.process'), $this->checkoutPayload());
        $this->assertEquals(100000, DB::table('orders')->value('total_amount'));
    }

    public function test_checkout_validates_required_fields(): void
    {
        $product = $this->makeProduct();
        $this->post(route('cart.add', $product->id));

        $this->post(route('checkout.process'), ['payment_method' => 'paypal'])
            ->assertSessionHasErrors(['fullname', 'phone', 'address', 'payment_method']);
    }

    public function test_success_page_is_only_visible_to_the_buyer(): void
    {
        $product = $this->makeProduct();
        $this->post(route('cart.add', $product->id));
        $this->post(route('checkout.process'), $this->checkoutPayload());
        $orderId = DB::table('orders')->value('id');

        // Chính phiên vừa đặt xem được
        $this->get(route('checkout.success', $orderId))->assertOk();

        // Người lạ (session mới) không xem được -> 404, không lộ SĐT/địa chỉ
        $this->flushSession();
        $this->get(route('checkout.success', $orderId))->assertNotFound();
    }
}
