<?php

namespace App\Models\Products;

use App\Models\Invoices\Movements\Balance;
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
}
