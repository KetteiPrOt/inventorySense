<?php

namespace App\Models\Products;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Presentation extends Model
{
    use HasFactory;

    protected $table = 'product_presentations';

    public $timestamps = false;

    protected $fillabe = ['content'];

    public static $initialPresentations = [200, 375, 750, 1000, 1500, 600, 700];

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }
}
