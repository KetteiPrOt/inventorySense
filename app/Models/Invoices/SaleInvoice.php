<?php

namespace App\Models\Invoices;

use App\Models\Client;
use App\Models\Invoices\Movements\Movement;
use App\Models\User;
use App\Models\Warehouse;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class SaleInvoice extends Model
{
    use HasFactory;

    protected $table = 'sale_invoices';

    protected $fillable = [
        'date',
        'comment',
        'due_payment_date',
        'paid',
        'paid_date',
        'user_id',
        'warehouse_id',
        'client_id'
    ];

    /**
     * The relationships that should always be loaded.
     *
     * @var array
     */
    protected $with = ['user', 'warehouse', 'client'];

    public function warehouse(): BelongsTo
    {
        return $this->belongsTo(Warehouse::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function movements(): MorphMany
    {
        return $this->morphMany(Movement::class, 'invoice');
    }
}
