<?php

namespace App\Services;

use App\Exceptions\ShopException;
use Illuminate\Support\Facades\DB;

class OrderService
{
    /**
     * Khách tự hủy đơn của chính mình: chỉ khi đơn còn "pending" và chưa thanh toán.
     * Hoàn lại tồn kho đúng 1 lần (đơn được khoá trong transaction).
     *
     * @throws ShopException
     */
    public function cancelByCustomer(int $orderId, int $userId): void
    {
        DB::transaction(function () use ($orderId, $userId) {
            $order = DB::table('orders')
                ->where('id', $orderId)
                ->where('user_id', $userId)
                ->lockForUpdate()
                ->first();

            if (!$order) {
                throw new ShopException('Không tìm thấy đơn hàng.');
            }

            if ($order->order_status === 'cancelled') {
                throw new ShopException('Đơn hàng này đã được hủy trước đó.');
            }

            if ($order->order_status !== 'pending' || $order->payment_status !== 'unpaid') {
                throw new ShopException('Đơn hàng đã được xử lý hoặc đã thanh toán nên không thể tự hủy. Vui lòng liên hệ shop để được hỗ trợ.');
            }

            $items = DB::table('order_items')->where('order_id', $orderId)->get();

            foreach ($items as $item) {
                if ($item->product_id) {
                    DB::table('products')
                        ->where('id', $item->product_id)
                        ->increment('quantity', $item->quantity);
                }
            }

            DB::table('orders')->where('id', $orderId)->update([
                'order_status' => 'cancelled',
                'updated_at'   => now(),
            ]);
        }, 3);
    }
}