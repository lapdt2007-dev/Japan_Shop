<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index()
    {
        $cart = session()->get('cart', []);
        $totalPrice = 0;
        foreach ($cart as $item) {
            $totalPrice += $item['price'] * $item['quantity'];
        }

        // Tính số tiền giảm giá từ Voucher (nếu có)
    $voucher = session()->get('voucher');
    $discount = 0;

    if ($voucher) {
        if ($voucher['type'] === 'percent') {
            $discount = ($totalPrice * $voucher['value']) / 100;
        } elseif ($voucher['type'] === 'fixed') {
            $discount = $voucher['value'];
        }
        // Số tiền giảm không được vượt quá tổng tiền hàng
        $discount = min($discount, $totalPrice);
    }

    $finalTotal = max(0, $totalPrice - $discount);

        return view('cart.index', compact('cart', 'totalPrice', 'discount', 'finalTotal', 'voucher'));
}
public function applyVoucher(Request $request)
{
    $code = strtoupper(trim($request->input('voucher_code')));

    // Danh sách Voucher mẫu (sau này bro có thể query từ Database)
    $vouchers = [
        'JAPAN10' => [
            'type'      => 'percent', // Giảm theo %
            'value'     => 10,        // Giảm 10%
            'min_order' => 100000,    // Đơn từ 100k
            'name'      => 'Giảm 10% đơn từ 100k'
        ],
        'JAPAN50K' => [
            'type'      => 'fixed',   // Giảm tiền cố định
            'value'     => 50000,     // Giảm 50.000đ
            'min_order' => 300000,    // Đơn từ 300k
            'name'      => 'Giảm 50k đơn từ 300k'
        ],
    ];

    if (!isset($vouchers[$code])) {
        return redirect()->back()->with('error', 'Mã giảm giá không hợp lệ hoặc đã hết hạn!');
    }

    $voucher = $vouchers[$code];

    // Kiểm tra điều kiện đơn hàng tối thiểu
    $cart = session()->get('cart', []);
    $totalPrice = array_sum(array_map(fn($item) => $item['price'] * $item['quantity'], $cart));

    if ($totalPrice < $voucher['min_order']) {
        return redirect()->back()->with('error', 'Đơn hàng tối thiểu ' . number_format($voucher['min_order'], 0, ',', '.') . 'đ để sử dụng mã này!');
    }

    // Lưu voucher vào Session
    session()->put('voucher', [
        'code'  => $code,
        'type'  => $voucher['type'],
        'value' => $voucher['value'],
        'name'  => $voucher['name'],
    ]);

    return redirect()->back()->with('success', 'Áp dụng mã giảm giá thành công!');
}
public function removeVoucher()
{
    session()->forget('voucher');
    return redirect()->back()->with('success', 'Đã gỡ bỏ mã giảm giá!');
}

    public function add(Request $request, $id)
    {
        $product = Product::findOrFail($id);
        $cart = session()->get('cart', []);
        $quantity = max(1, (int) $request->input('quantity', 1));

        $price = ($product->sale_price && $product->sale_price < $product->price) 
            ? $product->sale_price 
            : $product->price;

        if (isset($cart[$id])) {
            $cart[$id]['quantity'] += $quantity;
        } else {
            $cart[$id] = [
                'id'       => $product->id,
                'name'     => $product->name,
                'slug'     => $product->slug ?? '',
                'price'    => $price,
                'image'    => $product->image ?? $product->thumbnail ?? '',
                'quantity' => $quantity,
            ];
        }

        session()->put('cart', $cart);

        // Nếu bấm "Mua ngay" thì chuyển thẳng sang trang thanh toán
        if ($request->has('buy_now')) {
            return redirect()->route('checkout.index');
        }

        return redirect()->back()->with('success', 'Đã thêm sản phẩm vào giỏ hàng!');
    }

public function update(Request $request, $id)
{
    $cart = session()->get('cart', []);

    if (isset($cart[$id])) {
        // Cập nhật số lượng mới (ép kiểu sang số nguyên và tối thiểu là 1)
        $quantity = max(1, (int) $request->quantity);
        $cart[$id]['quantity'] = $quantity;

        session()->put('cart', $cart);
        return redirect()->route('cart.index')->with('success', 'Đã cập nhật số lượng giỏ hàng!');
    }

    return redirect()->route('cart.index')->with('error', 'Sản phẩm không tồn tại trong giỏ hàng.');
}

    public function remove($id)
    {
        $cart = session()->get('cart', []);
        if (isset($cart[$id])) {
            unset($cart[$id]);
            session()->put('cart', $cart);
        }
        return redirect()->back()->with('success', 'Đã xóa sản phẩm khỏi giỏ hàng!');
    }

    public function clear()
    {
        session()->forget('cart');
        return redirect()->back()->with('success', 'Đã xóa toàn bộ giỏ hàng!');
    }
}