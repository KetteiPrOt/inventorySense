<?php

namespace App\Http\Controllers;

use App\Http\Requests\Users\StoreRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\Registered;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'search' => 'nullable|string|min:2|max:255',
            'column' => 'nullable|string|size:4',
            'order' => 'nullable|string|min:3|max:4'
        ], attributes: ['search' => 'Buscar']);
        if($validator->fails()){
            return redirect()->route('users.index')->withErrors($validator)->withInput();
        }
        $validated = $validator->validated();
        if(isset($validated['search'])){
            $search = $validated['search'];
            $query = User::whereRaw("`name` LIKE ?", ["%$search%"]);
        }
        $column = match($validated['column'] ?? null){
            'name' => 'name', default => 'name'
        };
        $order = match($validated['order'] ?? null){
            'desc' => 'desc', 'asc' => 'asc', default => 'asc'
        };
        $users = isset($validated['search'])
            ? $query->orderBy($column, $order)
            : User::orderBy($column, $order);
        $users = $users->paginate(15)->withQueryString();
        foreach($users as $key => $user){
            $user->n =
                ($key + 1) + ($users->currentPage() - 1) * $users->perPage();
        }
        return view('entities.users.index', [
            'users' => $users,
            'filters' => [
                'column' => $column,
                'order' => $order
            ]
        ]);
    }

    public function create()
    {
        return view('entities.users.create');
    }

    public function store(StoreRequest $request)
    {
        $validated = $request->validated();
        $validated['password'] = Hash::make($validated['password']);
        event(new Registered($user = User::create($validated)));
        return redirect()->route('users.show', $user->id);
    }

    public function show(User $user)
    {
        return view('entities.users.show', [
            'user' => $user
        ]);
    }
}
