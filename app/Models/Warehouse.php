<?php

namespace App\Models;

use App\Models\Invoices\PurchaseInvoice;
use App\Models\Invoices\SaleInvoice;
use App\Models\Products\Product;
use App\Models\Products\ProductWarehouse;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Warehouse extends Model
{
    use HasFactory;

    public $timestamps = false;

    public function saleInvoices(): HasMany
    {
        return $this->hasMany(SaleInvoice::class);
    }

    public function purchaseInvoices(): HasMany
    {
        return $this->hasMany(PurchaseInvoice::class);
    }

    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'product_warehouse', 'warehouse_id', 'product_id')
            ->using(ProductWarehouse::class)
            ->withPivot(['amount', 'balance_id']);
    }
}
