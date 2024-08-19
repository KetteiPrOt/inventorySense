<?php

namespace App\Models\Invoices\Movements;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Balance extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = ['amount', 'unitary_price', 'total_price', 'movement_id'];

    public function movement(): BelongsTo
    {
        return $this->belongsTo(Movement::class);
    }
}
