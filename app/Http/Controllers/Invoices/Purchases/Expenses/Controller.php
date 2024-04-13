<?php

namespace App\Http\Controllers\Invoices\Purchases\Expenses;

use App\Http\Controllers\Controller as BaseController;
use App\Models\Invoices\Movements\Balance;
use App\Models\Invoices\Movements\Movement;
use App\Models\Products\Product;
use App\Models\Products\ProductWarehouse;
use Illuminate\Http\Request;

class Controller extends BaseController
{
    public function store(array $inputData, int $warehouse_id): Movement
    {
        $product = Product::find($inputData['product_id']);
        if($product->started_inventory){
            $movement = $this->pushExpense($inputData, $warehouse_id);
        } else {
            $movement = $this->startInventory($inputData, $warehouse_id);
            $product->started_inventory = true;
            $product->save();
        }
        return $movement;
    }

    private function startInventory(array $inputData, int $warehouse_id): Movement
    {
        $total_purchase_price = bcmul($inputData['amount'], $inputData['unitary_purchase_price'], 2);
        $inputData['total_purchase_price'] = $total_purchase_price;
        $movement = Movement::create($inputData);
        $balance_id = Balance::create([
            'amount' => $inputData['amount'],
            'unitary_price' => $inputData['unitary_purchase_price'],
            'total_price' => $inputData['total_purchase_price'],
            'movement_id' => $movement->id
        ])->id;
        // Store product warehouse existence
        ProductWarehouse::create([
            'product_id' => $inputData['product_id'],
            'warehouse_id' => $warehouse_id,
            'balance_id' => $balance_id,
            'amount' => $inputData['amount']
        ]);
        return $movement;
    }

    private function pushExpense(array $inputData, int $warehouse_id): Movement
    {
        // Movement
        $total_purchase_price = bcmul($inputData['amount'], $inputData['unitary_purchase_price'], 2);
        $inputData['total_purchase_price'] = $total_purchase_price;
        $movement = Movement::create($inputData);
        // Balance
        $lastBalance = Balance::orderBy('id', 'desc')->first();
        $amount = bcadd($inputData['amount'], "$lastBalance->amount", 0);
        $total_price = bcadd($inputData['total_purchase_price'], $lastBalance->total_price, 2);
        $balance_id = Balance::create([
            'amount' => $amount, // last_amount + current_amount
            'unitary_price' => round(bcdiv($total_price, $amount, 3), 2), // average_weigthed_method (total_price / amount)
            'total_price' => $total_price,// last_total + current_total
            'movement_id' => $movement->id
        ])->id;
        // Update product warehouse existence
        $warehouseExistence = ProductWarehouse::where(
            'product_id', $inputData['product_id']
        )->where(
            'warehouse_id', $warehouse_id
        )->first();
        if(is_null($warehouseExistence)){
            ProductWarehouse::create([
                'product_id' => $inputData['product_id'],
                'warehouse_id' => $warehouse_id,
                'balance_id' => $balance_id,
                'amount' => $inputData['amount']
            ]);
        } else {
            $warehouseExistence->balance_id = $balance_id;
            $warehouseExistence->amount = $warehouseExistence->amount + intval($inputData['amount']);
            $warehouseExistence->save();
        }
        return $movement;
    }
}
