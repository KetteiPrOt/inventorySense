<?php

namespace App\Models;

use Spatie\Permission\Models\Permission as SpatieModel;

class Permission extends SpatieModel
{
    public static array $permissions = [
        'products',
        'clients',
        'providers',
        'users',
        'roles',
        'create-purchases',
        'create-sales',
        'kardex',
        'cash-closing',
        'inventory',
        'sales-report',
        'purchases-report',
    ];

    public static array $permissionNames = [
        'products' => 'Productos',
        'clients' => 'Clientes',
        'providers' => 'Proveedores',
        'users' => 'Usuarios',
        'roles' => 'Roles',
        'create-purchases' => 'Registrar compras',
        'create-sales' => 'Registrar ventas',
        'kardex' => 'Kardex',
        'cash-closing' => 'Cierre de caja',
        'inventory' => 'Inventario',
        'sales-report' => 'Reporte de ventas',
        'purchases-report' => 'Reporte de compras',
        'permissions' => 'Parametrizar permisos',
    ];

    public static array $directPermissions = [
        'products',
        'clients',
        'providers',
        // Only Super Admin can manage user accounts, roles, and direct permissions
        // 'users',
        // 'roles',
        'create-purchases',
        'create-sales',
        'kardex',
        'cash-closing',
        'inventory',
        'sales-report',
        'purchases-report',
    ];

    public static array $directPermissionNames = [
        'products' => 'Productos',
        'clients' => 'Clientes',
        'providers' => 'Proveedores',
        // Only Super Admin can manage user accounts, roles, and direct permissions
        // 'users' => 'Usuarios',
        // 'roles' => 'Roles',
        'create-purchases' => 'Registrar compras',
        'create-sales' => 'Registrar ventas',
        'kardex' => 'Kardex',
        'cash-closing' => 'Cierre de caja',
        'inventory' => 'Inventario',
        'sales-report' => 'Reporte de ventas',
        'purchases-report' => 'Reporte de compras',
        'permissions' => 'Parametrizar permisos',
    ];

    public static function translator(): object
    {
        return new class {
            public array $directPermissions;

            public array $permissions;

            private array $permissionNames;

            public function __construct()
            {
                $this->directPermissions = Permission::$directPermissions;
                $this->permissionNames = Permission::$permissionNames;
                $this->permissions = Permission::$permissions;
            }

            public function translate(string $permission): string
            {
                return $this->permissionNames[$permission];
            }
        };
    }
}
