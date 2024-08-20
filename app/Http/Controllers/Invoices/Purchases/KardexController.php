<?php

namespace App\Http\Controllers\Invoices\Purchases;

use App\Http\Controllers\Controller;
use App\Http\Requests\Invoices\Purchases\ShowKardexRequest;
use App\Models\Invoices\Movements\Movement;
use App\Models\Products\Product;
use App\Models\Warehouse;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;

class KardexController extends Controller
{
    public function queryKardex()
    {
        return view('entities.invoices.purchases.query-kardex');
    }

    public function kardex(ShowKardexRequest $request)
    {
        $validated = $request->validated();
        $movements = $this->productMovements($validated);
        $product = Product::find($validated['product']);
        $product->loadTag();
        return $this->redirectToLastPage(
            compact('validated', 'movements', 'product')
        );
    }

    private function productMovements(array $validated): LengthAwarePaginator
    {
        // General kardex, or kardex of warehouse?
        $balances_table = isset($validated['warehouse']) ? 'balances_warehouse' : 'balances';
        // Join and Select
        $query = Movement::
            with('invoice')
            ->join('movement_types', 'movements.type_id', '=', 'movement_types.id')
            ->join('products', 'products.id', '=', 'movements.product_id')
            ->join(
                "$balances_table", "$balances_table.movement_id", '=', 'movements.id'
            )
            ->selectRaw("
                movement_types.`name` as `type`,
                movement_types.`category` as `category`,
                movements.id,
                movements.amount,
                movements.unitary_purchase_price,
                movements.total_purchase_price,
                movements.invoice_id,
                movements.invoice_type,
                products.id as `product_id`,
                $balances_table.amount as `balance_amount`,
                $balances_table.unitary_price as `balance_unitary_price`,
                $balances_table.total_price as `balance_total_price`
            ");
        // Where
        $query = $query
            ->where('product_id', $validated['product'])
            ->whereHasMorph('invoice', '*', function (Builder $query) use ($validated) {
                if( isset($validated['warehouse']) ){
                    $query->where('warehouse_id', '=', $validated['warehouse']);
                }
                $query->where('created_at',  '>=', $validated['date_from'] . ' 00:00:00')
                    ->where('created_at',  '<', $validated['date_to'] . ' 23:59:59');
            });
        // Pagination and Order
        return $query
            ->orderBy('id', 'asc')
            ->paginate(15)->withQueryString();
    }

    private function redirectToLastPage(array $data): object
    {
        extract($data);
        if(isset($validated['page'])){
            $response = view('entities.invoices.purchases.kardex', [
                'movements' => $movements,
                'filters' => [
                    'product' => $product,
                    'warehouse' => isset($validated['warehouse'])
                        ? Warehouse::find($validated['warehouse'])
                        : null,
                    'date_from' => $validated['date_from'],
                    'date_to' => $validated['date_to']
                ]
            ]);
        } else {
            $validated['page'] = $movements->lastPage();
            $response = redirect()->route('purchases.kardex', $validated);
        }
        return $response;
    }
}
