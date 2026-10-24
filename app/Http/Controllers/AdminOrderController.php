<?php

namespace App\Http\Controllers;

use App\Exceptions\ShopException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class AdminOrderController extends Controller
{
    public const ORDER_STATUSES   = ['pending', 'processing', 'shipping', 'completed', 'cancelled'];
    public const PAYMENT_STATUSES = ['unpaid', 'paid'];

    // 1. Danh sách đơn hàng (có bộ lọc & phân trang)
    public function index(Request $request)
    {
        $filters = $request->validate([
            'status'  => ['nullable', Rule::in(self::ORDER_STATUSES)],
            'keyword' => 'nullable|string|max:100',
        ]);

        $query = DB::table('orders')->orderByDesc('created_at')->orderByDesc('id');

        if (!empty($filters['status'])) {
            $query->where('order_status', $filters['status']);
        }

        if (!empty($filters['keyword'])) {
            $like = '%' . addcslashes(trim($filters['keyword']), '%_\\') . '%';

            $query->where(function ($q) use ($like) {
                $q->where('order_code', 'like', $like)
                  ->orWhere('recipient_name', 'like', $like)
                  ->orWhere('recipient_phone', 'like', $like);
            });
        }

        $orders = $query->paginate(10)->withQueryString();

        return view('admin.orders.index', compact('orders'));
    }

    // 2. Chi tiết đơn hàng
    public function show($id)
    {
        $order = DB::table('orders')->where('id', $id)->first();

        if (!$order) {
            return redirect()->route('admin.orders.index')->with('error', 'Không tìm thấy đơn hàng!');
        }

        $orderItems = DB::table('order_items')->where('order_id', $id)->get();

        return view('admin.orders.show', compact('order', 'orderItems'));
    }

    // 3. Cập nhật trạng thái đơn hàng & thanh toán
    public function updateStatus(Request $request, $id)
    {
        $data = $request->validate([
            'order_status'   => ['required', Rule::in(self::ORDER_STATUSES)],
            'payment_status' => ['required', Rule::in(self::PAYMENT_STATUSES)],
        ]);

        try {
            DB::transaction(function () use ($data, $id) {
                // Khoá đơn để 2 admin bấm cùng lúc không thể hoàn kho 2 lần.
                $order = DB::table('orders')->where('id', $id)->lockForUpdate()->first();

                if (!$order) {
                    throw new ShopException('Không tìm thấy đơn hàng!');
                }

                $old = $order->order_status;
                $new = $data['order_status'];

                // Đơn đã hủy là trạng thái cuối: kho đã được hoàn, mở lại sẽ làm lệch tồn kho.
                if ($old === 'cancelled' && $new !== 'cancelled') {
                    throw new ShopException('Đơn hàng đã hủy nên không thể mở lại. Hãy để khách đặt đơn mới.');
                }

                if ($old === 'completed' && $new === 'cancelled') {
                    throw new ShopException('Đơn hàng đã hoàn thành nên không thể hủy.');
                }

                // Chuyển sang "cancelled" lần đầu -> hoàn lại tồn kho đúng 1 lần.
                if ($new === 'cancelled' && $old !== 'cancelled') {
                    $items = DB::table('order_items')->where('order_id', $id)->get();

                    foreach ($items as $item) {
                        if ($item->product_id) {
                            DB::table('products')
                                ->where('id', $item->product_id)
                                ->increment('quantity', $item->quantity);
                        }
                    }
                }

                DB::table('orders')->where('id', $id)->update([
                    'order_status'   => $new,
                    'payment_status' => $data['payment_status'],
                    'updated_at'     => now(),
                ]);
            });
        } catch (ShopException $e) {
            return back()->with('error', $e->getMessage());
        }

        return back()->with('success', 'Cập nhật trạng thái thành công!');
    }
}
