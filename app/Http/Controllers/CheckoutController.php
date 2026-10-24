<?php

namespace App\Http\Controllers;

use App\Exceptions\ShopException;
use App\Models\Product;
use App\Services\CartService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class CheckoutController extends Controller
{
    public function __construct(private CartService $cart)
    {
    }

    /** 1. Trang thanh toán */
    public function index()
    {
        $summary = $this->cart->summary();

        if (empty($summary['items'])) {
            return redirect()->route('cart.index')->with('error', 'Giỏ hàng của bạn đang trống!');
        }

        if ($summary['notices']) {
            session()->now('error', implode(' ', $summary['notices']));
        }

        return view('client.checkout', [
            'cart'       => $summary['items'],
            'totalPrice' => $summary['subtotal'],
            'discount'   => $summary['discount'],
            'finalTotal' => $summary['total'],
            'voucher'    => $summary['voucher'],
        ]);
    }

    /** 2. Tạo đơn hàng: kiểm tra lại giá/tồn kho/voucher trong transaction, trừ kho. */
    public function process(Request $request)
    {
        $data = $request->validate([
            'fullname'       => 'required|string|max:255',
            'phone'          => ['required', 'string', 'max:20', 'regex:/^[0-9+\-\s().]{8,20}$/'],
            'email'          => 'nullable|email|max:255',
            'address'        => 'required|string|max:500',
            'payment_method' => 'required|in:cod,bank',
            'note'           => 'nullable|string|max:1000',
        ], [
            'fullname.required'       => 'Vui lòng nhập họ tên người nhận',
            'phone.required'          => 'Vui lòng nhập số điện thoại',
            'phone.regex'             => 'Số điện thoại không hợp lệ',
            'address.required'        => 'Vui lòng nhập địa chỉ nhận hàng',
            'payment_method.required' => 'Vui lòng chọn phương thức thanh toán',
        ]);

        // Đồng bộ giỏ với DB. Nếu có gì thay đổi (giá, tồn kho, voucher) thì dừng lại để khách xem lại,
        // tuyệt đối không âm thầm tính tiền khác với những gì khách đã thấy.
        $summary = $this->cart->summary();

        if (empty($summary['items'])) {
            return redirect()->route('cart.index')->with('error', 'Giỏ hàng của bạn đang trống!');
        }

        if ($summary['notices']) {
            return redirect()->route('checkout.index')
                ->withInput()
                ->with('error', 'Giỏ hàng vừa được cập nhật: ' . implode(' ', $summary['notices']) . ' Vui lòng kiểm tra lại rồi đặt hàng.');
        }

        try {
            $orderId = DB::transaction(fn () => $this->createOrder($data, $summary['items']), 3);
        } catch (ShopException $e) {
            return back()->withInput()->with('error', $e->getMessage());
        } catch (\Throwable $e) {
            report($e); // chi tiết đầy đủ nằm trong storage/logs/laravel.log

            $message = 'Không thể đặt hàng lúc này, vui lòng thử lại sau.';
            if (config('app.debug')) {
                // Chỉ hiện khi APP_DEBUG=true (môi trường phát triển) để dễ tìm nguyên nhân
                $message .= ' [Chi tiết: ' . $e->getMessage() . ']';
            }

            return back()->withInput()->with('error', $message);
        }

        $this->cart->clear();

        // Cho phép chính khách này (kể cả khách vãng lai) xem trang cảm ơn của đơn vừa đặt.
        session(['last_order_id' => $orderId]);

        return redirect()->route('checkout.success', $orderId)->with('success', 'Đặt hàng thành công!');
    }

    /** 3. Trang đặt hàng thành công: chỉ chủ đơn (hoặc phiên vừa đặt, hoặc admin) mới xem được. */
    public function success($orderId)
    {
        $order = DB::table('orders')->where('id', $orderId)->first();

        // Trả 404 thay vì 403 để không lộ việc đơn hàng đó có tồn tại hay không.
        abort_unless($order && $this->canViewOrder($order), 404);

        return view('client.checkout-success', compact('order'));
    }

    /* ------------------------------------------------------------------ */

    /**
     * Chạy bên trong DB::transaction.
     *
     * @throws ShopException
     */
    private function createOrder(array $data, array $items): int
    {
        // Khoá các dòng sản phẩm theo thứ tự id để tránh deadlock khi nhiều khách đặt cùng lúc.
        $products = DB::table('products')
            ->whereIn('id', array_keys($items))
            ->orderBy('id')
            ->lockForUpdate()
            ->get()
            ->keyBy('id');

        $lines    = [];
        $subtotal = 0;

        foreach ($items as $id => $item) {
            $product  = $products->get($id);
            $quantity = (int) $item['quantity'];

            if (!$product || !$product->status) {
                throw new ShopException("Sản phẩm '{$item['name']}' không còn được bán!");
            }

            if ($product->quantity < $quantity) {
                throw new ShopException("Sản phẩm '{$product->name}' chỉ còn {$product->quantity} sản phẩm trong kho, không đủ số lượng bạn đặt!");
            }

            // Giá luôn lấy từ DB tại thời điểm đặt hàng, không tin dữ liệu trong session.
            $price     = Product::resolvePrice($product->price, $product->sale_price);
            $subtotal += $price * $quantity;

            $lines[] = [
                'product_id' => $product->id,
                'name'       => $product->name,
                'price'      => $price,
                'quantity'   => $quantity,
            ];
        }

        $resolved = $this->cart->resolveVoucher($subtotal);

        if ($resolved['notice']) {
            throw new ShopException($resolved['notice'] . ' Vui lòng kiểm tra lại giỏ hàng.');
        }

        $discount = $resolved['discount'];
        $total    = max(0, $subtotal - $discount);

        $order = [
            'order_code'       => $this->generateOrderCode(),
            'user_id'          => auth()->id(),
            'recipient_name'   => $data['fullname'],
            'recipient_phone'  => $data['phone'],
            'shipping_address' => $data['address'],
            'note'             => $data['note'] ?? null,
            'sub_total'        => $subtotal,
            'discount'         => $discount,
            'voucher_code'     => $resolved['voucher']['code'] ?? null,
            'total_amount'     => $total,
            'payment_method'   => strtoupper($data['payment_method']),
            'payment_status'   => 'unpaid',
            'order_status'     => 'pending',
            'created_at'       => now(),
            'updated_at'       => now(),
        ];

        // Cột email do migration mới thêm vào: nếu DB chưa chạy `php artisan migrate` thì bỏ qua
        // để việc đặt hàng vẫn hoạt động (email chỉ được lưu khi cột đã tồn tại).
        if (Schema::hasColumn('orders', 'email')) {
            $order['email'] = $data['email'] ?? null;
        }

        $orderId = DB::table('orders')->insertGetId($order);

        foreach ($lines as $line) {
            DB::table('order_items')->insert([
                'order_id'     => $orderId,
                'product_id'   => $line['product_id'],
                'product_name' => $line['name'],
                'price'        => $line['price'],
                'quantity'     => $line['quantity'],
                'created_at'   => now(),
                'updated_at'   => now(),
            ]);

            $updated = DB::table('products')
                ->where('id', $line['product_id'])
                ->where('quantity', '>=', $line['quantity'])
                ->decrement('quantity', $line['quantity']);

            if (!$updated) {
                throw new ShopException("Sản phẩm '{$line['name']}' vừa hết hàng, vui lòng thử lại!");
            }
        }

        return (int) $orderId;
    }

    private function generateOrderCode(): string
    {
        do {
            $code = 'ORD-' . strtoupper(Str::random(8));
        } while (DB::table('orders')->where('order_code', $code)->exists());

        return $code;
    }

    private function canViewOrder(object $order): bool
    {
        if ((int) session('last_order_id') === (int) $order->id) {
            return true;
        }

        $user = auth()->user();

        if (!$user) {
            return false;
        }

        return $user->isAdmin() || ($order->user_id !== null && (int) $order->user_id === (int) $user->id);
    }
}
