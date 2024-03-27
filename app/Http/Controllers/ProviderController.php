<?php

namespace App\Http\Controllers;

use App\Http\Requests\Providers\StoreRequest;
use App\Http\Requests\Providers\UpdateRequest;
use App\Models\Provider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProviderController extends Controller
{
    public function index(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'search' => 'nullable|string|min:2|max:255',
            'column' => 'nullable|string|size:4',
            'order' => 'nullable|string|min:3|max:4'
        ], attributes: ['search' => 'Buscar']);
        if($validator->fails()){
            return redirect()->route('providers.index')->withErrors($validator)->withInput();
        }
        $validated = $validator->validated();
        if(isset($validated['search'])){
            $search = $validated['search'];
            $query = Provider::whereRaw("`name` LIKE ?", ["%$search%"]);
        }
        $column = match($validated['column'] ?? null){
            'name' => 'name', default => 'name'
        };
        $order = match($validated['order'] ?? null){
            'desc' => 'desc', 'asc' => 'asc', default => 'asc'
        };
        $providers = isset($validated['search'])
            ? $query->orderBy($column, $order)
            : Provider::orderBy($column, $order);
        $providers = $providers->paginate(15)->withQueryString();
        foreach($providers as $key => $provider){
            $provider->n =
                ($key + 1) + ($providers->currentPage() - 1) * $providers->perPage();
        }
        return view('entities.providers.index', [
            'providers' => $providers,
            'filters' => [
                'column' => $column,
                'order' => $order
            ]
        ]);
    }

    public function create()
    {
        return view('entities.providers.create');
    }

    public function store(StoreRequest $request)
    {
        $validated = $request->validated();
        $provider = Provider::create($validated);
        return redirect()->route('providers.show', $provider->id);
    }

    public function show(Provider $provider)
    {
        return view('entities.providers.show', [
            'provider' => $provider
        ]);
    }

    public function edit(Provider $provider)
    {
        return view('entities.providers.edit', [
            'provider' => $provider
        ]);
    }

    public function update(UpdateRequest $request, Provider $provider)
    {
        $validated = $request->validated();
        $provider->update($validated);
        return redirect()->route('providers.show', $provider->id);
    }

    public function destroy(Provider $provider)
    {
        $provider->delete();
        return redirect()->route('providers.index');
    }
}
