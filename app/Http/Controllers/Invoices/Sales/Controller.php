<?php

namespace App\Http\Controllers\Invoices\Sales;

use App\Http\Controllers\Controller as BaseController;
use App\Http\Controllers\Invoices\Sales\Incomes\Controller as IncomeController;
use App\Http\Requests\Invoices\Sales\ShowCashClosingRequest;
use App\Http\Requests\Invoices\Sales\StoreRequest;
use App\Models\Invoices\Movements\Movement;
use App\Models\Invoices\SaleInvoice;
use App\Models\Products\Product;
use App\Models\User;
use App\Models\Warehouse;
use Illuminate\Database\Eloquent\Builder;
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

    public function queryCashClosing()
    {
        return view('entities.invoices.sales.query-cash-closing');
    }

    public function cashClosing(ShowCashClosingRequest $request)
    {
        $validated = $request->validated();
        $query = Movement::with('invoice')
            ->join('movement_types', 'movements.type_id', '=', 'movement_types.id')
            ->join('incomes', 'incomes.movement_id', '=', 'movements.id')
            ->join('products', 'products.id', '=', 'movements.product_id');
        if(!isset($validated['product'])){
            $query = $query
                ->leftJoin('product_types', 'product_types.id', '=', 'products.type_id')
                ->leftJoin('product_presentations', 'product_presentations.id', '=', 'products.presentation_id');
            $selectProductTag = "
                CONCAT_WS(' ',
                    `product_types`.`name`,
                    `products`.`name`,
                    CONCAT(`product_presentations`.`content`, 'ml')
                ) as `product_tag`,
            ";
        } else {
            $selectProductTag = null;
        }
        $query =  $query->selectRaw("
                    movements.id,
                    movements.amount,
                    incomes.unitary_sale_price,
                    incomes.total_sale_price,
                    movement_types.`name` as `type`,
                    movement_types.`category` as `category`,
                    products.id as `product_id`,
                    $selectProductTag
                    movements.invoice_id,
                    movements.invoice_type
                ")
            ->where('category', 'i');

        if(isset($validated['product'])){
            $query = $query->where('product_id', $validated['product']);
        }

        $movements = $query->whereHasMorph('invoice', [SaleInvoice::class], function (Builder $query) use ($validated) {
                if(isset($validated['warehouse'])){
                    $query->where('warehouse_id', $validated['warehouse']);
                }
                if(isset($validated['user'])){
                    $query->where('user_id', $validated['user']);
                }
                $query->where('paid', true)
                      ->where('created_at',  '<', $validated['date_to'] . ' 23:59:59')
                      ->where('created_at',  '>', $validated['date_from'] . ' 00:00:00');
            })
            ->orderBy('id', 'desc')
            ->get();
        $total_prices_summation = $movements->sum('total_sale_price');
        $product = isset($validated['product'])
            ? Product::find($validated['product'])
            : null;
        $product?->loadTag();
        $movements = $this->paginate(
            $movements, 10, $validated['page'] ?? 1, $request->url()
        )->withQueryString();
        return view('entities.invoices.sales.cash-closing', [
            'movements' => $movements,
            'total_prices_summation' => $total_prices_summation,
            'filters' => [
                'warehouse' => isset($validated['warehouse']) ? Warehouse::find($validated['warehouse']) : null,
                'user' => isset($validated['user']) ? User::find($validated['user']) : null,
                'product' => $product,
                'date_from' => $validated['date_from'],
                'date_to' => $validated['date_to']
            ]
        ]);
    }
}
