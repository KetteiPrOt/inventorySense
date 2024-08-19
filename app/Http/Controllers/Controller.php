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

    protected function multiplication(string $a, string $b, int $scale = 2): string
    {
        return bcmul($a, $b, $scale);
    }

    protected function summation(string $a, string $b, int $scale = 2): string
    {
        return bcadd($a, $b, $scale);
    }

    protected function subtraction(string $a, string $b, int $scale = 2): string
    {
        return bcsub($a, $b, $scale);
    }

    protected function division(string $a, string $b, int $scale = 2): string
    {
        return bcdiv($a, $b, $scale);
    }
}
