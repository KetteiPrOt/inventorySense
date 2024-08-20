<?php

namespace App\Http\Controllers\Invoices\Sales\Incomes;

use App\Models\Invoices\Movements\Balance;
use App\Models\Invoices\Movements\BalanceWarehouse;
use App\Models\Invoices\Movements\Income;
use App\Models\Invoices\Movements\Movement;
use App\Models\Products\Product;
use App\Models\Products\SalePrice;

class StoreController extends Controller
{
    public function store(array $inputData, int $warehouse_id): Movement
    {
        // Search the last balances, before create the new movement
        $product = Product::find($inputData['product_id']);
        $latestBalance = $product->latestBalance()->first();
        $latestBalanceWarehouse = $product->latestBalanceWarehouse($warehouse_id)->first();
        // Movement
        $inputData = $this->calculateMovement($inputData, $latestBalance->unitary_price);
        $movement = Movement::create($inputData);
        // Income
        Income::create($this->calculateIncome($inputData, $movement->id));
        // Balance
        Balance::create(
            $this->calculateBalance($inputData, $latestBalance, $movement->id)
        );
        // Balance of warehouse
        $balanceData = $this->calculateBalance($inputData, $latestBalanceWarehouse, $movement->id);
        $balanceData['warehouse_id'] = $warehouse_id;
        BalanceWarehouse::create($balanceData);
        return $movement;
    }

    private function calculateMovement(array $inputData, string $unitary_price): array
    {
        $inputData['unitary_purchase_price'] = $unitary_price;
        $inputData['total_purchase_price'] = $this->multiplication(
            $inputData['amount'],
            $inputData['unitary_purchase_price']
        );
        return $inputData;
    }

    private function calculateBalance(array $inputData, object $latestBalance, int $movement_id): array
    {
        $amount = $this->subtraction(
            "$latestBalance->amount",
            $inputData['amount'],
            0
        );
        if($amount > 0){
            $total_price = $this->subtraction(
                $latestBalance->total_price,
                $inputData['total_purchase_price']
            );
            $unitary_price = round(
                $this->division($total_price, $amount, 3),
                2
            );
        } else {
            $total_price = 0; $unitary_price = 0;
        }
        return [
            'amount' => $amount,
            'total_price' => $total_price,
            'unitary_price' => $unitary_price,
            'movement_id' => $movement_id
        ];
    }

    private function calculateIncome(array $inputData, int $movement_id): array
    {
        $unitary_sale_price = SalePrice::find($inputData['unitary_sale_price'])->price;
        return [
            'unitary_sale_price' => $unitary_sale_price,
            'total_sale_price' => $this->multiplication(
                $inputData['amount'],
                $unitary_sale_price
            ),
            'movement_id' => $movement_id
        ];
    }
}
