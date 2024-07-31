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
        Schema::create('movement_warehouse', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('amount')->unsigned();
            $table->decimal('unitary_price', 17, 6);
            $table->decimal('total_price', 17, 2);
            // Foreign Keys
            $table->unsignedBigInteger('movement_id');
            $table->foreign('movement_id', 'movement_warehouses')
                ->references('id')
                ->on('movements')
                ->cascadeOnDelete()->cascadeOnUpdate();
            $table->unsignedMediumInteger('warehouse_id');
            $table->foreign('warehouse_id', 'movements_warehouse')
                ->references('id')
                ->on('warehouses');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('movement_warehouse');
    }
};
