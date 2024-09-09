<?php

namespace App\Models\Invoices\Movements;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Type extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $table = 'movement_types';

    protected $fillable = ['name', 'category'];

    public function movements(): HasMany
    {
        return $this->hasMany(Movement::class);
    }

    public static $initialInventoryName = 'Inventario Inicial';

    public static $purchaseName = 'Compra';

    public static $saleName = 'Venta';

    public static $warehouseChangeExpenseName = 'Entrada a Bodega';

    public static $warehouseChangeIncomeName = 'Salida a Bodega';

    public static function initialInventory(): Type
    {
        return Type::where('name', Type::$initialInventoryName)->first();
    }

    public static function purchase(): Type
    {
        return Type::where('name', Type::$purchaseName)->first();
    }

    public static function sale(): Type
    {
        return Type::where('name', Type::$saleName)->first();
    }

    public static function warehouseChangeExpense(): Type
    {
        return Type::where('name', Type::$warehouseChangeExpenseName)->first();
    }

    public static function warehouseChangeIncome(): Type
    {
        return Type::where('name', Type::$warehouseChangeIncomeName)->first();
    }
}
