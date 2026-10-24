<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function store(Request $request, Product $product)
    {
        $data = $request->validate([
            'rating'  => 'required|integer|min:1|max:5',
            'comment' => 'required|string|max:1000',
        ], [
            'rating.required'  => 'Vui lòng chọn số sao đánh giá!',
            'comment.required' => 'Vui lòng nhập nhận xét của bạn!',
        ]);

        $userId = auth()->id();

        if (!$product->wasPurchasedBy($userId)) {
            return back()->with('error', 'Bạn chỉ có thể đánh giá sản phẩm sau khi đơn hàng đã hoàn thành.');
        }

        // Mỗi người chỉ có 1 đánh giá cho mỗi sản phẩm; gửi lại sẽ cập nhật đánh giá cũ.
        Review::updateOrCreate(
            ['user_id' => $userId, 'product_id' => $product->id],
            ['rating' => $data['rating'], 'comment' => $data['comment']]
        );

        return back()->with('success', 'Cảm ơn bạn đã gửi đánh giá sản phẩm!');
    }
}
