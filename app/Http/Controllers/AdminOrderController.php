<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminOrderController extends Controller
{
    // 1. Danh sách đơn hàng (Có bộ lọc & phân trang)
    public function index(Request $request)
    {
        $query = DB::table('orders')->orderBy('created_at', 'desc');

        // Lọc theo trạng thái đơn hàng
        if ($request->has('status') && $request->status != '') {
            $query->where('order_status', $request->status);
        }

        // Tìm kiếm theo Mã đơn hàng / Tên / SĐT
        if ($request->has('keyword') && $request->keyword != '') {
            $keyword = trim($request->keyword);
            $query->where(function($q) use ($keyword) {
                $q->where('order_code', 'like', "%{$keyword}%")
                  ->orWhere('recipient_name', 'like', "%{$keyword}%")
                  ->orWhere('recipient_phone', 'like', "%{$keyword}%");
            });
        }

        $orders = $query->paginate(10);

        return view('admin.orders.index', compact('orders'));
    }

    // 2. Chi tiết đơn hàng
    public function show($id)
    {
        $order = DB::table('orders')->where('id', $id)->first();

        if (!$order) {
            return redirect()->route('admin.orders.index')->with('error', 'Không tìm thấy đơn hàng!');
        }

        // Lấy danh sách sản phẩm trong đơn hàng
        $orderItems = DB::table('order_items')
            ->where('order_id', $id)
            ->get();

        return view('admin.orders.show', compact('order', 'orderItems'));
    }

    // 3. Cập nhật trạng thái đơn hàng & Thanh toán
   public function updateStatus(Request $request, $id)
{
    $order = DB::table('orders')->where('id', $id)->first();

    if (!$order) {
        return redirect()->back()->with('error', 'Không tìm thấy đơn hàng!');
    }

    try {
        DB::transaction(function () use ($request, $id, $order) {
            // Nếu đơn hàng chuyển sang trạng thái "cancelled" (Hủy) và trước đó chưa bị hủy
            if ($request->order_status === 'cancelled' && $order->order_status !== 'cancelled') {
                $orderItems = DB::table('order_items')->where('order_id', $id)->get();

                foreach ($orderItems as $item) {
                    // Cộng trả lại số lượng vào kho
                    DB::table('products')
                        ->where('id', $item->product_id)
                        ->increment('quantity', $item->quantity);
                }
            }

            // Cập nhật trạng thái mới
            DB::table('orders')->where('id', $id)->update([
                'order_status'   => $request->order_status,
                'payment_status' => $request->payment_status,
                'updated_at'     => now(),
            ]);
        });

        return redirect()->back()->with('success', 'Cập nhật trạng thái thành công!');
    } catch (\Exception $e) {
        return redirect()->back()->with('error', 'Cập nhật thất bại: ' . $e->getMessage());
    }
}

    
}