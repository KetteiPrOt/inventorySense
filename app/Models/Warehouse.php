<?php

namespace App\Models;

use App\Models\Invoices\Movements\MovementWarehouse;
use App\Models\Invoices\PurchaseInvoice;
use App\Models\Invoices\SaleInvoice;
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

    public function movements(): BelongsToMany
    {
        return $this->belongsToMany(Warehouse::class, 'movement_warehouse')
            ->using(MovementWarehouse::class)
            ->withPivot(['amount','unitary_price','total_price']);;
    }
}
