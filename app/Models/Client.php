<?php

namespace App\Models;

use App\Models\Invoices\SaleInvoice;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Client extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'phone',
        'email',
        'ruc',
        'address',
        'identity_card'
    ];

    public function saleInvoices(): HasMany
    {
        return $this->hasMany(SaleInvoice::class);
    }
}
