<?php

namespace App\Models\Invoices\Movements;

use App\Models\Products\Product;
use App\Models\Warehouse;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Movement extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'amount',
        'unitary_purchase_price',
        'total_purchase_price',
        'product_id',
        'invoice_id',
        'invoice_type',
        'type_id'
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function invoice(): MorphTo
    {
        return $this->morphTo(__FUNCTION__, 'invoice_type', 'invoice_id');
    }

    public function type(): BelongsTo
    {
        return $this->belongsTo(Type::class);
    }

    public function income(): HasOne
    {
        return $this->hasOne(Income::class);
    }

    public function balance(): HasOne
    {
        return $this->hasOne(Balance::class);
    }

    public function warehouses(): BelongsToMany
    {
        return $this->belongsToMany(Warehouse::class, 'movement_warehouse')
            ->using(MovementWarehouse::class)
            ->withPivot(['amount','unitary_price','total_price']);
    }
}
