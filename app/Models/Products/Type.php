<?php

namespace App\Models\Products;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Type extends Model
{
    use HasFactory;

    protected $table = 'product_types';

    public $timestamps = false;

    protected $fillabe = ['name', 'active'];

    public static $initialTypes = [
        'WHISKY',
        'VINO',
        'RON',
        'AGUARDIENTE',
        'TEQUILA',
        'COCKTAIL',
        'ESPUMANTE',
        'SANGRIA',
        'VODKA',
        'LICOR',
        '(OTRO TIPO)'
    ];

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }
}
