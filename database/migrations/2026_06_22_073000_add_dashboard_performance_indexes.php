<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->index(['status', 'created_at']);
            $table->index(['user_id', 'created_at']);
        });

        Schema::table('order_items', function (Blueprint $table) {
            $table->index(['product_id', 'quantity']);
        });

        Schema::table('products', function (Blueprint $table) {
            $table->index(['stock', 'updated_at']);
            $table->index(['category_id', 'is_active']);
        });

        Schema::table('users', function (Blueprint $table) {
            $table->index(['is_admin', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex(['is_admin', 'created_at']);
        });

        Schema::table('products', function (Blueprint $table) {
            $table->dropIndex(['stock', 'updated_at']);
            $table->dropIndex(['category_id', 'is_active']);
        });

        Schema::table('order_items', function (Blueprint $table) {
            $table->dropIndex(['product_id', 'quantity']);
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->dropIndex(['status', 'created_at']);
            $table->dropIndex(['user_id', 'created_at']);
        });
    }
};
