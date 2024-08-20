<?php

namespace App\Http\Controllers\Inventory;

use App\Http\Controllers\Controller;
use App\Http\Requests\Inventory\ShowIndexRequest;
use App\Models\Products\Product;
use App\Models\Warehouse;
use Illuminate\Database\Eloquent\Collection;

class IndexController extends Controller
{
    public function queryIndex()
    {
        return view('entities.inventory.query-index');
    }

    public function index(ShowIndexRequest $request)
    {
        $validated = $request->validated();
        $products = $this
            ->productStocks(
                $validated['warehouse'] ?? null,
                $validated['search_product'] ?? null
            )
            ->sortBy($validated['column'], descending: $validated['order'] != 'asc');
        $products = $this->filterResults(
            $validated['report_type'],
            $products
        );
        $products = $this->paginate(
            $products,
            15,
            $validated['page'] ?? 1,
            $request->url()
        );
        return view('entities.inventory.index', [
            'products' => $products,
            'filters' => [
                'report_type' => match($validated['report_type']){
                    'all' => 'Todos los productos.',
                    'under_min_stock' => 'Productos debajo del stock mínimo.',
                    'not_stock' => 'Productos sin stock.',
                },
                'warehouse' => isset($validated['warehouse'])
                    ? Warehouse::find($validated['warehouse'])
                    : null,
                'column' => $validated['column'],
                'order' => $validated['order']
            ]
        ]);
    }

    private function productStocks(?int $warehouse_id = null, ?string $search_product = null): Collection
    {
        $products = Product::joinTag($search_product)->get();
        foreach($products as $product){
            $latestBalance = is_null($warehouse_id)
                ? $product->latestBalance()->first()
                : $product->latestBalanceWarehouse($warehouse_id)->first();
            $product->existences = $latestBalance->amount ?? 0;
            $product->unitary_price = $latestBalance->unitary_price ?? 0;
            $product->total_price = $latestBalance->total_price ?? 0;
        }
        return $products;
    }

    private function filterResults(string $report_type, Collection $products): Collection
    {
        if($report_type == 'under_min_stock'){
            $products = $products->filter(function (Product $product, int $key) {
                return $product->existences < $product->min_stock;
            });
        } else if($report_type == 'not_stock'){
            $products = $products->filter(function (Product $product, int $key) {
                return $product->existences == 0;
            });
        }
        return $products;
    }
}
