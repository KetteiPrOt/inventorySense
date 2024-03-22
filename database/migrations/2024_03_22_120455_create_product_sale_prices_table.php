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
        Schema::create('product_sale_prices', function (Blueprint $table) {
            $table->increments('id');
            $table->decimal('price', 6, 2);
            $table->smallInteger('units_number')->unsigned();
            // Foreign Keys
            $table->unsignedInteger('product_id');
            $table->foreign('product_id', 'product_sale_price')
                ->references('id')
                ->on('products');
            // Indexs
            $table->unique(['price', 'units_number', 'product_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_sale_prices');
    }
};
