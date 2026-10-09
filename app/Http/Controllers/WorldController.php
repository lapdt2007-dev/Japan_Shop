<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Product;

class WorldController extends Controller
{
    public function index(Request $request)
    {
        $categories = Category::all();

        $query = Product::with('category')
            ->where('status', 1);

        if ($request->filled('category')) {
            $query->whereHas('category', function ($q) use ($request) {
                $q->where('slug', $request->category);
            });
        }

        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                    ->orWhere('description', 'like', '%' . $search . '%');
            });
        }

        $products = $query->latest('id')
            ->paginate(10)
            ->appends($request->query());

        // Dữ liệu dùng cho các ô giới thiệu phía trên, lấy trực tiếp từ sản phẩm hiện có.
        $promoProducts = Product::with('category')
            ->where('status', 1)
            ->latest('id')
            ->take(12)
            ->get();

        return view('client.world.index', compact(
            'categories',
            'products',
            'promoProducts'
        ));
    }
}
