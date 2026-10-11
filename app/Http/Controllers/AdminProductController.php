<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class AdminProductController extends Controller
{
    // 1. Danh sách sản phẩm
    public function index()
    {
        $products = Product::with('category')->latest()->paginate(10);

        return view('admin.products.index', compact('products'));
    }

    // 2. Form thêm sản phẩm mới
    public function create()
    {
        $categories = Category::all();

        return view('admin.products.create', compact('categories'));
    }

    // 3. Lưu sản phẩm mới
    public function store(Request $request)
    {
        $data = $request->validate($this->rules(), $this->messages());

        $data['slug']      = Str::slug($data['name']) . '-' . Str::lower(Str::random(6)); // slug duy nhất
        $data['thumbnail'] = $request->hasFile('thumbnail')
            ? $request->file('thumbnail')->store('products', 'public')
            : null;

        Product::create($data);

        return redirect()->route('admin.products.index')->with('success', 'Thêm sản phẩm thành công!');
    }

    // 4. Form chỉnh sửa
    public function edit($id)
    {
        $product    = Product::findOrFail($id);
        $categories = Category::all();

        return view('admin.products.edit', compact('product', 'categories'));
    }

    // 5. Cập nhật
    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);
        $data    = $request->validate($this->rules(), $this->messages());

        if ($request->hasFile('thumbnail')) {
            $this->deleteThumbnail($product);
            $data['thumbnail'] = $request->file('thumbnail')->store('products', 'public');
        } else {
            unset($data['thumbnail']); // giữ ảnh cũ
        }

        $data['slug'] = Str::slug($data['name']) . '-' . $product->id;

        $product->update($data);

        return redirect()->route('admin.products.index')->with('success', 'Cập nhật sản phẩm thành công!');
    }

    // 6. Xóa
    public function destroy($id)
    {
        $product = Product::findOrFail($id);

        $this->deleteThumbnail($product);
        $product->delete();

        return redirect()->route('admin.products.index')->with('success', 'Đã xóa sản phẩm!');
    }

    /* ------------------------------------------------------------------ */

    /** Rule dùng chung cho thêm & sửa (trước đây update thiếu rule sale_price < price). */
    private function rules(): array
    {
        return [
            'category_id' => 'required|exists:categories,id',
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string|max:5000',
            'price'       => 'required|numeric|min:0',
            'sale_price'  => 'nullable|numeric|min:0|lt:price',
            'quantity'    => 'required|integer|min:0',
            'status'      => 'required|in:0,1',
            'thumbnail'   => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ];
    }

    private function messages(): array
    {
        return [
            'category_id.required' => 'Vui lòng chọn danh mục sản phẩm',
            'name.required'        => 'Vui lòng nhập tên sản phẩm',
            'price.required'       => 'Vui lòng nhập giá gốc',
            'sale_price.lt'        => 'Giá khuyến mãi phải nhỏ hơn giá gốc',
            'quantity.required'    => 'Vui lòng nhập số lượng',
            'thumbnail.image'      => 'File tải lên phải là hình ảnh',
        ];
    }

    /** Chỉ xoá file nằm trong disk public (ảnh seed dạng URL ngoài sẽ bị bỏ qua). */
    private function deleteThumbnail(Product $product): void
    {
        if ($product->thumbnail && Storage::disk('public')->exists($product->thumbnail)) {
            Storage::disk('public')->delete($product->thumbnail);
        }
    }
}
