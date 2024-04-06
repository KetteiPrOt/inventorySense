<?php

namespace App\Models\Invoices\Movements;

use App\Models\Products\Product;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
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

    public function income(): BelongsTo
    {
        return $this->belongsTo(Income::class);
    }

    public function balance(): HasOne
    {
        return $this->hasOne(Balance::class);
    }
}
