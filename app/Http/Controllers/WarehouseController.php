<?php

namespace App\Http\Controllers;

use App\Http\Requests\Warehouses\IndexRequest;
use App\Http\Requests\Warehouses\StoreRequest;
use App\Http\Requests\Warehouses\UpdateRequest;
use App\Models\Warehouse;

class WarehouseController extends Controller
{
    public function index(IndexRequest $request)
    {
        $validated = $request->validated();
        $warehouses = Warehouse::where(
            'name', 'LIKE', '%' . ($validated['search'] ?? null) . '%'
        )->orderBy(
            $validated['column'], $validated['order']
        )->paginate(10)->withQueryString();
        foreach($warehouses as $key => $warehouse){
            $warehouse->n =
                ($key + 1) + ($warehouses->currentPage() - 1) * $warehouses->perPage();
        }
        return view('entities.warehouses.index', [
            'warehouses' => $warehouses,
            'filters' => [
                'column' => $validated['column'],
                'order' => $validated['order']
            ]
        ]);
    }

    public function create()
    {
        return view('entities.warehouses.create');
    }

    public function store(StoreRequest $request)
    {
        $validated = $request->validated();
        $warehouse = Warehouse::create($validated);
        return redirect()->route('warehouses.show', $warehouse->id);
    }

    public function show(Warehouse $warehouse)
    {
        return view('entities.warehouses.show', compact('warehouse'));
    }

    public function edit(Warehouse $warehouse)
    {
        return view('entities.warehouses.edit', compact('warehouse'));
    }

    public function update(UpdateRequest $request, Warehouse $warehouse)
    {
        $validated = $request->validated();
        $warehouse->update($validated);
        return redirect()->route('warehouses.show', $warehouse->id);
    }

    // public function destroy(Warehouse $warehouse)
    // {
    //     $warehouse->delete();
    //     return redirect()->route('warehouses.index');
    // }
}
