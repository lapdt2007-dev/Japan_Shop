<?php

namespace App\Exceptions;

use RuntimeException;

/**
 * Lỗi nghiệp vụ có thông điệp thân thiện, được phép hiển thị trực tiếp cho người dùng
 * (hết hàng, voucher không hợp lệ, trạng thái đơn không hợp lệ...).
 */
class ShopException extends RuntimeException
{
}
