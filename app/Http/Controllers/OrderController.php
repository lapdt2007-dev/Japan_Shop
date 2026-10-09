<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Review;

class OrderController extends Controller
{
    // Trang "Mua" - Lịch sử đơn hàng của khách, có tab trạng thái + bộ lọc
    public function index(Request $request)
    {
        $tab = $request->get('tab', 'all');

        $query = Order::where('user_id', auth()->id())->with('items.product');

        // ===== Lọc theo TAB trạng thái =====
        switch ($tab) {
            case 'unpaid': // Chờ thanh toán
                $query->where('payment_status', 'unpaid');
                break;
            case 'processing': // Chờ vận chuyển
                $query->where('order_status', 'processing');
                break;
            case 'shipping': // Chờ giao hàng
                $query->where('order_status', 'shipping');
                break;
            case 'cancelled': // Yêu cầu hoàn tiền / Đã hủy
                $query->where('order_status', 'cancelled');
                break;
            case 'not_reviewed': // Chưa đánh giá (đơn đã hoàn thành nhưng còn sp chưa review)
            case 'reviewed': // Đã đánh giá
                $query->where('order_status', 'completed');
                break;
            case 'all':
            default:
                // không lọc gì thêm
                break;
        }

        // ===== Bộ lọc tìm kiếm nâng cao =====
        if ($request->filled('keyword')) {
            $keyword = $request->keyword;
            $query->where(function ($q) use ($keyword) {
                $q->where('order_code', 'like', '%' . $keyword . '%')
                  ->orWhereHas('items.product', function ($q2) use ($keyword) {
                      $q2->where('name', 'like', '%' . $keyword . '%');
                  });
            });
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        if ($request->filled('payment_method') && $request->payment_method !== 'all') {
            $query->where('payment_method', $request->payment_method);
        }

        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('order_status', $request->status);
        }

        if ($request->filled('price_min')) {
            $query->where('total_amount', '>=', (float) $request->price_min);
        }

        if ($request->filled('price_max')) {
            $query->where('total_amount', '<=', (float) $request->price_max);
        }

        $orders = $query->latest()->get();

        // Lấy danh sách product_id mà user đã đánh giá, để biết đơn nào "đã/chưa đánh giá"
        $reviewedProductIds = Review::where('user_id', auth()->id())->pluck('product_id')->toArray();

        // Với 2 tab đặc biệt, lọc thêm theo việc đã review đủ tất cả sản phẩm trong đơn chưa
        if (in_array($tab, ['not_reviewed', 'reviewed'])) {
            $orders = $orders->filter(function ($order) use ($reviewedProductIds, $tab) {
                $productIds = $order->items->pluck('product_id')->toArray();
                $allReviewed = count(array_diff($productIds, $reviewedProductIds)) === 0;
                return $tab === 'reviewed' ? $allReviewed : !$allReviewed;
            })->values();
        }

        return view('client.orders.index', compact('orders', 'tab', 'reviewedProductIds'));
    }
}