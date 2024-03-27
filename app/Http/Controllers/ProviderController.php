<?php

namespace App\Http\Controllers;

use App\Models\Provider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProviderController extends Controller
{
    public function index(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'search' => 'nullable|string|min:2|max:255',
        ], attributes: ['search' => 'Buscar']);
        if($validator->fails()){
            return redirect()->route('products.index')->withErrors($validator)->withInput();
        }
        $validated = $validator->validated();
        $query = Provider::orderBy('name');
        if(isset($validated['search'])){
            $search = $validated['search'];
            $query->whereRaw("`name` LIKE ?", ["%$search%"]);
        }
        return view('entities.providers.index', [
            'providers' => $query->paginate(5)->withQueryString()
        ]);
    }

    public function create()
    {
        return view('entities.providers.create');
    }

    public function store()
    {
        //
    }
}
