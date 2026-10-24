<?php

namespace Tests\Unit;

use App\Models\Product;
use App\Services\CartService;
use PHPUnit\Framework\TestCase;

/** Test thuần logic, không cần DB / framework. */
class PricingTest extends TestCase
{
    public function test_sale_price_is_used_only_when_valid(): void
    {
        $this->assertSame(80000, Product::resolvePrice(100000, 80000));
        $this->assertSame(100000, Product::resolvePrice(100000, null));
        $this->assertSame(100000, Product::resolvePrice(100000, 0));
        $this->assertSame(100000, Product::resolvePrice(100000, 120000)); // giá KM cao hơn giá gốc -> bỏ qua
        $this->assertSame(80000, Product::resolvePrice('100000', '80000')); // giá trị thô từ DB (chuỗi)
    }

    public function test_discount_calculation(): void
    {
        $service = new CartService();

        $percent = ['type' => 'percent', 'value' => 10];
        $fixed   = ['type' => 'fixed', 'value' => 50000];

        $this->assertSame(15000, $service->calculateDiscount($percent, 150000));
        $this->assertSame(50000, $service->calculateDiscount($fixed, 300000));
        $this->assertSame(30000, $service->calculateDiscount($fixed, 30000)); // không giảm quá tạm tính
        $this->assertSame(0, $service->calculateDiscount($percent, 0));
        $this->assertSame(20000, $service->calculateDiscount($percent + ['max_discount' => 20000], 1000000));
    }
}
