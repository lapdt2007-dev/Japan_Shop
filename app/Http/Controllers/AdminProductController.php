<?php

namespace App\Http\Controllers;

use App\Exceptions\ShopException;
use App\Models\Category;
use App\Models\Product;
use App\Services\ImageService;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class AdminProductController extends Controller
{
    private const MAX_GALLERY = 8;

    public function __construct(private ImageService $images)
    {
    }

    // 1. Danh sách sản phẩm
    // 1. Danh sách sản phẩm
public function index(Request $request)
{
    $query = Product::with('category')
        ->withCount('reviews')
        ->withAvg('reviews', 'rating')
        ->latest();

        // Tìm theo tên
        if ($request->filled('keyword')) {
            $query->where('name', 'like', '%' . trim($request->keyword) . '%');
        }

        // Lọc theo tab
        match ($request->filter) {
            'visible' => $query->where('status', 1),
            'hidden'  => $query->where('status', 0),
            'low'     => $query->whereBetween('quantity', [1, 5]),
            'out'     => $query->where('quantity', '<=', 0),
            default   => null,
        };

        $products = $query->paginate(10);

        // Số đếm hiện trên từng tab
        $productCounts = [
            'all'     => Product::count(),
            'visible' => Product::where('status', 1)->count(),
            'hidden'  => Product::where('status', 0)->count(),
            'low'     => Product::whereBetween('quantity', [1, 5])->count(),
            'out'     => Product::where('quantity', '<=', 0)->count(),
        ];

        return view('admin.products.index', compact('products', 'productCounts'));
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
        unset($data['images'], $data['delete_images']); // ảnh phụ xử lý riêng

        $data['slug'] = Str::slug($data['name']) . '-' . Str::lower(Str::random(6));

        try {
            $data['thumbnail'] = $request->hasFile('thumbnail')
                ? $this->saveImage($request->file('thumbnail'), 'products')
                : null;
        } catch (ShopException $e) {
            return back()->withInput()->withErrors(['thumbnail' => $e->getMessage()]);
        }

        $product = Product::create($data);
        $failed  = $this->saveGallery($request, $product);

        return $this->backToIndex('Thêm sản phẩm thành công!', $failed);
    }

    // 4. Form chỉnh sửa
    public function edit($id)
    {
        $product    = Product::with('images')->findOrFail($id);
        $categories = Category::all();

        return view('admin.products.edit', compact('product', 'categories'));
    }

    // 5. Cập nhật
    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);
        $data    = $request->validate($this->rules(), $this->messages());
        unset($data['images'], $data['delete_images']);

        if ($request->hasFile('thumbnail')) {
            // Lưu ảnh mới thành công rồi mới xoá ảnh cũ
            try {
                $newPath = $this->saveImage($request->file('thumbnail'), 'products');
            } catch (ShopException $e) {
                return back()->withInput()->withErrors(['thumbnail' => $e->getMessage()]);
            }

            $this->deleteFile($product->thumbnail);
            $data['thumbnail'] = $newPath;
        } else {
            unset($data['thumbnail']); // giữ ảnh cũ
        }

        $data['slug'] = Str::slug($data['name']) . '-' . $product->id;

        $product->update($data);

        $this->deleteGalleryImages($product, (array) $request->input('delete_images', []));
        $failed = $this->saveGallery($request, $product);

        return $this->backToIndex('Cập nhật sản phẩm thành công!', $failed);
    }

    // 6. Xóa
    public function destroy($id)
    {
        $product = Product::with('images')->findOrFail($id);

        $this->deleteFile($product->thumbnail);
        foreach ($product->images as $image) {
            $this->deleteFile($image->image_path);
        }

        $product->delete(); // bản ghi product_images tự xoá theo (onDelete cascade)

        return redirect()->route('admin.products.index')->with('success', 'Đã xóa sản phẩm!');
    }

    /* ------------------------------------------------------------------ */

    private function rules(): array
    {
        return [
            'category_id'     => 'required|exists:categories,id',
            'name'            => 'required|string|max:255',
            'description'     => 'nullable|string|max:5000',
            'price'           => 'required|numeric|min:0',
            'sale_price'      => 'nullable|numeric|min:0|lt:price',
            'quantity'        => 'required|integer|min:0',
            'status'          => 'required|in:0,1',
            'thumbnail'       => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'images'          => 'nullable|array|max:' . self::MAX_GALLERY,
            'images.*'        => 'image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'delete_images'   => 'nullable|array',
            'delete_images.*' => 'integer',
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
            'thumbnail.mimes'      => 'Ảnh phải có định dạng jpeg, png, jpg, gif hoặc webp',
            'thumbnail.max'        => 'Ảnh đại diện không được lớn hơn 5MB',
            'thumbnail.uploaded'   => 'Tải ảnh lên thất bại. Ảnh có thể vượt giới hạn upload của PHP (upload_max_filesize / post_max_size).',
            'images.max'           => 'Chỉ được tải tối đa ' . self::MAX_GALLERY . ' ảnh phụ mỗi lần',
            'images.*.image'       => 'Mỗi ảnh phụ phải là file hình ảnh',
            'images.*.mimes'       => 'Ảnh phụ phải có định dạng jpeg, png, jpg, gif hoặc webp',
            'images.*.max'         => 'Mỗi ảnh phụ không được lớn hơn 5MB',
            'images.*.uploaded'    => 'Có ảnh phụ tải lên thất bại (vượt giới hạn upload của PHP).',
        ];
    }

    /** @throws ShopException */
    private function saveImage(UploadedFile $file, string $dir): string
    {
        $path = $this->images->store($file, $dir);

        if (!$path) {
            throw new ShopException("Không lưu được ảnh vào storage/app/public/{$dir}. Hãy kiểm tra thư mục storage có quyền ghi.");
        }

        return $path;
    }

    /** Lưu ảnh phụ, tổng tối đa MAX_GALLERY ảnh/sản phẩm. Trả về số ảnh không lưu được. */
    private function saveGallery(Request $request, Product $product): int
    {
        $files = $request->file('images');

        if (!is_array($files)) {
            return 0;
        }

        $room   = max(0, self::MAX_GALLERY - $product->images()->count());
        $failed = max(0, count($files) - $room);

        foreach (array_slice($files, 0, $room) as $file) {
            $path = $this->images->store($file, 'products/gallery');

            if ($path) {
                $product->images()->create(['image_path' => $path]);
            } else {
                $failed++;
            }
        }

        return $failed;
    }

    /** Chỉ xoá ảnh thuộc đúng sản phẩm này (lọc qua quan hệ), không xoá được ảnh của sản phẩm khác. */
    private function deleteGalleryImages(Product $product, array $ids): void
    {
        $ids = array_filter(array_map('intval', $ids));

        if (!$ids) {
            return;
        }

        $product->images()->whereIn('id', $ids)->get()->each(function ($image) {
            $this->deleteFile($image->image_path);
            $image->delete();
        });
    }

    /** Chỉ xoá file nằm trong disk public (ảnh seed dạng URL ngoài sẽ bị bỏ qua). */
    private function deleteFile(?string $path): void
    {
        if ($path && !filter_var($path, FILTER_VALIDATE_URL) && Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }
    }

    private function backToIndex(string $message, int $failedImages = 0)
    {
        $redirect = redirect()->route('admin.products.index')->with('success', $message);

        if ($failedImages > 0) {
            $redirect->with('warning', "Có {$failedImages} ảnh phụ không được lưu (vượt giới hạn " . self::MAX_GALLERY . ' ảnh hoặc lỗi ghi file).');
        }

        return $redirect;
    }
}