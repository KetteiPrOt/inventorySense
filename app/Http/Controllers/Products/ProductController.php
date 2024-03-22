<?php

namespace App\Http\Controllers\Products;

use App\Http\Controllers\Controller;
use App\Models\Products\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'search' => 'nullable|string|min:2|max:255',
            'column' => 'nullable|string|size:3',
            'order' => 'nullable|string|min:3|max:4'
        ], attributes: ['search' => 'Buscar']);
        if($validator->fails()){
            return redirect()->route('products.index')->withErrors($validator)->withInput();
        }
        $validated = $validator->validated();
        $query = 
            Product::leftJoin('product_types', 'product_types.id', '=', 'products.type_id')
                ->leftJoin('product_presentations', 'product_presentations.id', '=', 'products.presentation_id')
                ->selectRaw("
                    products.id,
                    CONCAT_WS(' ',
                        `product_types`.`name`,
                        `products`.`name`,
                        CONCAT(`product_presentations`.`content`, 'ml')
                    ) as `tag`,
                    products.started_inventory
                ");
        if(isset($validated['search'])){
            $search = mb_strtoupper($validated['search']);
            $query->whereRaw("
                    CONCAT_WS(' ',
                        `product_types`.`name`,
                        `products`.`name`,
                        CONCAT(`product_presentations`.`content`, 'ml')
                    ) LIKE ?
                ", ["%$search%"]);
        }
        $column = match($validated['column'] ?? null){
            'tag' => 'tag', default => 'tag'
        };
        $order = match($validated['order'] ?? null){
            'desc' => 'desc', 'asc' => 'asc', default => 'asc'
        };
        $products = $query
            ->orderBy($column, $order)
            ->paginate(15)->withQueryString();
        foreach($products as $key => $product){
            $product->n =
                ($key + 1) + ($products->currentPage() - 1) * $products->perPage();
        }
        return view('entities.products.index', [
            'products' => $products,
            'filters' => [
                'column' => $column,
                'order' => $order
            ]
        ]);
    }
}
