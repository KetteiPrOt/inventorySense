<?php

namespace App\Models\Invoices\Movements;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Income extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = ['unitary_sale_price', 'total_sale_price', 'movement_id'];

    public function movement(): BelongsTo
    {
        return $this->belongsTo(Movement::class);
    }
}
