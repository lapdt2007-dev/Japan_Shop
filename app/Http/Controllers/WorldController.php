<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class WorldController extends Controller
{
    public function index(Request $request)
    {
        $categories = Category::all();

        $products = Product::with('category')
            ->active()
            ->filter($request->only(['category', 'search']))
            ->latest('id')
            ->paginate(10)
            ->appends($request->query());

        // Dữ liệu cho các ô giới thiệu phía trên: 12 sản phẩm mới nhất đang bán.
        $promoProducts = Product::with('category')
            ->active()
            ->latest('id')
            ->take(12)
            ->get();

        return view('client.world.index', compact('categories', 'products', 'promoProducts'));
    }
}
