<?php

namespace App\Models\Products;

use App\Models\Invoices\Movements\Balance;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;

class ProductWarehouse extends Pivot
{
    public $incrementing = true;

    protected $fillable = ['product_id', 'warehouse_id', 'balance_id', 'amount'];

    public $timestamps = false;

    public function balance(): BelongsTo
    {
        return $this->belongsTo(Balance::class);
    }
}
