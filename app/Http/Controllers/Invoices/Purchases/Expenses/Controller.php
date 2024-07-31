<?php

namespace App\Http\Controllers\Invoices\Purchases\Expenses;

use App\Http\Controllers\Controller as BaseController;
use App\Models\Invoices\Movements\Balance;
use App\Models\Invoices\Movements\Movement;
use App\Models\Invoices\Movements\MovementWarehouse;
use App\Models\Products\Product;

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
        // Store Balance
        Balance::create([
            'amount' => $inputData['amount'],
            'unitary_price' => $inputData['unitary_purchase_price'],
            'total_price' => $inputData['total_purchase_price'],
            'movement_id' => $movement->id
        ])->id;
        // Store Movement in Warehouse with balance data
        MovementWarehouse::create([
            'amount' => $inputData['amount'],
            'unitary_price' => $inputData['unitary_purchase_price'],
            'total_price' => $inputData['total_purchase_price'],
            'movement_id' => $movement->id,
            'warehouse_id' => $warehouse_id
        ]);
        return $movement;
    }

    private function pushExpense(array $inputData, int $warehouse_id): Movement
    {
        // Movement
        $total_purchase_price = bcmul($inputData['amount'], $inputData['unitary_purchase_price'], 2);
        $inputData['total_purchase_price'] = $total_purchase_price;
        $movement = Movement::create($inputData);
        // Store Balance
        $lastBalance = Product::find($inputData['product_id'])->latestBalance()->first();
        $balanceData = $this->calculateBalance($inputData, $lastBalance);
        Balance::create([
            'amount' => $balanceData['amount'],
            'unitary_price' => $balanceData['unitary_price'],
            'total_price' => $balanceData['total_price'],
            'movement_id' => $movement->id
        ])->id;
        // Store Movement in Warehouse with balance data
        $lastWarehouseBalance = MovementWarehouse::lastBalance(
            $inputData['product_id'], $warehouse_id
        );
        if(is_null($lastWarehouseBalance)){
            // Store as first balance in warehouse
            MovementWarehouse::create([
                'amount' => $inputData['amount'],
                'unitary_price' => $inputData['unitary_purchase_price'],
                'total_price' => $inputData['total_purchase_price'],
                'movement_id' => $movement->id,
                'warehouse_id' => $warehouse_id
            ]);
        } else {
            // Push new balance in warehouse
            $balanceWarehouseData = $this->calculateBalance($inputData, $lastWarehouseBalance);
            MovementWarehouse::create([
                'amount' => $balanceWarehouseData['amount'],
                'unitary_price' => $balanceWarehouseData['unitary_price'],
                'total_price' => $balanceWarehouseData['total_price'],
                'movement_id' => $movement->id,
                'warehouse_id' => $warehouse_id
            ]);
        }
        return $movement;
    }

    private function calculateBalance(array $inputData, object $lastBalance): array
    {
        $amount = bcadd($inputData['amount'], "$lastBalance->amount", 0);
        $total_price = bcadd($inputData['total_purchase_price'], $lastBalance->total_price, 2);
        return [
            'amount' => $amount, // last_amount + current_amount
            'unitary_price' => round(bcdiv($total_price, $amount, 3), 2), // average_weigthed_method (total_price / amount)
            'total_price' => $total_price // last_total + current_total
        ];
    }
}
