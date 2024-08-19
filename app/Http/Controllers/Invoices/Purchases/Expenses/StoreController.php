<?php

namespace App\Http\Controllers\Invoices\Purchases\Expenses;

use App\Models\Invoices\Movements\Balance;
use App\Models\Invoices\Movements\BalanceWarehouse;
use App\Models\Invoices\Movements\Movement;
use App\Models\Products\Product;

class StoreController extends Controller
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
        // Movement
        $inputData = $this->calculateMovement($inputData);
        $movement = Movement::create($inputData);
        // Balance
        $balanceData = $this->buildBalance($inputData, $movement->id);
        Balance::create($balanceData);
        // Balance of warehouse
        $balanceData['warehouse_id'] = $warehouse_id;
        BalanceWarehouse::create($balanceData);
        return $movement;
    }

    private function pushExpense(array $inputData, int $warehouse_id): Movement
    {
        // Search the last balances, before create the new movement
        $product = Product::find($inputData['product_id']);
        $lastBalance = $product->latestBalance()->first();
        $lastBalanceWarehouse = $product->latestBalanceWarehouse($warehouse_id)->first();
        // Movement
        $inputData = $this->calculateMovement($inputData);
        $movement = Movement::create($inputData);
        // Balance
        $balanceData = $this->calculateBalance($lastBalance, $inputData, $movement->id);
        Balance::create($balanceData);
        // Balance of warehouse
        $balanceData = is_null($lastBalanceWarehouse)
            ? $this->buildBalance($inputData, $movement->id)
            : $this->calculateBalance($lastBalanceWarehouse, $inputData, $movement->id);
        $balanceData['warehouse_id'] = $warehouse_id;
        BalanceWarehouse::create($balanceData);
        return $movement;
    }

    private function calculateBalance(object $lastBalance, array $inputData, int $movement_id): array
    {
        // Balance
        $balanceData = [];
        $balanceData['amount'] = $this->summation(
            $inputData['amount'],
            "$lastBalance->amount"
        );
        $balanceData['total_price'] = $this->summation(
            $inputData['total_purchase_price'],
            $lastBalance->total_price
        );
        $balanceData['unitary_price'] = round(
            $this->division(
                $balanceData['total_price'], $balanceData['amount'], 3
            ), 2
        );
        $balanceData['movement_id'] = $movement_id;
        return $balanceData;
    }

    private function calculateMovement(array $inputData): array
    {
        $inputData['total_purchase_price'] = $this->multiplication(
            $inputData['amount'],
            $inputData['unitary_purchase_price']
        );
        return $inputData;
    }

    private function buildBalance(array $inputData, int $movement_id): array
    {
        return [
            'amount' => $inputData['amount'],
            'unitary_price' => $inputData['unitary_purchase_price'],
            'total_price' => $inputData['total_purchase_price'],
            'movement_id' => $movement_id
        ];
    }
}
