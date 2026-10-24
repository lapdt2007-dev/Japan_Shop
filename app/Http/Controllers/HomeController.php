<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $categories = Category::all();

        // Chỉ sản phẩm đang mở bán; lọc danh mục + tìm kiếm dùng chung qua scope của Product.
        $products = Product::with('category')
            ->active()
            ->filter($request->only(['category', 'search']))
            ->latest('id')
            ->paginate(10)
            ->appends($request->query());

        return view('home', compact('categories', 'products'));
    }
}
