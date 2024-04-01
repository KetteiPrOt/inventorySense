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
        Schema::create('purchase_invoices', function (Blueprint $table) {
            $table->increments('id');
            $table->string('number', 17)->nullable();
            $table->string('comment', 1000)->nullable();
            $table->date('due_payment_date')->nullable()->default(null);
            $table->tinyInteger('paid')->default(true);
            $table->date('paid_date')->nullable()->default(null);
            $table->timestamps();
            // Foreign Keys
            $table->unsignedBigInteger('user_id')->nullable();
            $table->foreign('user_id', 'purchase_invoice_user')
                ->references('id')
                ->on('users')
                ->nullOnDelete()->cascadeOnUpdate();
            $table->unsignedMediumInteger('warehouse_id')->nullable();
            $table->foreign('warehouse_id', 'purchase_invoice_warehouse')
                ->references('id')
                ->on('warehouses')
                ->nullOnDelete()->cascadeOnUpdate();
            $table->unsignedInteger('provider_id')->nullable();
            $table->foreign('provider_id', 'provider_sale_invoice')
                ->references('id')
                ->on('providers')
                ->nullOnDelete()->cascadeOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchase_invoices');
    }
};
