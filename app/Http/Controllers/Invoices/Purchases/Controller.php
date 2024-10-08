<?php

namespace App\Http\Controllers\Invoices\Purchases;

use App\Http\Controllers\Controller as BaseController;
use App\Http\Requests\Invoices\Purchases\StoreRequest;
use App\Http\Controllers\Invoices\Purchases\Expenses\StoreController as StoreExpenseController;
use App\Models\Invoices\Movements\Movement;
use App\Models\Invoices\PurchaseInvoice;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class Controller extends BaseController
{
    public function queryIndex()
    {
        return view('entities.invoices.purchases.query-index');
    }

    public function index(Request $request)
    {
        $today = date('Y-m-d');
        $validator = Validator::make($request->all(), [
            'date_from' => "required|date_format:Y-m-d|before_or_equal:date_to",
            'date_to' => "required|date_format:Y-m-d|before_or_equal:$today",
            'report_type' => 'required|string|min:3|max:13'
        ], attributes: [
            'date_from' => 'fecha incial',
            'date_to' => 'fecha final',
            'report_type' => 'tipo de reporte'
        ]);
        if($validator->fails()){
            return redirect()->route('purchases.query-index')->withErrors($validator)->withInput();
        }
        $validated = $validator->validated();
        $validated['report_type'] = match($validated['report_type']){
            'all' => 0,
            'only-paid' => 1,
            'only-not-paid' => 2,
            default => 0
        };
        $query = PurchaseInvoice::with('provider')
            ->where('created_at',  '<', $validated['date_to'] . ' 23:59:59')
            ->where('created_at',  '>', $validated['date_from'] . ' 00:00:00');
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
                $invoice->total_price = bcadd($invoice->total_price, $movement->total_purchase_price, 2);
            }
        }
        return view('entities.invoices.purchases.index', [
            'invoices' => $invoices,
            'filters' => [
                'date_from' => $validated['date_from'],
                'date_to' => $validated['date_to'],
                'report_type' => match($validated['report_type']){
                    0 => 'Todas las compras',
                    1 => 'Compras pagadas',
                    2 => 'Compras no pagadas'
                }
            ]
        ]);
    }

    public function create()
    {
        return view('entities.invoices.purchases.create');
    }

    public function store(StoreRequest $request, StoreExpenseController $expenseController)
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
            ], $validated['warehouse']);
        }
        // Save the choosed warehouse in session
        $request->session()->put('purchases-selected-warehouse', intval($validated['warehouse']));
        // return redirect()->route('purchases.show', [
        //    'ínvoice' => $invoice->id
        // );
        return back();
    }

    public function show(PurchaseInvoice $invoice)
    {
        $this->authorize();
        $movements = Movement::
            join('products', 'products.id', '=', 'movements.product_id')
            ->leftJoin('product_types', 'product_types.id', '=', 'products.type_id')
            ->leftJoin('product_presentations', 'product_presentations.id', '=', 'products.presentation_id')
            ->selectRaw("
                    movements.id,
                    movements.amount,
                    movements.unitary_purchase_price,
                    movements.total_purchase_price,
                    CONCAT_WS(' ',
                        `product_types`.`name`,
                        `products`.`name`,
                        CONCAT(`product_presentations`.`content`, 'ml')
                    ) as `product_tag`
                ")
            ->where('invoice_id', $invoice->id)
            ->where('invoice_type', PurchaseInvoice::class)
            ->orderBy('id', 'asc')
            ->get();
        return view('entities.invoices.purchases.show', [
            'invoice' => $invoice,
            'movements' => $movements,
            'total_prices_summation' => $movements->sum('total_purchase_price')
        ]);
    }

    public function update(Request $request, PurchaseInvoice $invoice)
    {
        $this->authorize();
        $today = date('Y-m-d');
        $created_at = date('Y-m-d', strtotime($invoice->created_at));
        $validator = Validator::make($request->all(), [
            'paid_date' => "required|date_format:Y-m-d|before_or_equal:$today|after_or_equal:$created_at"
        ], attributes: ['paid_date' => 'fecha de pago']);
        if($validator->fails()){
            return redirect()->route('purchases.show', $invoice->id)->withErrors($validator)->withInput();
        }
        $validated = $validator->validated();
        $invoice->paid = true;
        if($validated['paid_date'] === $created_at){
            $invoice->paid_date = null;
        } else {
            $invoice->paid_date = $validated['paid_date'];
        }
        $invoice->save();
        return redirect()->route('purchases.show', $invoice->id);
    }

    private function authorize(): void
    {
        $user = User::find(auth()->user()->id);
        if(
            !(
                $user->can('kardex')
                || $user->can('purchases-report')
            )
        ){
            abort(403);
        }
    }
}
