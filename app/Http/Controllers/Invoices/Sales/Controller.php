<?php

namespace App\Http\Controllers\Invoices\Sales;

use App\Http\Controllers\Controller as BaseController;
use App\Http\Controllers\Invoices\Sales\Incomes\Controller as IncomeController;
use App\Http\Requests\Invoices\Sales\StoreRequest;
use App\Models\Invoices\SaleInvoice;
use Illuminate\Http\Request;

class Controller extends BaseController
{
    public function create()
    {
        return view('entities.invoices.sales.create');
    }

    public function store(StoreRequest $request, IncomeController $incomeController)
    {
        $validated = $request->validated();
        $invoice = SaleInvoice::create([
            'comment' => $validated['comment'] ?? null,
            'due_payment_date' => $validated['due_payment_date'] ?? null,
            'paid' => isset($validated['paid']) ? true : false,
            'paid_date' => null,
            'user_id' => auth()->user()->id,
            'warehouse_id' => $validated['warehouse'],
            'client_id' => $validated['client'] ?? null
        ]);
        foreach($validated['products'] as $key => $productId){
            $incomeController->store([
                'amount' => $validated['amounts'][$key],
                'unitary_sale_price' => $validated['unitary_sale_prices'][$key],
                'product_id' => $productId,
                'invoice_id' => $invoice->id,
                'invoice_type' => SaleInvoice::class,
                'type_id' => $validated['movement_types'][$key],
            ]);
        }
        // Save the choosed warehouse in session
        $request->session()->put('sales-selected-warehouse', intval($validated['warehouse']));
        // return redirect()->route('sales.show', [
        //    'ínvoice' => $invoice->id
        // );
        return back();
    }
}
