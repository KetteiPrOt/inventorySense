<?php

namespace App\Http\Controllers;

use Illuminate\Pagination\LengthAwarePaginator;

abstract class Controller
{
    protected function paginate($collection, $perPage, $currentPage, $path): LengthAwarePaginator
    {
        $start = $perPage * ($currentPage - 1);
        $paginated = new LengthAwarePaginator(
            $collection->slice($start, $perPage),
            $collection->count(),
            $perPage,
            $currentPage,
            options: [
                'path' => $path,
            ]
        );
        return $paginated;
    }
}
