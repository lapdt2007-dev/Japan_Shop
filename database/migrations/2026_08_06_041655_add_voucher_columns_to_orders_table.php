<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // Kiểm tra nếu chưa có thì mới thêm vào để tránh lỗi duplicate
            if (!Schema::hasColumn('orders', 'sub_total')) {
                $table->decimal('sub_total', 15, 0)->nullable();
            }
            if (!Schema::hasColumn('orders', 'discount')) {
                $table->decimal('discount', 15, 0)->default(0);
            }
            if (!Schema::hasColumn('orders', 'voucher_code')) {
                $table->string('voucher_code')->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['sub_total', 'discount', 'voucher_code']);
        });
    }
};