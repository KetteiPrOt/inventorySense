<?php

namespace App\Models\Products;

use App\Models\Invoices\Movements\Movement;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

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
}
