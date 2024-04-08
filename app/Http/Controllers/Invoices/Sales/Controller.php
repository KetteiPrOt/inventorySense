<?php

namespace App\Http\Controllers\Invoices\Sales;

use App\Http\Controllers\Controller as BaseController;
use Illuminate\Http\Request;

class Controller extends BaseController
{
    public function create()
    {
        return view('entities.invoices.sales.create');
    }
}
