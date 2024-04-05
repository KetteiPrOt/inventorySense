<?php

namespace App\Http\Controllers\Invoices\Purchases;

use App\Http\Controllers\Controller as BaseController;
use App\Http\Requests\Invoices\Purchases\StoreRequest;

class Controller extends BaseController
{
    public function create()
    {
        return view('entities.invoices.purchases.create');
    }

    public function store(StoreRequest $request)
    {
        $validated = $request->validated();
        dump($validated);
    }
}
