<?php

namespace App\Http\Controllers\Invoices\Purchases;

use App\Http\Controllers\Controller as BaseController;
use App\Http\Requests\Invoices\Purchases\StoreRequest;
use App\Http\Controllers\Invoices\Purchases\Expenses\Controller as ExpenseController;
use App\Http\Requests\Invoices\Purchases\ShowKardexRequest;
use App\Models\Invoices\Movements\Movement;
use App\Models\Invoices\PurchaseInvoice;
use App\Models\Products\Product;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class Controller extends BaseController
{
    public function create()
    {
        return view('entities.invoices.purchases.create');
    }

    public function store(StoreRequest $request, ExpenseController $expenseController)
    {
        $validated = $request->validated();
        $invoice = PurchaseInvoice::create([
            'number' => $validated['number'] ?? null,
            'comment' => $validated['comment'] ?? null,
            'due_payment_date' => $validated['due_payment_date'] ?? null,
            'paid' => isset($validated['paid']) ? true : false,
            'paid_date' => null,
            'user_id' => auth()->user()->id,
            'warehouse_id' => $validated['warehouse'],
            'provider_id' => $validated['provider'] ?? null
        ]);
        foreach($validated['products'] as $key => $productId){
            $expenseController->store([
                'amount' => $validated['amounts'][$key],
                'unitary_purchase_price' => $validated['unitary_purchase_prices'][$key],
                'product_id' => $productId,
                'invoice_id' => $invoice->id,
                'invoice_type' => PurchaseInvoice::class,
                'type_id' => $validated['movement_types'][$key],
            ]);
        }
        // Save the choosed warehouse in session
        $request->session()->put('purchases-selected-warehouse', intval($validated['warehouse']));
        // return redirect()->route('purchases.show', [
        //    'ínvoice' => $invoice->id
        // );
        return back();
    }

    public function queryKardex()
    {
        return view('entities.invoices.purchases.query-kardex');
    }

    public function kardex(ShowKardexRequest $request)
    {
        $validated = $request->validated();
        $movements = 
            Movement::
                join('movement_types', 'movements.type_id', '=', 'movement_types.id')
                ->join('balances', 'balances.movement_id', '=', 'movements.id')
                ->join('products', 'products.id', '=', 'movements.product_id')
                ->selectRaw("
                    movements.id,
                    movements.amount,
                    movements.unitary_purchase_price,
                    movements.total_purchase_price,
                    movement_types.`name` as `type`,
                    movement_types.`category` as `category`,
                    balances.amount as `balance_amount`,
                    balances.unitary_price as `balance_unitary_price`,
                    balances.total_price as `balance_total_price`,
                    products.id as `product_id`,
                    movements.invoice_id,
                    movements.invoice_type
                ")
                ->where('product_id', $validated['product'])
                ->whereHasMorph('invoice', '*', function (Builder $query) use ($validated) {
                    $query->where('created_at',  '<', $validated['date_to'] . ' 23:59:59')
                          ->where('created_at',  '>', $validated['date_from'] . ' 00:00:00');
                })
                ->orderBy('id', 'desc')
                ->paginate(10)->withQueryString();
        $product = Product::find($validated['product']);
        $product->loadTag();
        return view('entities.invoices.purchases.kardex', [
            'movements' => $movements,
            'filters' => [
                'product' => $product,
                'date_from' => $validated['date_from'],
                'date_to' => $validated['date_to']
            ]
        ]);
    }
}
