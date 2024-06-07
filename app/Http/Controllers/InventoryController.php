<?php

namespace App\Http\Controllers;

use App\Http\Requests\Inventory\ShowIndexRequest;
use App\Models\Products\Product;
use App\Models\Warehouse;

class InventoryController extends Controller
{
    public function queryIndex()
    {
        return view('entities.inventory.query-index');
    }

    public function index(ShowIndexRequest $request)
    {
        $validated = $request->validated();
        $validated['report_type'] = match($validated['report_type']){
            'all' => 0,
            'under_min_stock' => 1,
            'not_stock' => 2,
            default => 0
        };
        $column = match($validated['column'] ?? null){
            'tag' => 'tag',
            'amount' => 'amount',
            'unitary_price' => 'unitary_price',
            'total_price' => 'total_price',
            default => 'tag'
        };
        $order = match($validated['order'] ?? null){
            'desc' => 'desc', 'asc' => 'asc', default => 'asc'
        };
        if(isset($validated['warehouse'])){
            $query = Product::
                leftJoin('product_warehouse', 'product_warehouse.product_id', '=', 'products.id')
                ->leftJoin('product_types', 'product_types.id', '=', 'products.type_id')
                ->leftJoin('product_presentations', 'product_presentations.id', '=', 'products.presentation_id')
                ->leftJoin('balances', 'product_warehouse.balance_id', '=', 'balances.id')
                ->selectRaw("
                    products.id,
                    products.min_stock,
                    CONCAT_WS(' ',
                        `product_types`.`name`,
                        `products`.`name`,
                        CONCAT(`product_presentations`.`content`, 'ml')
                    ) as `tag`,
                    IF(
                        ISNULL(product_warehouse.amount), 0, product_warehouse.amount
                    ) as `amount`,
                    IF(
                        ISNULL(balances.unitary_price), 0, balances.unitary_price
                    ) as `unitary_price`,
                    (product_warehouse.amount * balances.unitary_price)
                    as `total_price`
                ")
                ->where('product_warehouse.warehouse_id', $validated['warehouse']);
            if(isset($validated['search_product'])){
                $search_product = $validated['search_product'];
                $query = $query->whereRaw("
                    CONCAT_WS(' ',
                        `product_types`.`name`,
                        `products`.`name`,
                        CONCAT(`product_presentations`.`content`, 'ml')
                    ) LIKE ?
                ", ["%$search_product%"]);
            }
            // $column,
            //     descending: $order === 'asc' ? false : true
            $products = $query
                ->orderBy($column, $order)
                ->paginate(15)->withQueryString();
        } else {
            $query = Product::with('latestBalance')
                ->leftJoin('product_types', 'product_types.id', '=', 'products.type_id')
                ->leftJoin('product_presentations', 'product_presentations.id', '=', 'products.presentation_id')
                ->selectRaw("
                    products.id,
                    products.min_stock,
                    CONCAT_WS(' ',
                        `product_types`.`name`,
                        `products`.`name`,
                        CONCAT(`product_presentations`.`content`, 'ml')
                    ) as `tag`
                ");
            if(isset($validated['search_product'])){
                $search_product = $validated['search_product'];
                $query = $query->whereRaw("
                    CONCAT_WS(' ',
                        `product_types`.`name`,
                        `products`.`name`,
                        CONCAT(`product_presentations`.`content`, 'ml')
                    ) LIKE ?
                ", ["%$search_product%"]);
            }
            $products = $query->get();
            foreach($products as $key => $product){
                $product->amount = $product->latestBalance?->amount ?? 0;
                $product->unitary_price = $product->latestBalance?->unitary_price ?? '0.00';
                $product->total_price = $product->latestBalance?->total_price ?? '0.00';
                if($validated['report_type'] === 1){
                    if(
                        ! ($product->amount < $product->min_stock)
                    ){
                        $products->forget($key);
                    }
                } else if($validated['report_type'] === 2){
                    if(
                        ! ($product->amount == 0)
                    ){
                        $products->forget($key);
                    }
                }
            }
            $products = $products->sortBy(
                $column,
                descending: $order === 'asc' ? false : true
            );
            $products = $this->paginate(
                $products, 15, $validated['page'] ?? 1, $request->url()
            )->withQueryString();
        }
        return view('entities.inventory.index', [
            'products' => $products,
            'filters' => [
                'warehouse' => isset($validated['warehouse']) ? Warehouse::find($validated['warehouse']) : null,
                'column' => $column,
                'order' => $order
            ]
        ]);
    }
}
