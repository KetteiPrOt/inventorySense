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
        Schema::create('movements', function (Blueprint $table) {
            $table->id();
            $table->smallInteger('amount')->unsigned();
            $table->decimal('unitary_purchase_price', 8, 2);
            $table->decimal('total_purchase_price', 13, 2);
            // Foreign Keys
            $table->unsignedInteger('product_id');
            $table->foreign('product_id', 'movement_product')
                ->references('id')
                ->on('products')
                ->cascadeOnDelete()->cascadeOnUpdate();
            $table->unsignedSmallInteger('type_id');
            $table->foreign('type_id', 'movement_type')
                ->references('id')
                ->on('movement_types')
                ->restrictOnDelete()->cascadeOnUpdate();
            $table->unsignedBigInteger('balance_id');
            $table->foreign('balance_id', 'balance_movement')
                ->references('id')
                ->on('balances')
                ->restrictOnDelete()->cascadeOnUpdate();
            // Polimorphic relationships
            $table->unsignedInteger('invoice_id');
            $table->string('invoice_type', 255);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('movements');
    }
};
