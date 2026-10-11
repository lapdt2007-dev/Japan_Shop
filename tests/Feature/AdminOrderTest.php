<?php

namespace Tests\Feature;

use App\Models\Product;
use Illuminate\Support\Facades\DB;

class AdminOrderTest extends ShopTestCase
{
    public function test_guest_and_customer_cannot_access_admin(): void
    {
        $this->get(route('admin.orders.index'))->assertRedirect(route('login'));

        $this->actingAs(\App\Models\User::factory()->create())
            ->get(route('admin.orders.index'))
            ->assertRedirect('/');
    }

    public function test_cancelling_an_order_restocks_exactly_once(): void
    {
        $product = $this->makeProduct(['quantity' => 5]);
        $orderId = $this->makeOrder($product, 2);
        $admin   = $this->makeAdmin();

        $payload = ['order_status' => 'cancelled', 'payment_status' => 'unpaid'];

        $this->actingAs($admin)->patch(route('admin.orders.updateStatus', $orderId), $payload);
        $this->assertEquals(7, Product::find($product->id)->quantity);

        // Gửi lại lệnh hủy -> không được cộng kho lần nữa
        $this->actingAs($admin)->patch(route('admin.orders.updateStatus', $orderId), $payload);
        $this->assertEquals(7, Product::find($product->id)->quantity);
    }

    public function test_cancelled_order_cannot_be_reopened(): void
    {
        $product = $this->makeProduct(['quantity' => 5]);
        $orderId = $this->makeOrder($product, 2, ['order_status' => 'cancelled']);

        $this->actingAs($this->makeAdmin())
            ->patch(route('admin.orders.updateStatus', $orderId), ['order_status' => 'processing', 'payment_status' => 'unpaid'])
            ->assertSessionHas('error');

        $this->assertSame('cancelled', DB::table('orders')->where('id', $orderId)->value('order_status'));
    }

    public function test_invalid_status_values_are_rejected(): void
    {
        $product = $this->makeProduct();
        $orderId = $this->makeOrder($product);

        $this->actingAs($this->makeAdmin())
            ->patch(route('admin.orders.updateStatus', $orderId), ['order_status' => 'hacked', 'payment_status' => 'whatever'])
            ->assertSessionHasErrors(['order_status', 'payment_status']);
    }
}
