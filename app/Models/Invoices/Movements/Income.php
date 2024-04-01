<?php

namespace App\Models\Invoices\Movements;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Income extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = ['unitary_sale_price', 'total_sale_price', 'movement_id'];

    public function movement(): HasOne
    {
        return $this->hasOne(Movement::class);
    }
}
