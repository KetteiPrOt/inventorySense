<?php

namespace App\Http\Controllers\Invoices\Sales\Incomes;

use App\Http\Controllers\Controller as BaseController;
use App\Models\Invoices\Movements\Balance;
use App\Models\Invoices\Movements\Income;
use App\Models\Invoices\Movements\Movement;
use App\Models\Products\Product;
use App\Models\Products\SalePrice;
use Illuminate\Http\Request;

class Controller extends BaseController
{
    public function store(array $inputData): Movement
    {
        $product = Product::with('latestBalance')->find($inputData['product_id']);
        $latestBalance = $product->latestBalance;
        $inputData['unitary_purchase_price'] = $latestBalance->unitary_price;
        $inputData['total_purchase_price'] = bcmul($inputData['amount'], $latestBalance->unitary_price, 2);
        $movement = Movement::create($inputData);
        // Create balance
        $amount = $latestBalance->amount - intval($inputData['amount']);
        $total_price = $amount > 0
            ? bcsub($latestBalance->total_price, $inputData['total_purchase_price'], 2)
            : 0;
        $unitary_price = $amount > 0
            ? round(bcdiv($total_price, "$amount", 3), 2)
            : 0;
        Balance::create([
            'amount' => $amount,
            'total_price' => $total_price,
            'unitary_price' => $unitary_price,
            'movement_id' => $movement->id
        ]);
        // Create income
        $unitary_sale_price = SalePrice::find($inputData['unitary_sale_price'])->price;
        Income::create([
            'unitary_sale_price' => $unitary_sale_price,
            'total_sale_price' => bcmul($inputData['amount'], $unitary_sale_price, 2),
            'movement_id' => $movement->id
        ]);
        return $movement;
    }
}
