<?php

namespace App\Models;

use Spatie\Permission\Models\Permission as SpatieModel;

class Permission extends SpatieModel
{
    public static array $permissions = [
        'products',
        'providers',
        'clients',
        'users',
        'roles',
        'create-purchases',
        'kardex',
        'purchases-report',
        'create-sales',
        'cash-closing',
        'see-all-incomes',
        'sales-report',
        'see-all-sales',
        'edit-all-sales',
        'inventory',
    ];

    public static array $permissionNames = [
        'products' => 'Productos',
        'providers' => 'Proveedores',
        'clients' => 'Clientes',
        'users' => 'Usuarios',
        'roles' => 'Roles',
        'create-purchases' => 'Registrar compras',
        'kardex' => 'Kardex',
        'purchases-report' => 'Reporte de compras',
        'create-sales' => 'Registrar ventas',
        'cash-closing' => 'Cierre de caja',
        'see-all-incomes' => 'Ver todos los ingresos',
        'sales-report' => 'Reporte de ventas',
        'see-all-sales' => 'Ver todas las ventas',
        'edit-all-sales' => 'Editar todas las ventas',
        'inventory' => 'Inventario',
    ];

    public static array $directPermissions = [
        'products',
        'providers',
        'clients',
        // Only Super Admin can manage user accounts, roles, and direct permissions
        // 'users',
        // 'roles',
        'create-purchases',
        'kardex',
        'purchases-report',
        'create-sales',
        'cash-closing',
        'see-all-incomes',
        'sales-report',
        'see-all-sales',
        'edit-all-sales',
        'inventory',
    ];

    public static array $directPermissionNames = [
        'products' => 'Productos',
        'providers' => 'Proveedores',
        'clients' => 'Clientes',
        // Only Super Admin can manage user accounts, roles, and direct permissions
        // 'users' => 'Usuarios',
        // 'roles' => 'Roles',
        'create-purchases' => 'Registrar compras',
        'kardex' => 'Kardex',
        'purchases-report' => 'Reporte de compras',
        'create-sales' => 'Registrar ventas',
        'cash-closing' => 'Cierre de caja',
        'see-all-incomes' => 'Ver todos los ingresos',
        'sales-report' => 'Reporte de ventas',
        'see-all-sales' => 'Ver todas las ventas',
        'edit-all-sales' => 'Editar todas las ventas',
        'inventory' => 'Inventario',
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
