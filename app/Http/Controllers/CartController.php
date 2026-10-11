<?php

namespace App\Http\Controllers;

use App\Exceptions\ShopException;
use App\Models\Product;
use App\Services\CartService;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function __construct(private CartService $cart)
    {
    }

    public function index()
    {
        $summary = $this->cart->summary();

        if ($summary['notices']) {
            session()->now('warning', implode(' ', $summary['notices']));
        }

        return view('cart.index', [
            'cart'       => $summary['items'],
            'totalPrice' => $summary['subtotal'],
            'discount'   => $summary['discount'],
            'finalTotal' => $summary['total'],
            'voucher'    => $summary['voucher'],
        ]);
    }

    public function add(Request $request, $id)
    {
        $product  = Product::findOrFail($id);
        $quantity = max(1, min(999, (int) $request->input('quantity', 1)));

        try {
            $result = $this->cart->add($product, $quantity);
        } catch (ShopException $e) {
            return back()->with('error', $e->getMessage());
        }

        // "Mua ngay" -> sang thẳng trang thanh toán
        if ($request->has('buy_now')) {
            return redirect()->route('checkout.index');
        }

        if ($result['capped']) {
            return back()->with('success', "Đã thêm vào giỏ. Số lượng được giới hạn ở {$result['quantity']} theo tồn kho.");
        }

        return back()->with('success', 'Đã thêm sản phẩm vào giỏ hàng!');
    }

    public function update(Request $request, $id)
    {
        try {
            $result = $this->cart->update((int) $id, (int) $request->input('quantity', 1));
        } catch (ShopException $e) {
            return redirect()->route('cart.index')->with('error', $e->getMessage());
        }

        $message = $result['capped']
            ? "Số lượng được giới hạn ở {$result['quantity']} theo tồn kho."
            : 'Đã cập nhật số lượng giỏ hàng!';

        return redirect()->route('cart.index')->with('success', $message);
    }

    public function remove($id)
    {
        $this->cart->remove((int) $id);

        return back()->with('success', 'Đã xóa sản phẩm khỏi giỏ hàng!');
    }

    public function clear()
    {
        $this->cart->clear();

        return back()->with('success', 'Đã xóa toàn bộ giỏ hàng!');
    }

    public function applyVoucher(Request $request)
    {
        $data = $request->validate([
            'voucher_code' => 'required|string|max:50',
        ], [
            'voucher_code.required' => 'Vui lòng nhập mã giảm giá.',
        ]);

        try {
            $this->cart->applyVoucher($data['voucher_code']);
        } catch (ShopException $e) {
            return back()->with('error', $e->getMessage());
        }

        return back()->with('success', 'Áp dụng mã giảm giá thành công!');
    }

    public function removeVoucher()
    {
        $this->cart->removeVoucher();

        return back()->with('success', 'Đã gỡ bỏ mã giảm giá!');
    }
}
