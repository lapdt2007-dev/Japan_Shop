<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    // Trang danh sách sản phẩm (Hàng hóa)
    public function index(Request $request)
    {
        $categories = Category::all();

        $products = Product::with('category')
            ->active()
            ->filter($request->only(['category', 'search']))
            ->latest('id')
            ->paginate(12)
            ->appends($request->query());

        return view('client.products.index', compact('products', 'categories'));
    }

    // Trang chi tiết sản phẩm
    public function show($slug)
    {
        // Eager load reviews + user để trang không bị N+1 query khi liệt kê đánh giá
        $product = Product::with(['category', 'images', 'reviews.user'])
            ->active()
            ->where('slug', $slug)
            ->firstOrFail();

        $relatedProducts = Product::where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->active()
            ->latest()
            ->take(4)
            ->get();

        // Chỉ khách đã nhận hàng mới được đánh giá
        $canReview = $product->wasPurchasedBy(auth()->id());

        return view('client.products.show', compact('product', 'relatedProducts', 'canReview'));
    }
}
