<?php

namespace Database\Seeders;

use App\Models\Client;
use App\Models\Products\Presentation as ProductPresentation;
use App\Models\Products\Product;
use App\Models\Products\SalePrice;
use App\Models\Products\Type as ProductType;
use App\Models\Provider;
use App\Models\Role;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        Provider::factory(30)->create();

        Client::factory(30)->create();

        // Register Roles and Permissions
        $this->call([RolesSeeder::class]);
    
        $user = User::factory()->create([
            'name' => 'Fernando Joel',
            'email' => 'sd.kettei@gmail.com',
        ]);

        $user->assignRole(Role::$superAdmin);
        $user->givePermissionTo('products');

        foreach(ProductType::$initialTypes as $type){
            ProductType::create([
                'name' => $type
            ]);
        }

        foreach(ProductPresentation::$initialPresentations as $presentation){
            ProductPresentation::create([
                'content' => $presentation
            ]);
        }

        $products = [
            ['JHONNIE RED', 1, 1],
            ['JHONNIE BLACK', 1, 1],
            ['JHONNIE GOLD', 1, 1],
            ['JHONNIE DOUBLE BLACK', 1, 1],
            ['JHONNIE RED', 1, 2],
            ['JHONNIE BLACK', 1, 2],
            ['JHONNIE GOLD', 1, 2],
            ['JHONNIE DOUBLE BLACK', 1, 2],
            ['JHONNIE RED', 1, 3],
            ['JHONNIE RED', 1, 4],
            ['JHONNIE BLACK', 1, 3],
            ['JHONNIE BLACK', 1, 4],
            ['JHONNIE GOLD', 1, 3],
            ['JHONNIE GOLD', 1, 4],
            ['JHONNIE DOUBLE BLACK', 1, 3],
            ['JHONNIE DOUBLE BLACK', 1, 4],
        ];
        foreach($products as $product){
            $id = Product::create([
                'name' => $product[0],
                'presentation_id' => $product[1],
                'type_id' => $product[2]
            ])->id;
            SalePrice::create([
                'price' => 10.75,
                'units_number' => 1,
                'product_id' => $id
            ]);
            SalePrice::create([
                'price' => 10.50,
                'units_number' => 6,
                'product_id' => $id
            ]);
            SalePrice::create([
                'price' => 10,
                'units_number' => 12,
                'product_id' => $id
            ]);
        }
    }
}
