<?php

namespace App\Http\Controllers\Invoices\Purchases\Expenses;

use App\Http\Controllers\Controller as BaseController;
use App\Models\Invoices\Movements\Balance;
use App\Models\Invoices\Movements\Movement;
use App\Models\Products\Product;
use Illuminate\Http\Request;

class Controller extends BaseController
{
    public function store(array $inputData): Movement
    {
        $product = Product::find($inputData['product_id']);
        if($product->started_inventory){
            $movement = $this->pushExpense($inputData);
        } else {
            $movement = $this->startInventory($inputData);
            $product->started_inventory = true;
            $product->save();
        }
        return $movement;
    }

    private function startInventory(array $inputData): Movement
    {
        $total_purchase_price = bcmul($inputData['amount'], $inputData['unitary_purchase_price'], 2);
        $inputData['total_purchase_price'] = $total_purchase_price;
        $movement = Movement::create($inputData);
        Balance::create([
            'amount' => $inputData['amount'],
            'unitary_price' => $inputData['unitary_purchase_price'],
            'total_price' => $inputData['total_purchase_price'],
            'movement_id' => $movement->id
        ]);
        return $movement;
    }

    private function pushExpense(array $inputData): Movement
    {
        // Movement
        $total_purchase_price = bcmul($inputData['amount'], $inputData['unitary_purchase_price'], 2);
        $inputData['total_purchase_price'] = $total_purchase_price;
        $movement = Movement::create($inputData);
        // Balance
        $lastBalance = Balance::orderBy('id', 'desc')->first();
        $amount = bcadd($inputData['amount'], "$lastBalance->amount", 0);
        $total_price = bcadd($inputData['total_purchase_price'], $lastBalance->total_price, 2);
        Balance::create([
            'amount' => $amount, // last_amount + current_amount
            'unitary_price' => round(bcdiv($total_price, $amount, 3), 2), // average_weigthed_method (total_price / amount)
            'total_price' => $total_price,// last_total + current_total
            'movement_id' => $movement->id
        ]);
        return $movement;
    }
}
