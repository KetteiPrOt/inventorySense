<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        /// Roles
        Role::create(['name' => Role::$superAdmin]);
        $seller = Role::create(['name' => 'Vendedor']);
        $creditController = Role::create(['name' => 'Controlador de crédito']);
        $sentinel = Role::create(['name' => 'Centinela']);;

        // Permissions
        foreach(Permission::$permissions as $permission){
            Permission::create(['name' => $permission]);
        }

        // Assign Permissions to Roles
        $sellerPermissions = [
            'create-sales',
            'cash-closing',
            'sales-report'
        ];
        foreach($sellerPermissions as $sellerPermission){
            $seller->givePermissionTo($sellerPermission);
        }
        $creditControllerPermissions = [
            'create-sales',
            'cash-closing',
            'see-all-incomes',
            'sales-report',
            'see-all-sales',
            'edit-all-sales'
        ];
        foreach($creditControllerPermissions as $creditControllerPermission){
            $creditController->givePermissionTo($creditControllerPermission);
        }
        $sentinelPermissions = Permission::$directPermissions;
        foreach($sentinelPermissions as $sentinelPermission){
            $sentinel->givePermissionTo($sentinelPermission);
        }
    }
}
