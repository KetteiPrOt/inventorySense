<?php

namespace App\Models\Invoices\Movements;

use App\Models\Warehouse;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BalanceWarehouse extends Model
{
    use HasFactory;

    protected $table = 'balances_warehouse';

    protected $fillable = [
        'amount', 'unitary_price', 'total_price', 'movement_id', 'warehouse_id'
    ];

    public $timestamps = false;

    public function movement(): BelongsTo
    {
        return $this->belongsTo(Movement::class);
    }

    public function warehouse(): BelongsTo
    {
        return $this->belongsTo(Warehouse::class);
    }
}