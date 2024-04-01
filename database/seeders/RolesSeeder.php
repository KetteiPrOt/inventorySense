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
        Role::create(['name' => 'Vendedor_1']);
        Role::create(['name' => 'Vendedor_2']);
        Role::create(['name' => 'Vendedor_3']);
        Role::create(['name' => 'Vendedor_4']);
        Role::create(['name' => 'Vendedor_5']);
        Role::create(['name' => 'Vendedor_6']);

        // Permissions
        foreach(Permission::$permissions as $permission){
            Permission::create(['name' => $permission]);
        }

        // Assign Permissions to Roles
        $sellerPermissions = [
            'create-sales'
        ];
        foreach($sellerPermissions as $sellerPermission){
            $seller->givePermissionTo($sellerPermission);
        }
    }
}
