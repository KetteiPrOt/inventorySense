<?php

namespace App\Http\Controllers\Invoices\Sales;

use App\Http\Controllers\Controller as BaseController;
use App\Http\Controllers\Invoices\Sales\Incomes\Controller as IncomeController;
use App\Http\Requests\Invoices\Sales\ShowCashClosingRequest;
use App\Http\Requests\Invoices\Sales\StoreRequest;
use App\Models\Client;
use App\Models\Invoices\Movements\Movement;
use App\Models\Invoices\SaleInvoice;
use App\Models\Products\Product;
use App\Models\User;
use App\Models\Warehouse;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class Controller extends BaseController
{
    public function queryIndex()
    {
        return view('entities.invoices.sales.query-index');
    }

    public function index(Request $request)
    {
        $authUser = auth()->user();
        if(!is_null($request->get('user'))){
            if(!$authUser->can('see-all-sales')){
                abort(403);
            }
        }
        $today = date('Y-m-d');
        $validator = Validator::make($request->all(), [
            'date_from' => "required|date_format:Y-m-d|before_or_equal:date_to",
            'date_to' => "required|date_format:Y-m-d|before_or_equal:$today",
            'report_type' => 'required|string|min:3|max:13',
            'warehouse' => 'nullable|integer|exists:warehouses,id',
            'user' => 'nullable|integer|exists:users,id',
            'client' => 'nullable|integer|exists:clients,id'
        ], attributes: [
            'date_from' => 'fecha incial',
            'date_to' => 'fecha final',
            'report_type' => 'tipo de reporte',
            'warehouse' => 'bodega',
            'user' => 'usuario',
            'client' => 'cliente'
        ]);
        if($validator->fails()){
            return redirect()->route('sales.query-index')->withErrors($validator)->withInput();
        }
        $validated = $validator->validated();
        $validated['report_type'] = match($validated['report_type']){
            'all' => 0,
            'only-paid' => 1,
            'only-not-paid' => 2,
            default => 0
        };
        $query = SaleInvoice::with('client')
            ->where('created_at',  '<', $validated['date_to'] . ' 23:59:59')
            ->where('created_at',  '>', $validated['date_from'] . ' 00:00:00');
        if(isset($validated['warehouse'])){
            $query = $query->where('warehouse_id', $validated['warehouse']);
        }
        if(isset($validated['user'])){
            $query->where('user_id', $validated['user']);
        } else {
            if(!$authUser->can('see-all-sales')){
                $query->where('user_id', $authUser->id);
            }
        }
        if(isset($validated['client'])){
            $query = $query->where('client_id', $validated['client']);
        }
        if($validated['report_type'] === 1){
            $query->where('paid', true);
        }
        if($validated['report_type'] === 2){
            $query->where('paid', false);
        }
        $invoices = $query
            ->orderBy('id', 'desc')
            ->paginate(15)->withQueryString();
        foreach($invoices as $invoice){
            $invoice->total_price = '0.00';
            foreach($invoice->movements as $movement){
                $invoice->total_price = bcadd($invoice->total_price, $movement->income->total_sale_price, 2);
            }
        }
        return view('entities.invoices.sales.index', [
            'invoices' => $invoices,
            'filters' => [
                'date_from' => $validated['date_from'],
                'date_to' => $validated['date_to'],
                'report_type' => match($validated['report_type']){
                    0 => 'Todas las ventas',
                    1 => 'Ventas pagadas',
                    2 => 'Ventas no pagadas'
                },
                'warehouse' => isset($validated['warehouse']) ? Warehouse::find($validated['warehouse']) : null,
                'user' => isset($validated['user']) ? User::find($validated['user']) : null,
                'client' => isset($validated['client']) ? Client::find($validated['client']) : null
            ]
        ]);
    }
    public function selectWarehouse()
    {
        return view('entities.invoices.sales.select-warehouse');
    }

    public function saveSelectedWarehouse(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'warehouse' => 'required|integer|exists:warehouses,id'
        ], attributes: ['warehouse' => 'bodega']);
        if($validator->fails()){
            return redirect()->route('sales.select-warehouse')->withErrors($validator)->withInput();
        }
        $validated = $validator->validated();
        $request->session()->put('sales-selected-warehouse', intval($validated['warehouse']));
        return redirect()->route('sales.create');
    }

    public function create()
    {
        return 
            is_null(session('sales-selected-warehouse'))
                ? redirect()->route('sales.select-warehouse')
                : view('entities.invoices.sales.create', ['warehouse' => Warehouse::find(session('sales-selected-warehouse'))]);
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
            ], $validated['warehouse']);
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
                } else {
                    $authUser = auth()->user();
                    if(!$authUser->can('see-all-incomes')){
                        $query->where('user_id', $authUser->id);
                    }
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

    public function show(SaleInvoice $invoice)
    {
        if(auth()->user()->id !== $invoice->user_id){
            if(!auth()->user()->can('see-all-sales')){
                abort(403);
            }
        }
        $movements = Movement::
            join('incomes', 'incomes.movement_id', '=', 'movements.id')
            ->join('products', 'products.id', '=', 'movements.product_id')
            ->leftJoin('product_types', 'product_types.id', '=', 'products.type_id')
            ->leftJoin('product_presentations', 'product_presentations.id', '=', 'products.presentation_id')
            ->selectRaw("
                    movements.id,
                    movements.amount,
                    incomes.unitary_sale_price,
                    incomes.total_sale_price,
                    CONCAT_WS(' ',
                        `product_types`.`name`,
                        `products`.`name`,
                        CONCAT(`product_presentations`.`content`, 'ml')
                    ) as `product_tag`
                ")
            ->where('invoice_id', $invoice->id)
            ->orderBy('id', 'asc')
            ->get();
        return view('entities.invoices.sales.show', [
            'invoice' => $invoice,
            'movements' => $movements,
            'total_prices_summation' => $movements->sum('total_sale_price')
        ]);
    }

    public function update(Request $request, SaleInvoice $invoice)
    {
        if(!auth()->user()->can('edit-all-sales')){
            abort(403);
        }
        $today = date('Y-m-d');
        $created_at = date('Y-m-d', strtotime($invoice->created_at));
        $validator = Validator::make($request->all(), [
            'paid_date' => "required|date_format:Y-m-d|before_or_equal:$today|after_or_equal:$created_at"
        ], attributes: ['paid_date' => 'fecha de pago']);
        if($validator->fails()){
            return redirect()->route('sales.show', $invoice->id)->withErrors($validator)->withInput();
        }
        $validated = $validator->validated();
        $invoice->paid = true;
        if($validated['paid_date'] === $created_at){
            $invoice->paid_date = null;
        } else {
            $invoice->paid_date = $validated['paid_date'];
        }
        $invoice->save();
        return redirect()->route('sales.show', $invoice->id);
    }
}
