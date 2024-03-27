<?php

namespace App\Http\Controllers\Products;

use App\Http\Controllers\Controller;
use App\Models\Products\Type;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TypeController extends Controller
{
    public function index(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'search' => 'nullable|string|min:2|max:49',
            'page' => 'nullable|integer|min:1'
        ], attributes: ['search' => 'Buscar']);
        if($validator->fails()){
            return redirect()->route('product-types.index')->withErrors($validator)->withInput();
        }
        $validated = $validator->validated();
        $query = Type::orderBy('name');
        if(isset($validated['search'])){
            $search = $validated['search'];
            $query->whereRaw("`name` LIKE ?", ["%$search%"]);
        }
        return view('entities.products.types.index', [
            'types' => $query->simplePaginate(10)->withQueryString()
        ]);
    }

    public function destroy(Type $type)
    {
        $type->delete();
        return back();
    }
}
