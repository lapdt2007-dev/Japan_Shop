<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('orders', 'email')) {
            Schema::table('orders', function (Blueprint $table) {
                $table->string('email')->nullable()->after('recipient_phone');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('orders', 'email')) {
            Schema::table('orders', function (Blueprint $table) {
                $table->dropColumn('email');
            });
        }
    }
};
