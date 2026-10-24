<?php

namespace Tests\Feature;

class CartTest extends ShopTestCase
{
    public function test_add_to_cart_is_capped_by_stock(): void
    {
        $product = $this->makeProduct(['quantity' => 3]);

        $this->post(route('cart.add', $product->id), ['quantity' => 10]);

        $this->assertSame(3, session('cart')[$product->id]['quantity']);
    }

    public function test_cannot_add_out_of_stock_or_inactive_product(): void
    {
        $soldOut  = $this->makeProduct(['quantity' => 0]);
        $inactive = $this->makeProduct(['status' => 0]);

        $this->post(route('cart.add', $soldOut->id))->assertSessionHas('error');
        $this->post(route('cart.add', $inactive->id))->assertSessionHas('error');

        $this->assertEmpty(session('cart', []));
    }

    public function test_update_quantity_is_clamped_between_one_and_stock(): void
    {
        $product = $this->makeProduct(['quantity' => 5]);
        $this->post(route('cart.add', $product->id));

        $this->patch(route('cart.update', $product->id), ['quantity' => 99]);
        $this->assertSame(5, session('cart')[$product->id]['quantity']);

        $this->patch(route('cart.update', $product->id), ['quantity' => -4]);
        $this->assertSame(1, session('cart')[$product->id]['quantity']);
    }

    public function test_cart_uses_sale_price_and_refreshes_when_price_changes(): void
    {
        $product = $this->makeProduct(['price' => 200000, 'sale_price' => 150000]);
        $this->post(route('cart.add', $product->id));
        $this->assertSame(150000, session('cart')[$product->id]['price']);

        $product->update(['sale_price' => 120000]);

        $this->get(route('cart.index'))->assertOk();
        $this->assertSame(120000, session('cart')[$product->id]['price']);
    }

    public function test_product_that_becomes_unavailable_is_removed_when_viewing_cart(): void
    {
        $product = $this->makeProduct();
        $this->post(route('cart.add', $product->id));

        $product->update(['status' => 0]);

        $this->get(route('cart.index'))->assertOk();
        $this->assertEmpty(session('cart'));
    }

    public function test_voucher_is_applied_and_validates_minimum_order(): void
    {
        $cheap = $this->makeProduct(['price' => 50000]);
        $this->post(route('cart.add', $cheap->id));

        $this->post(route('cart.voucher.apply'), ['voucher_code' => 'JAPAN10'])->assertSessionHas('error');
        $this->post(route('cart.voucher.apply'), ['voucher_code' => 'KHONGTONTAI'])->assertSessionHas('error');
        $this->assertNull(session('voucher'));

        $this->post(route('cart.add', $cheap->id), ['quantity' => 2]); // 150k
        $this->post(route('cart.voucher.apply'), ['voucher_code' => 'japan10'])->assertSessionHas('success');
        $this->assertSame('JAPAN10', session('voucher.code'));
    }

    public function test_voucher_is_dropped_when_cart_no_longer_meets_minimum(): void
    {
        $product = $this->makeProduct(['price' => 100000]);
        $this->post(route('cart.add', $product->id), ['quantity' => 3]); // 300k
        $this->post(route('cart.voucher.apply'), ['voucher_code' => 'JAPAN50K'])->assertSessionHas('success');

        // Khách giảm giỏ xuống 100k rồi cố giữ lại mã giảm 50k
        $this->patch(route('cart.update', $product->id), ['quantity' => 1]);
        $this->get(route('cart.index'))->assertOk();

        $this->assertNull(session('voucher'));
    }
}
