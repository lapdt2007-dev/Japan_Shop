<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Product;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        // 1. Lấy tất cả danh mục ra menu
        $categories = Category::all();

        // 2. Khởi tạo Query sản phẩm (CHỈ LẤY SẢN PHẨM ĐANG HIỂN THỊ - status = 1)
        $query = Product::with('category')->where('status', 1);

        // 3. Xử lý Lọc theo Slug Danh mục (khi user bấm vào 1 danh mục)
        if ($request->filled('category')) {
            $query->whereHas('category', function ($q) use ($request) {
                $q->where('slug', $request->category);
            });
        }

        // 4. Xử lý Tìm kiếm từ khóa (khi user gõ ô Search)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('description', 'like', '%' . $search . '%');
            });
        }

        // 5. Phân trang 8 sản phẩm / trang (Sắp xếp theo ID mới nhất)
        // Dùng appends() để giữ lại tham số search/category khi bấm chuyển trang
        $products = $query->latest('id')
                            ->paginate(10)
                            ->appends($request->query());

        // 6. Trả dữ liệu ra view home
        return view('home', compact('categories', 'products'));
    }
}