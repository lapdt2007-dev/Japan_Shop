<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    // 1. Trang danh sách sản phẩm (Hàng hóa)
    public function index(Request $request)
    {
        $categories = Category::all();

        $query = Product::with('category')->where('status', 1);

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
                            ->paginate(12)
                            ->appends($request->query());

        return view('client.products.index', compact('products', 'categories'));
    }

    // 2. Trang chi tiết sản phẩm
    public function show($slug)
    {
        // Lấy chi tiết sản phẩm đang mở bán
        $product = Product::with(['category', 'images'])
            ->where('slug', $slug)
            ->where('status', 1)
            ->firstOrFail();

        // Lấy 4 sản phẩm liên quan cùng danh mục
        $relatedProducts = Product::where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->where('status', 1)
            ->latest()
            ->take(4)
            ->get();

        return view('client.products.show', compact('product', 'relatedProducts'));
    }

    // 3. Hiển thị form chỉnh sửa sản phẩm (Admin)
    public function edit($id)
    {
        $product = Product::findOrFail($id);
        $categories = Category::all();

        // Đảm bảo đường dẫn view khớp với thư mục (ví dụ: admin.products.edit)
        return view('admin.products.edit', compact('product', 'categories'));
    }

    // 4. Xử lý cập nhật sản phẩm vào Database (Admin)
    
    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        // 1. Validate dữ liệu
        $request->validate([
            'name'        => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'price'       => 'required|numeric|min:0',
            'quantity'    => 'required|integer|min:0',
        ]);

        $data = $request->all();

        // 2. Xử lý ảnh thumbnail nếu có thay đổi mới
        if ($request->hasFile('thumbnail')) {
            $data['thumbnail'] = $request->file('thumbnail')->store('products', 'public');
        }

        // 3. Thực hiện update
        $product->update($data);

        // 4. Chuyển hướng về trang danh sách kèm thông báo thành công
        return redirect()->route('admin.products.index')->with('success', 'Cập nhật sản phẩm thành công!');
    }
}