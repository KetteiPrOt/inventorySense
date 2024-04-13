<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('product_warehouse', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('amount');
            // Foreign Keys
            $table->unsignedInteger('product_id');
            $table->foreign('product_id', 'products_warehouses')
                ->references('id')
                ->on('products')
                ->noActionOnDelete()->cascadeOnUpdate();
            $table->unsignedMediumInteger('warehouse_id');
            $table->foreign('warehouse_id', 'warehouses_products')
                ->references('id')
                ->on('warehouses')
                ->noActionOnDelete()->cascadeOnUpdate();
            $table->unsignedBigInteger('balance_id');
            $table->foreign('balance_id', 'product_warehouse_balance')
                ->references('id')
                ->on('balances')
                ->cascadeOnDelete()->cascadeOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_warehouse');
    }
};
