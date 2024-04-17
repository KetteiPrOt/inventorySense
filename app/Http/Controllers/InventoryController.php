<?php

namespace App\Http\Controllers;

use App\Models\Products\Product;
use App\Models\Warehouse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class InventoryController extends Controller
{
    public function queryIndex()
    {
        return view('entities.inventory.query-index');
    }

    public function index(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'warehouse' => 'nullable|integer|exists:warehouses,id'
        ], attributes: ['warehouse' => 'bodega']);
        if($validator->fails()){
            return redirect()->route('inventory.query-index')->withErrors($validator)->withInput();
        }
        $validated = $validator->validated();
        if(isset($validated['warehouse'])){
            $products = Product::
                leftJoin('product_warehouse', 'product_warehouse.product_id', '=', 'products.id')
                ->leftJoin('product_types', 'product_types.id', '=', 'products.type_id')
                ->leftJoin('product_presentations', 'product_presentations.id', '=', 'products.presentation_id')
                ->leftJoin('balances', 'product_warehouse.balance_id', '=', 'balances.id')
                ->selectRaw("
                    products.id,
                    CONCAT_WS(' ',
                        `product_types`.`name`,
                        `products`.`name`,
                        CONCAT(`product_presentations`.`content`, 'ml')
                    ) as `tag`,
                    product_warehouse.amount,
                    balances.unitary_price,
                    (product_warehouse.amount * balances.unitary_price)
                    as `total_price`
                ")
                ->where('product_warehouse.warehouse_id', $validated['warehouse'])
                ->orderBy('tag')
                ->paginate(15)->withQueryString();
        } else {
            $products = Product::with('latestBalance')
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
                ")
                ->orderBy('tag')
                ->paginate(15)->withQueryString();
            foreach($products as $product){
                $product->amount = $product->latestBalance?->amount ?? 0;
                $product->unitary_price = $product->latestBalance?->unitary_price ?? '0.00';
                $product->total_price = $product->latestBalance?->total_price ?? '0.00';
            }
        }
        return view('entities.inventory.index', [
            'products' => $products,
            'filters' => [
                'warehouse' => isset($validated['warehouse']) ? Warehouse::find($validated['warehouse']) : null
            ]
        ]);
    }
}
