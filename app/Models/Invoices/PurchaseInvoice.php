<?php

namespace App\Models\Invoices;

use App\Models\Invoices\Movements\Movement;
use App\Models\Provider;
use App\Models\User;
use App\Models\Warehouse;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class PurchaseInvoice extends Model
{
    use HasFactory;

    protected $table = 'purchase_invoices';

    protected $fillable = [
        'number',
        'comment',
        'due_payment_date',
        'paid',
        'paid_date',
        'user_id',
        'warehouse_id',
        'provider_id'
    ];

    public function warehouse(): BelongsTo
    {
        return $this->belongsTo(Warehouse::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function provider(): BelongsTo
    {
        return $this->belongsTo(Provider::class);
    }

    public function movements(): MorphMany
    {
        return $this->morphMany(Movement::class, 'invoice');
    }
}
