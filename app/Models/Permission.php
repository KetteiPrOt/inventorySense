<?php

namespace App\Models;

use Spatie\Permission\Models\Permission as SpatieModel;

class Permission extends SpatieModel
{
    public static array $permissions = [
        'products',
        'clients',
        'providers',
        'sellers',
        'purchases',
        'sales',
        'kardex',
        'cash-closing',
        'inventory',
        'sales-report',
        'purchases-report',
        'permissions'
    ];

    public static array $directPermissions = [
        'products',
        'clients',
        'providers',
        'sellers',
        'purchases',
        'sales',
        'kardex',
        'cash-closing',
        'inventory',
        'sales-report',
        'purchases-report',
        // Only role Administrator can manage permissions
        // 'permissions'
    ];

    public static array $permissionNames = [
        'products' => 'Productos',
        'clients' => 'Clientes',
        'providers' => 'Proveedores',
        'sellers' => 'Vendedores',
        'purchases' => 'Compras',
        'sales' => 'Ventas',
        'kardex' => 'Kardex',
        'cash-closing' => 'Cierre de Caja',
        'inventory' => 'Reporte de stock',
        'sales-report' => 'Reporte de Ventas',
        'purchases-report' => 'Reporte de Compras',
        'permissions' => 'Parametrizar permisos'
    ];

    public static array $directPermissionNames = [
        'products' => 'Productos',
        'clients' => 'Clientes',
        'providers' => 'Proveedores',
        'sellers' => 'Vendedores',
        'purchases' => 'Compras',
        'sales' => 'Ventas',
        'kardex' => 'Kardex',
        'cash-closing' => 'Cierre de Caja',
        'inventory' => 'Reporte de stock',
        'sales-report' => 'Reporte de Ventas',
        'purchases-report' => 'Reporte de Compras',
        // Only role Administrator can manage permissions
        // 'permissions'
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
