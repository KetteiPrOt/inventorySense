<?php

namespace App\Models\Products;

use App\Models\Invoices\Movements\Balance;
use App\Models\Invoices\Movements\BalanceWarehouse;
use App\Models\Invoices\Movements\Movement;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;

class Product extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'started_inventory', 'min_stock', 'presentation_id', 'type_id'];

    public function loadTag(): void
    {
        $this->loadMissing(['presentation', 'type']);
        $this->tag = $this->type ? ($this->type->name . ' ') : null;
        $this->tag .= $this->name;
        $this->tag .=
            $this->presentation
            ? ' ' .$this->presentation->content . 'ml'
            : null;
    }

    public static function joinTag(?string $search): object
    {
        $query = static::
            leftJoin('product_types', 'product_types.id', '=', 'products.type_id')
            ->leftJoin('product_presentations', 'product_presentations.id', '=', 'products.presentation_id')
            ->selectRaw("
                products.*,
                CONCAT_WS(' ',
                    `product_types`.`name`,
                    `products`.`name`,
                    CONCAT(`product_presentations`.`content`, 'ml')
                ) as `tag`
            ");
        if(isset($search)){
            $search = mb_strtoupper($search);
            $query->whereRaw("
                    CONCAT_WS(' ',
                        `product_types`.`name`,
                        `products`.`name`,
                        CONCAT(`product_presentations`.`content`, 'ml')
                    ) LIKE ?
                ", ["%$search%"]);
        }
        return $query;
    }

    public function loadWarehouseExistences(int $warehouse_id): void
    {
        $lastBalanceWarehouse = $this->latestBalanceWarehouse($warehouse_id)->first();
        $this->warehouse_existences = $lastBalanceWarehouse->amount;
    }

    public function type(): BelongsTo
    {
        return $this->belongsTo(Type::class);
    }

    public function presentation(): BelongsTo
    {
        return $this->belongsTo(Presentation::class);
    }

    public function salePrices(): HasMany
    {
        return $this->hasMany(SalePrice::class);
    }

    public function movements(): HasMany
    {
        return $this->hasMany(Movement::class);
    }

    public function latestMovement(): HasOne
    {
        return $this->hasOne(Movement::class)->latestOfMany();
    }

    public function balances(): HasManyThrough
    {
        return $this->hasManyThrough(Balance::class, Movement::class);
    }

    public function latestBalance(): HasOneThrough
    {
        return $this->hasOneThrough(Balance::class, Movement::class)
            ->orderBy('id', 'desc');
    }

    public function latestBalanceWarehouse(int $warehouse_id): HasOneThrough
    {
        return $this->hasOneThrough(BalanceWarehouse::class, Movement::class)
            ->whereRaw("balances_warehouse.warehouse_id = $warehouse_id")
            ->orderBy('id', 'desc');
    }
}
