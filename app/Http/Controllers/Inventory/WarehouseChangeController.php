<?php

namespace App\Http\Controllers\Inventory;

use App\Http\Controllers\Controller;
use App\Http\Requests\Inventory\WarehouseChange\ChangeRequest;
use App\Http\Controllers\Invoices\Purchases\Expenses\StoreController as StoreExpenseController;
use App\Http\Controllers\Invoices\Sales\Incomes\StoreController as StoreIncomeController;
use App\Http\Requests\Inventory\WarehouseChange\SelectProductsRequest;
use App\Models\Invoices\Movements\Type as MovementType;
use App\Models\Invoices\PurchaseInvoice;
use App\Models\Invoices\SaleInvoice;
use App\Models\Warehouse;

class WarehouseChangeController extends Controller
{
    public function selectWarehouses()
    {
        return view('entities.inventory.warehouse-change.select-warehouses');
    }

    public function selectProducts(SelectProductsRequest $request)
    {
        $validated = $request->validated();
        return view('entities.inventory.warehouse-change.select-products', [
            'warehouses' => [
                'from' => Warehouse::find($validated['from_warehouse']),
                'to' => Warehouse::find($validated['to_warehouse']),
            ]
        ]);
    }

    public function change(
        ChangeRequest $request,
        StoreExpenseController $expenseController,
        StoreIncomeController $incomeController
    )
    {
        $validated = $request->validated();
        $this->createSale($validated, $incomeController);
        $this->createPurchase($validated, $expenseController);
        return redirect()->route('warehouse-change.select-products', [
            'from_warehouse' => $validated['from_warehouse'],
            'to_warehouse' => $validated['to_warehouse']
        ])->with('success', true);
    }

    private function createSale($validated, $incomeController)
    {
        $saleInvoice = SaleInvoice::create([
            'date' => date('Y-m-d'),
            'comment' => null,
            'due_payment_date' => null,
            'paid' => true,
            'paid_date' => null,
            'user_id' => auth()->user()->id,
            'warehouse_id' => $validated['from_warehouse'],
            'client_id' => null
        ]);
        foreach($validated['products'] as $key => $product_id){
            $incomeController->store([
                'amount' => $validated['amounts'][$key],
                'unitary_sale_price' => null,
                'product_id' => $product_id,
                'invoice_id' => $saleInvoice->id,
                'invoice_type' => SaleInvoice::class,
                'type_id' => MovementType::warehouseChangeIncome()->id,
            ],
                $validated['from_warehouse']
            );
        }
    }

    private function createPurchase($validated, $expenseController)
    {
        $purchaseInvoice = PurchaseInvoice::create([
            'number'=> null,
            'comment'=> null,
            'due_payment_date' => null,
            'paid' => true,
            'paid_date' => null,
            'user_id' => auth()->user()->id,
            'warehouse_id' => $validated['to_warehouse'],
            'provider_id' => null
        ]);
        foreach($validated['products'] as $key => $product_id){
            $expenseController->store([
                'amount' => $validated['amounts'][$key],
                'unitary_purchase_price' => 0.0,
                'product_id' => $product_id,
                'invoice_id' => $purchaseInvoice->id,
                'invoice_type' => PurchaseInvoice::class,
                'type_id' => MovementType::warehouseChangeExpense()->id,
            ],
                $validated['to_warehouse']
            );
        }
    }
}
