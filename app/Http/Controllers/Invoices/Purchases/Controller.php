<?php

namespace App\Http\Controllers\Invoices\Purchases;

use App\Http\Controllers\Controller as BaseController;
use App\Http\Requests\Invoices\Purchases\StoreRequest;
use App\Http\Controllers\Invoices\Purchases\Expenses\Controller as ExpenseController;
use App\Models\Invoices\PurchaseInvoice;
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
}
