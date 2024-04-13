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
        Schema::create('sale_invoices', function (Blueprint $table) {
            $table->increments('id');
            $table->string('comment', 1000)->nullable();
            $table->date('due_payment_date')->nullable()->default(null);
            $table->tinyInteger('paid')->default(true);
            $table->date('paid_date')->nullable()->default(null);
            $table->timestamps();
            // Foreign Keys
            $table->unsignedBigInteger('user_id')->nullable();
            $table->foreign('user_id', 'sale_invoice_user')
                ->references('id')
                ->on('users')
                ->nullOnDelete()->cascadeOnUpdate();
            $table->unsignedMediumInteger('warehouse_id');
            $table->foreign('warehouse_id', 'sale_invoice_warehouse')
                ->references('id')
                ->on('warehouses')
                ->noActionOnDelete()->cascadeOnUpdate();
            $table->unsignedInteger('client_id')->nullable();
            $table->foreign('client_id', 'client_sale_invoice')
                ->references('id')
                ->on('clients')
                ->nullOnDelete()->cascadeOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sale_invoices');
    }
};
