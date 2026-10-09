<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CheckoutController extends Controller
{
    // 1. Hiển thị trang thanh toán
    public function index()
    {
        $cart = session()->get('cart', []);

        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'Giỏ hàng của bạn đang trống!');
        }

        // Tạm tính tiền hàng
        $totalPrice = array_reduce($cart, function ($total, $item) {
            return $total + ($item['price'] * $item['quantity']);
        }, 0);

        // Lấy thông tin Voucher từ Session
        $voucher = session()->get('voucher');
        $discount = 0;

        if ($voucher) {
            if ($voucher['type'] === 'percent') {
                $discount = ($totalPrice * $voucher['value']) / 100;
            } elseif ($voucher['type'] === 'fixed') {
                $discount = $voucher['value'];
            }
            $discount = min($discount, $totalPrice);
        }

        // Tổng tiền cần thanh toán thực tế
        $finalTotal = max(0, $totalPrice - $discount);

        return view('client.checkout', compact('cart', 'totalPrice', 'discount', 'finalTotal', 'voucher'));
    }

    // 2. Xử lý lưu đơn hàng & trừ tồn kho
    public function process(Request $request)
    {
        $cart = session()->get('cart', []);

        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'Giỏ hàng của bạn đang trống!');
        }

        $request->validate([
            'fullname'       => 'required|string|max:255',
            'phone'          => 'required|string|max:20',
            'email'          => 'nullable|email|max:255',
            'address'        => 'required|string|max:500',
            'payment_method' => 'required|in:cod,bank',
            'note'           => 'nullable|string|max:1000',
        ], [
            'fullname.required'       => 'Vui lòng nhập họ tên người nhận',
            'phone.required'          => 'Vui lòng nhập số điện thoại',
            'address.required'        => 'Vui lòng nhập địa chỉ nhận hàng',
            'payment_method.required' => 'Vui lòng chọn phương thức thanh toán',
        ]);

        // Tính toán lại giá trị đơn hàng
        $totalPrice = array_reduce($cart, function ($total, $item) {
            return $total + ($item['price'] * $item['quantity']);
        }, 0);

        $voucher = session()->get('voucher');
        $discount = 0;

        if ($voucher) {
            if ($voucher['type'] === 'percent') {
                $discount = ($totalPrice * $voucher['value']) / 100;
            } elseif ($voucher['type'] === 'fixed') {
                $discount = $voucher['value'];
            }
            $discount = min($discount, $totalPrice);
        }

        $finalTotal = max(0, $totalPrice - $discount);

        DB::beginTransaction();
        try {
            // --- BƯỚC 1: KIỂM TRA TỒN KHO TRƯỚC KHI TẠO ĐƠN ---
            // (Lưu ý: Thay 'quantity' thành 'stock' nếu tên cột trong bảng products của bro là stock)
            foreach ($cart as $productId => $item) {
                $product = DB::table('products')->where('id', $productId)->lockForUpdate()->first();

                if (!$product) {
                    throw new \Exception("Sản phẩm '{$item['name']}' không còn tồn tại!");
                }

                // Giả sử tên cột số lượng tồn kho trong bảng products là 'quantity'
                if ($product->quantity < $item['quantity']) {
                    throw new \Exception("Sản phẩm '{$item['name']}' chỉ còn {$product->quantity} sản phẩm trong kho, không đủ số lượng bạn đặt!");
                }
            }

            // --- BƯỚC 2: TẠO MÃ & LƯU ĐƠN HÀNG ---
            $orderCode = 'ORD-' . strtoupper(Str::random(8));

            $orderId = DB::table('orders')->insertGetId([
                'order_code'       => $orderCode,
                'user_id'          => auth()->id() ?? null,
                'recipient_name'   => $request->fullname,
                'recipient_phone'  => $request->phone,
                'shipping_address' => $request->address,
                'note'             => $request->note,
                'total_amount'     => $finalTotal,
                'payment_method'   => strtoupper($request->payment_method),
                'payment_status'   => 'unpaid',
                'order_status'     => 'pending',
                'created_at'       => now(),
                'updated_at'       => now(),
            ]);

            // --- BƯỚC 3: LƯU CHI TIẾT ĐƠN HÀNG & TRỪ SỐ LƯỢNG TỒN KHO ---
            foreach ($cart as $productId => $item) {
                // 3.1. Lưu order_items
                DB::table('order_items')->insert([
                    'order_id'     => $orderId,
                    'product_id'   => $productId,
                    'product_name' => $item['name'] ?? 'Sản phẩm',
                    'price'        => $item['price'],
                    'quantity'     => $item['quantity'],
                    'created_at'   => now(),
                    'updated_at'   => now(),
                ]);

                // 3.2. Trừ tồn kho trong bảng products
                DB::table('products')
                    ->where('id', $productId)
                    ->decrement('quantity', $item['quantity']); // Thay 'quantity' thành tên cột tồn kho của bro nếu khác
            }

            DB::commit();

            // Xóa Giỏ hàng & Voucher sau khi hoàn tất
            session()->forget(['cart', 'voucher']);

            return redirect()->route('checkout.success', $orderId)
                            ->with('success', 'Đặt hàng thành công!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Đặt hàng thất bại: ' . $e->getMessage())->withInput();
        }
    }

    // 3. Trang đặt hàng thành công
    public function success($orderId)
    {
        $order = DB::table('orders')->where('id', $orderId)->first();

        if (!$order) {
            return redirect()->route('cart.index');
        }

        return view('client.checkout-success', compact('order'));
    }
}