<?php

namespace Database\Seeders;

use App\Models\Invoices\Movements\Type as MovementType;
use App\Models\Products\Presentation as ProductPresentation;
use App\Models\Products\Type as ProductType;
use App\Models\Role;
use App\Models\User;
use App\Models\Warehouse;
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
        Warehouse::create(['name' => 'Deposito']);
        Warehouse::create(['name' => 'Licorería']);

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
            MovementType::$purchaseName,
            MovementType::$warehouseChangeExpenseName,
        ];
        foreach($expenseTypes as $name){
            $attributes = [
                'name' => $name,
                'category' => 'e'
            ];
            $attributes['public'] = $name != MovementType::$warehouseChangeExpenseName;
            MovementType::create($attributes);
        }

        $incomeTypes = [
            MovementType::$saleName,
            MovementType::$warehouseChangeIncomeName,
            'Donación',
            'Publicidad',
        ];
        foreach($incomeTypes as $name){
            $attributes = [
                'name' => $name,
                'category' => 'i'
            ];
            $attributes['public'] = $name != MovementType::$warehouseChangeIncomeName;
            MovementType::create($attributes);
        }

        // Products
        $this->call([ProductsSeeder::class]);

        // Test Product
        // $product = Product::create([
        //     'name' => 'PRODUCTO A',
        //     'started_inventory' => false,
        //     'min_stock' => 1,
        //     'presentation_id' => null,
        //     'type_id' => null
        // ]);
        // SalePrice::create([
        //     'price' => '1.00',
        //     'product_id' => $product->id,
        //     'units_number' => 1
        // ]);
    }
}
