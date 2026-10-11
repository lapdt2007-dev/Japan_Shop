<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'name',
        'slug',
        'thumbnail',
        'description',
        'price',
        'sale_price',
        'quantity',
        'status',
    ];

    /* ------------------------------------------------------------------ */
    /*  Quan hệ                                                            */
    /* ------------------------------------------------------------------ */

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function images()
    {
        return $this->hasMany(ProductImage::class);
    }

    public function cartItems()
    {
        return $this->hasMany(CartItem::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    /* ------------------------------------------------------------------ */
    /*  Giá                                                                */
    /* ------------------------------------------------------------------ */

    /**
     * Giá bán thực tế: dùng giá khuyến mãi nếu hợp lệ (> 0 và nhỏ hơn giá gốc).
     * Nhận cả giá trị thô để dùng được với kết quả DB::table() (stdClass).
     */
    public static function resolvePrice($price, $salePrice = null): int
    {
        $price = (int) $price;
        $sale  = $salePrice === null ? 0 : (int) $salePrice;

        return ($sale > 0 && $sale < $price) ? $sale : $price;
    }

    public function getCurrentPriceAttribute(): int
    {
        return static::resolvePrice($this->price, $this->sale_price);
    }

    /* ------------------------------------------------------------------ */
    /*  Scope                                                              */
    /* ------------------------------------------------------------------ */

    /** Chỉ sản phẩm đang mở bán. */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('status', 1);
    }

    /** Lọc theo danh mục (slug) và từ khoá. Dùng chung cho trang chủ / hàng hoá / quốc tế. */
    public function scopeFilter(Builder $query, array $filters): Builder
    {
        $category = $filters['category'] ?? null;
        if (is_string($category) && $category !== '') {
            $query->whereHas('category', fn ($q) => $q->where('slug', $category));
        }

        $search = $filters['search'] ?? null;
        if (is_string($search) && trim($search) !== '') {
            // Escape % và _ để người dùng không tự tạo wildcard
            $like = '%' . addcslashes(trim($search), '%_\\') . '%';

            $query->where(function ($q) use ($like) {
                $q->where('name', 'like', $like)
                  ->orWhere('description', 'like', $like);
            });
        }

        return $query;
    }

    /* ------------------------------------------------------------------ */
    /*  Nghiệp vụ                                                          */
    /* ------------------------------------------------------------------ */

    /** Người dùng đã nhận hàng (đơn completed) sản phẩm này chưa? */
    public function wasPurchasedBy(?int $userId): bool
    {
        if (!$userId) {
            return false;
        }

        return OrderItem::where('product_id', $this->id)
            ->whereHas('order', function ($q) use ($userId) {
                $q->where('user_id', $userId)->where('order_status', 'completed');
            })
            ->exists();
    }
}
