<?php

namespace App\Http\Controllers\Products;

use App\Http\Controllers\Controller;
use App\Models\Products\Presentation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Psy\VarDumper\Presenter;

class PresentationController extends Controller
{
    public function index(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'search' => 'nullable|string|min:2|max:49',
            'page' => 'nullable|integer|min:1'
        ], attributes: ['search' => 'Buscar']);
        if($validator->fails()){
            return redirect()->route('product-presentations.index')->withErrors($validator)->withInput();
        }
        $validated = $validator->validated();
        $query = Presentation::orderBy('content');
        if(isset($validated['search'])){
            $search = $validated['search'];
            $query->whereRaw("CONCAT(`content`, 'ml') LIKE ?", ["%$search%"]);
        }
        return view('entities.products.presentations.index', [
            'presentations' => $query->simplePaginate(10)->withQueryString()
        ]);
    }

    public function destroy(Presentation $presentation)
    {
        $presentation->delete();
        return back();
    }
}
