<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        /// Roles
        Role::create(['name' => 'Administrador']);
        $seller = Role::create(['name' => 'Vendedor']);

        // Permissions
        foreach(Permission::$permissions as $permission){
            Permission::create(['name' => $permission]);
        }

        // Assign Permissions to Roles
        $sellerPermissions = [
            'sales'
        ];
        foreach($sellerPermissions as $sellerPermission){
            $seller->givePermissionTo($sellerPermission);
        }
    }
}
