<?php

namespace App\Models\Invoices\Movements;

use Illuminate\Database\Eloquent\Relations\Pivot;

class MovementWarehouse extends Pivot
{
    public $incrementing = true;

    protected $fillable = [
        'amount',
        'unitary_price',
        'total_price',
        'movement_id',
        'warehouse_id'
    ];

    public $timestamps = false;

    public static function lastBalance(int $product_id, int $warehouse_id): MovementWarehouse|null
    {
        return MovementWarehouse::join(
                'movements', 'movements.id', '=', 'movement_warehouse.movement_id'
            )->join(
                'products', 'products.id', '=', 'movements.product_id'
            )->selectRaw("
                movement_warehouse.*,
                movements.id as `movement_id`,
                products.name as `product_name`
            ")->where(
                'movement_warehouse.warehouse_id', $warehouse_id
            )->where(
                'movements.product_id', $product_id
            )->orderBy('id', 'desc')->first();
    }
}
