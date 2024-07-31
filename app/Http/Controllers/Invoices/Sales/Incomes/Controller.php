<?php

namespace App\Http\Controllers\Invoices\Sales\Incomes;

use App\Http\Controllers\Controller as BaseController;
use App\Models\Invoices\Movements\Balance;
use App\Models\Invoices\Movements\Income;
use App\Models\Invoices\Movements\Movement;
use App\Models\Invoices\Movements\MovementWarehouse;
use App\Models\Products\Product;
use App\Models\Products\ProductWarehouse;
use App\Models\Products\SalePrice;

class Controller extends BaseController
{
    public function store(array $inputData, int $warehouse_id): Movement
    {
        $product = Product::with('latestBalance')->find($inputData['product_id']);
        $latestBalance = $product->latestBalance;
        // Create Movement
        $inputData['unitary_purchase_price'] = $latestBalance->unitary_price;
        $inputData['total_purchase_price'] = bcmul($inputData['amount'], $latestBalance->unitary_price, 2);
        $movement = Movement::create($inputData);
        // Create Balance
        $balanceData = $this->calculateBalance($inputData, $latestBalance);
        Balance::create([
            'amount' => $balanceData['amount'],
            'total_price' => $balanceData['total_price'],
            'unitary_price' => $balanceData['unitary_price'],
            'movement_id' => $movement->id
        ])->id;
        // Create Income
        $unitary_sale_price = SalePrice::find($inputData['unitary_sale_price'])->price;
        Income::create([
            'unitary_sale_price' => $unitary_sale_price,
            'total_sale_price' => bcmul($inputData['amount'], $unitary_sale_price, 2),
            'movement_id' => $movement->id
        ]);
        // Create Movement in Warehouse with balance data
        $lastWarehouseBalance = MovementWarehouse::lastBalance(
            $inputData['product_id'], $warehouse_id
        );
        $warehouseBalanceData = $this->calculateBalance($inputData, $lastWarehouseBalance);
        Balance::create([
            'amount' => $warehouseBalanceData['amount'],
            'total_price' => $warehouseBalanceData['total_price'],
            'unitary_price' => $warehouseBalanceData['unitary_price'],
            'movement_id' => $movement->id
        ])->id;
        return $movement;
    }

    private function calculateBalance(array $inputData, $lastBalance): array
    {
        $amount = $lastBalance->amount - intval($inputData['amount']);
        $total_price = $amount > 0
            ? bcsub($lastBalance->total_price, $inputData['total_purchase_price'], 2)
            : 0;
        $unitary_price = $amount > 0
            ? round(bcdiv($total_price, "$amount", 3), 2)
            : 0;
        return [
            'amount' => $amount,
            'total_price' => $total_price,
            'unitary_price' => $unitary_price,
        ];
    }
}
