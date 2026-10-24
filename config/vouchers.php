<?php

/*
|--------------------------------------------------------------------------
| Danh sách voucher
|--------------------------------------------------------------------------
| type      : percent (giảm theo %) | fixed (giảm số tiền cố định, VNĐ)
| value     : 10 (nghĩa là 10%) hoặc 50000 (nghĩa là 50.000đ)
| min_order : giá trị tạm tính tối thiểu để được áp dụng
| max_discount (tuỳ chọn): trần giảm giá cho voucher kiểu percent
|
| Muốn quản lý voucher trong DB thì chỉ cần thay phần đọc trong
| App\Services\CartService::voucherDefinition().
*/
return [
    'JAPAN10' => [
        'name'      => 'Giảm 10% đơn từ 100k',
        'type'      => 'percent',
        'value'     => 10,
        'min_order' => 100000,
    ],
    'JAPAN50K' => [
        'name'      => 'Giảm 50k đơn từ 300k',
        'type'      => 'fixed',
        'value'     => 50000,
        'min_order' => 300000,
    ],
];
