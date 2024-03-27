<?php

namespace App\Http\Controllers;

use App\Http\Requests\Clients\StoreRequest;
use App\Http\Requests\Clients\UpdateRequest;
use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ClientController extends Controller
{
    public function index(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'search' => 'nullable|string|min:2|max:255',
            'column' => 'nullable|string|size:4',
            'order' => 'nullable|string|min:3|max:4'
        ], attributes: ['search' => 'Buscar']);
        if($validator->fails()){
            return redirect()->route('clients.index')->withErrors($validator)->withInput();
        }
        $validated = $validator->validated();
        if(isset($validated['search'])){
            $search = $validated['search'];
            $query = Client::whereRaw("`name` LIKE ?", ["%$search%"]);
        }
        $column = match($validated['column'] ?? null){
            'name' => 'name', default => 'name'
        };
        $order = match($validated['order'] ?? null){
            'desc' => 'desc', 'asc' => 'asc', default => 'asc'
        };
        $clients = isset($validated['search'])
            ? $query->orderBy($column, $order)
            : Client::orderBy($column, $order);
        $clients = $clients->paginate(15)->withQueryString();
        foreach($clients as $key => $client){
            $client->n =
                ($key + 1) + ($clients->currentPage() - 1) * $clients->perPage();
        }
        return view('entities.clients.index', [
            'clients' => $clients,
            'filters' => [
                'column' => $column,
                'order' => $order
            ]
        ]);
    }

    public function create()
    {
        return view('entities.clients.create');
    }

    public function store(StoreRequest $request)
    {
        $validated = $request->validated();
        $client = Client::create($validated);
        return redirect()->route('clients.show', $client->id);
    }

    public function show(Client $client)
    {
        return view('entities.clients.show', [
            'client' => $client
        ]);
    }

    public function edit(Client $client)
    {
        return view('entities.clients.edit', [
            'client' => $client
        ]);
    }

    public function update(UpdateRequest $request, Client $client)
    {
        $validated = $request->validated();
        $client->update($validated);
        return redirect()->route('clients.show', $client->id);
    }

    public function destroy(Client $client)
    {
        $client->delete();
        return redirect()->route('clients.index');
    }
}
