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
        Schema::create('balances_warehouse', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('amount');
            $table->decimal('unitary_price', 17, 6);
            $table->decimal('total_price', 17, 2);
            // Foreign Keys
            $table->unsignedBigInteger('movement_id');
            $table->foreign('movement_id', 'balance_warehouse_movement')
                ->references('id')
                ->on('movements')
                ->cascadeOnDelete()->cascadeOnUpdate();
            $table->unsignedMediumInteger('warehouse_id');
            $table->foreign('warehouse_id', 'balances_warehouse')
                ->references('id')
                ->on('warehouses')
                ->noActionOnDelete()->cascadeOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('balances_warehouse');
    }
};
