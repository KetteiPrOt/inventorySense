<?php

namespace Database\Seeders;

use App\Models\Invoices\Movements\Type as MovementType;
use App\Models\Products\Presentation as ProductPresentation;
use App\Models\Products\Type as ProductType;
use App\Models\Role;
use App\Models\User;
use App\Models\Warehouse;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Initial product types
        foreach(ProductType::$initialTypes as $type){
            ProductType::create([
                'name' => $type
            ]);
        }

        // Initial product presentations
        foreach(
            ProductPresentation::$initialPresentations
            as $presentation
        ){
            ProductPresentation::create([
                'content' => $presentation
            ]);
        }

        // Warehouses
        Warehouse::create(['name' => 'Licorería']);
        Warehouse::create(['name' => 'Deposito']);

        // Roles and permissions
        $this->call([RolesSeeder::class]);

        // Admin user
        $admin = User::create([
            'name' => 'Administrador',
            'email' => config('auth.admin_username'),
            'password' => Hash::make(config('auth.admin_password'))
        ]);

        $admin->assignRole(Role::$superAdmin);

        // Movement types
        $expenseTypes = [
            MovementType::$initialInventoryName,
            MovementType::$purchaseName
        ];
        foreach($expenseTypes as $name){
            MovementType::create(['name' => $name, 'category' => 'e']);
        }

        $incomeTypes = [MovementType::$saleName, 'Donación', 'Publicidad'];
        foreach($incomeTypes as $name){
            MovementType::create(['name' => $name, 'category' => 'i']);
        }

        // Products
        $this->call([ProductsSeeder::class]);
    }
}
