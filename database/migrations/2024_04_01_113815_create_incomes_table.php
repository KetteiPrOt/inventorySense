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
        Schema::create('incomes', function (Blueprint $table) {
            $table->id();
            $table->decimal('unitary_sale_price', 8, 2);
            $table->decimal('total_sale_price', 8, 2);
            // Foreign Keys
            $table->unsignedBigInteger('movement_id');
            $table->foreign('movement_id', 'income_movement')
                ->references('id')
                ->on('movements')
                ->cascadeOnDelete()->cascadeOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('incomes');
    }
};
