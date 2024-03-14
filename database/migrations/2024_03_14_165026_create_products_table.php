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
        Schema::create('products', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 255);
            $table->tinyInteger('started_inventory')->default(false);
            $table->timestamps();
            // Foreign Keys
            $table->unsignedMediumInteger('presentation_id')->nullable();
            $table->foreign('presentation_id', 'product_presentation')
                ->references('id')
                ->on('product_presentations')
                ->nullOnDelete()->cascadeOnUpdate();
            $table->unsignedMediumInteger('type_id')->nullable();
            $table->foreign('type_id', 'product_type')
                ->references('id')
                ->on('product_types')
                ->nullOnDelete()->cascadeOnUpdate();
            // Indexs
            $table->unique(['name', 'type_id', 'presentation_id'], 'unique_product_tag');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
