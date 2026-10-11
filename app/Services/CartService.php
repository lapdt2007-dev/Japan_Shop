<?php

namespace App\Services;

use App\Exceptions\ShopException;
use App\Models\Product;

/**
 * Quản lý giỏ hàng lưu trong session.
 *
 * Cấu trúc 1 dòng giỏ hàng (key = id sản phẩm):
 *   id, name, slug, price, image, quantity, stock
 *
 * Nguyên tắc: session chỉ là "bản nháp" của khách. Mọi con số quan trọng (giá, tồn kho,
 * điều kiện voucher) đều được đối chiếu lại với DB bằng refresh()/summary() trước khi hiển thị
 * và trước khi tạo đơn.
 */
class CartService
{
    private const CART_KEY    = 'cart';
    private const VOUCHER_KEY = 'voucher';

    /* ------------------------------------------------------------------ */
    /*  Đọc giỏ hàng                                                       */
    /* ------------------------------------------------------------------ */

    public function items(): array
    {
        return session()->get(self::CART_KEY, []);
    }

    /** Tổng số lượng sản phẩm (hiển thị badge giỏ hàng). */
    public function count(): int
    {
        return (int) array_sum(array_column($this->items(), 'quantity'));
    }

    /** Tạm tính (chưa trừ voucher). */
    public function subtotal(?array $items = null): int
    {
        $items ??= $this->items();

        $total = 0;
        foreach ($items as $item) {
            $total += (int) $item['price'] * (int) $item['quantity'];
        }

        return $total;
    }

    /**
     * Đồng bộ giỏ hàng với DB rồi trả về toàn bộ số liệu cần hiển thị.
     *
     * @return array{items:array,subtotal:int,voucher:?array,discount:int,total:int,notices:string[]}
     */
    public function summary(): array
    {
        $notices = $this->refresh();

        $items    = $this->items();
        $subtotal = $this->subtotal($items);

        $resolved = $this->resolveVoucher($subtotal);
        if ($resolved['notice']) {
            $notices[] = $resolved['notice'];
        }

        return [
            'items'    => $items,
            'subtotal' => $subtotal,
            'voucher'  => $resolved['voucher'],
            'discount' => $resolved['discount'],
            'total'    => max(0, $subtotal - $resolved['discount']),
            'notices'  => $notices,
        ];
    }

    /* ------------------------------------------------------------------ */
    /*  Thao tác giỏ hàng                                                  */
    /* ------------------------------------------------------------------ */

    /**
     * Thêm sản phẩm vào giỏ.
     *
     * @return array{quantity:int,capped:bool} số lượng hiện có trong giỏ + có bị cắt theo tồn kho không
     * @throws ShopException
     */
    public function add(Product $product, int $quantity): array
    {
        $this->assertPurchasable($product);

        $cart    = $this->items();
        $current = isset($cart[$product->id]) ? (int) $cart[$product->id]['quantity'] : 0;
        $stock   = (int) $product->quantity;

        if ($current >= $stock) {
            throw new ShopException("Bạn đã có {$current} sản phẩm này trong giỏ, bằng số lượng còn trong kho.");
        }

        $wanted = $current + max(1, $quantity);
        $final  = min($wanted, $stock);

        $cart[$product->id] = $this->makeLine($product, $final);

        session()->put(self::CART_KEY, $cart);

        return ['quantity' => $final, 'capped' => $final < $wanted];
    }

    /**
     * Đặt lại số lượng 1 dòng trong giỏ (tối thiểu 1, tối đa bằng tồn kho).
     *
     * @return array{quantity:int,capped:bool}
     * @throws ShopException
     */
    public function update(int $productId, int $quantity): array
    {
        $cart = $this->items();

        if (!isset($cart[$productId])) {
            throw new ShopException('Sản phẩm không tồn tại trong giỏ hàng.');
        }

        $product = Product::find($productId);

        if (!$product || !$product->status || $product->quantity <= 0) {
            $this->remove($productId);
            throw new ShopException('Sản phẩm này đã hết hàng hoặc ngừng bán nên được gỡ khỏi giỏ.');
        }

        $wanted = max(1, $quantity);
        $final  = min($wanted, (int) $product->quantity);

        $cart[$productId] = $this->makeLine($product, $final);
        session()->put(self::CART_KEY, $cart);

        return ['quantity' => $final, 'capped' => $final < $wanted];
    }

    public function remove(int $productId): void
    {
        $cart = $this->items();
        unset($cart[$productId]);
        session()->put(self::CART_KEY, $cart);
    }

    /** Xoá giỏ hàng + voucher. */
    public function clear(): void
    {
        session()->forget([self::CART_KEY, self::VOUCHER_KEY]);
    }

    /**
     * Đối chiếu từng dòng giỏ với DB: gỡ sản phẩm hết hàng/ngừng bán, cắt số lượng theo tồn kho,
     * cập nhật giá/tên/ảnh mới nhất. Trả về danh sách thông báo cho khách.
     *
     * @return string[]
     */
    public function refresh(): array
    {
        $cart = $this->items();

        if (!$cart) {
            return [];
        }

        $products = Product::whereIn('id', array_keys($cart))->get()->keyBy('id');
        $notices  = [];

        foreach ($cart as $id => $item) {
            $product = $products->get($id);

            if (!$product || !$product->status || $product->quantity <= 0) {
                unset($cart[$id]);
                $notices[] = "“{$item['name']}” đã hết hàng hoặc ngừng bán nên được gỡ khỏi giỏ.";
                continue;
            }

            $quantity = (int) $item['quantity'];

            if ($quantity > $product->quantity) {
                $quantity  = (int) $product->quantity;
                $notices[] = "“{$product->name}” chỉ còn {$product->quantity} sản phẩm, đã điều chỉnh số lượng trong giỏ.";
            }

            if ((int) $item['price'] !== $product->current_price) {
                $notices[] = "Giá “{$product->name}” đã thay đổi, giỏ hàng đã được cập nhật theo giá mới.";
            }

            $cart[$id] = $this->makeLine($product, $quantity);
        }

        session()->put(self::CART_KEY, $cart);

        return $notices;
    }

    /* ------------------------------------------------------------------ */
    /*  Voucher                                                            */
    /* ------------------------------------------------------------------ */

    /** Đọc định nghĩa voucher (hiện lấy từ config/vouchers.php). */
    public function voucherDefinition(string $code): ?array
    {
        $vouchers = config('vouchers', []);

        return $vouchers[strtoupper(trim($code))] ?? null;
    }

    /**
     * Áp mã giảm giá cho giỏ hiện tại.
     *
     * @throws ShopException
     */
    public function applyVoucher(string $code): void
    {
        $code       = strtoupper(trim($code));
        $definition = $this->voucherDefinition($code);

        if (!$definition) {
            throw new ShopException('Mã giảm giá không hợp lệ hoặc đã hết hạn!');
        }

        $this->refresh();
        $subtotal = $this->subtotal();

        if ($subtotal <= 0) {
            throw new ShopException('Giỏ hàng đang trống, hãy thêm sản phẩm trước khi áp mã.');
        }

        if ($subtotal < $definition['min_order']) {
            throw new ShopException(
                'Đơn hàng tối thiểu ' . number_format($definition['min_order'], 0, ',', '.') . 'đ để sử dụng mã này!'
            );
        }

        session()->put(self::VOUCHER_KEY, $this->voucherPayload($code, $definition));
    }

    public function removeVoucher(): void
    {
        session()->forget(self::VOUCHER_KEY);
    }

    /**
     * Kiểm tra voucher đang lưu trong session còn hợp lệ với tạm tính hiện tại không.
     * Nếu không còn hợp lệ (đổi giỏ, xoá bớt hàng...) thì tự gỡ và trả về thông báo.
     *
     * @return array{voucher:?array,discount:int,notice:?string}
     */
    public function resolveVoucher(int $subtotal): array
    {
        $saved = session()->get(self::VOUCHER_KEY);

        if (!$saved) {
            return ['voucher' => null, 'discount' => 0, 'notice' => null];
        }

        $code       = $saved['code'] ?? '';
        $definition = $this->voucherDefinition($code);

        if (!$definition || $subtotal < $definition['min_order']) {
            $this->removeVoucher();

            return [
                'voucher'  => null,
                'discount' => 0,
                'notice'   => "Mã giảm giá {$code} đã được gỡ vì đơn hàng không còn đủ điều kiện áp dụng.",
            ];
        }

        $voucher = $this->voucherPayload($code, $definition);

        return [
            'voucher'  => $voucher,
            'discount' => $this->calculateDiscount($definition, $subtotal),
            'notice'   => null,
        ];
    }

    /** Số tiền giảm, luôn nằm trong khoảng [0, tạm tính]. */
    public function calculateDiscount(array $definition, int $subtotal): int
    {
        if ($definition['type'] === 'percent') {
            $discount = intdiv($subtotal * (int) $definition['value'], 100);

            if (isset($definition['max_discount'])) {
                $discount = min($discount, (int) $definition['max_discount']);
            }
        } else {
            $discount = (int) $definition['value'];
        }

        return max(0, min($discount, $subtotal));
    }

    /* ------------------------------------------------------------------ */
    /*  Nội bộ                                                             */
    /* ------------------------------------------------------------------ */

    /** @throws ShopException */
    private function assertPurchasable(Product $product): void
    {
        if (!$product->status) {
            throw new ShopException('Sản phẩm này hiện không còn được bán.');
        }

        if ($product->quantity <= 0) {
            throw new ShopException('Sản phẩm này đã hết hàng.');
        }
    }

    private function makeLine(Product $product, int $quantity): array
    {
        return [
            'id'       => $product->id,
            'name'     => $product->name,
            'slug'     => $product->slug ?? '',
            'price'    => $product->current_price,
            'image'    => $product->thumbnail ?? '',
            'quantity' => $quantity,
            'stock'    => (int) $product->quantity,
        ];
    }

    private function voucherPayload(string $code, array $definition): array
    {
        return [
            'code'  => $code,
            'name'  => $definition['name'],
            'type'  => $definition['type'],
            'value' => $definition['value'],
        ];
    }
}
