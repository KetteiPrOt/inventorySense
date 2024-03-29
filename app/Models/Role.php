<?php

namespace App\Models;

use Spatie\Permission\Models\Role as SpatieModel;

class Role extends SpatieModel
{
    public static string $superAdmin = 'Administrador';

    public static function superAdmin(): Role
    {
        return Role::where('name', Role::$superAdmin)->first();
    }
}
