<?php

namespace Database\Seeders;

use App\Models\Invoices\PurchaseInvoice;
use App\Models\Products\Presentation;
use App\Models\Products\Product;
use App\Models\Products\SalePrice;
use App\Models\Products\Type;
use Illuminate\Database\Seeder;
use App\Http\Controllers\Invoices\Purchases\Expenses\StoreController as StoreExpenseController;
use App\Models\Invoices\Movements\Type as MovementType;

class ProductsSeeder extends Seeder
{
    private array $types = [];

    private array $presentations = [];

    public function __construct()
    {
        foreach(Type::$initialTypes as $key => $name){
            $id = $key + 1;
            $this->types[$name] = $id;
        }
        foreach(Presentation::$initialPresentations as $key => $name){
            $id = $key + 1;
            $this->presentations[$name] = $id;
        }
    }

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = $this->defineProducts();
        foreach($products as $productData){
            // Create Product
            $product = Product::create([
                'name' => mb_strtoupper($productData['name']),
                'type_id' => $this->types[$productData['type']],
                'min_stock' => 1,
                'presentation_id' => $this->presentations[$productData['presentation']],
            ]);
            // Create sale price
            SalePrice::create([
                'price' => $productData['sale_price'],
                'units_number' => 1,
                'product_id' => $product->id
            ]);
            if($productData['deposit_count'] > 0){
                $this->createExpense(
                    $product,
                    $productData['deposit_count'],
                    $productData['purchase_price'],
                    warehouse_id: 1,
                    movementType: MovementType::initialInventory()
                );
                if($productData['liquor_store_count'] > 0){
                    $this->createExpense(
                        $product,
                        $productData['liquor_store_count'],
                        $productData['purchase_price'],
                        warehouse_id: 2,
                        movementType: MovementType::purchase()
                    );
                }
            } else if($productData['liquor_store_count'] > 0) {
                $this->createExpense(
                    $product,
                    $productData['liquor_store_count'],
                    $productData['purchase_price'],
                    warehouse_id: 2,
                    movementType: MovementType::initialInventory()
                );
            }
        }
    }

    private function createExpense($product, $amount, $purchase_price, $warehouse_id, $movementType)
    {
        
        $invoice = PurchaseInvoice::create([
            'number' => null,
            'comment' => null,
            'due_payment_date' => null,
            'paid' => true,
            'paid_date' => null,
            'user_id' => 1,
            'warehouse_id' => $warehouse_id,
            'provider_id' => null
        ]);
        $expenseController = new StoreExpenseController;
        $expenseController->store([
            'amount' => $amount,
            'unitary_purchase_price' => $purchase_price,
            'product_id' => $product->id,
            'invoice_id' => $invoice->id,
            'invoice_type' => PurchaseInvoice::class,
            'type_id' => $movementType->id,
        ], warehouse_id: $warehouse_id);
    }

    private function defineProducts(): array
    {
        return array(
            array('type' => 'VINO','name' => 'ANTHONY FRAMBUESA LATA','presentation' => '375','deposit_count' => '54','liquor_store_count' => '12','purchase_price' => '1.850000','sale_price' => '1.750000'),
            array('type' => 'VINO','name' => 'ANTHONY MORA LATA','presentation' => '375','deposit_count' => '40','liquor_store_count' => '12','purchase_price' => '1.120000','sale_price' => '1.750000'),
            array('type' => 'AGUARDIENTE','name' => 'ANTIOQUEÑO AZUL','presentation' => '375','deposit_count' => '0','liquor_store_count' => '0','purchase_price' => '7.500000','sale_price' => '9.000000'),
            array('type' => 'AGUARDIENTE','name' => 'ANTIOQUEÑO ROJO','presentation' => '375','deposit_count' => '0','liquor_store_count' => '0','purchase_price' => '0.000000','sale_price' => '9.000000'),
            array('type' => 'AGUARDIENTE','name' => 'CAÑA MANABITA NEGRA','presentation' => '375','deposit_count' => '0','liquor_store_count' => '1','purchase_price' => '3.125000','sale_price' => '3.750000'),
            array('type' => 'AGUARDIENTE','name' => 'CAÑA MANABITA ROJA','presentation' => '375','deposit_count' => '0','liquor_store_count' => '1','purchase_price' => '3.540000','sale_price' => '4.250000'),
            array('type' => 'AGUARDIENTE','name' => 'CRISTAL','presentation' => '375','deposit_count' => '10','liquor_store_count' => '0','purchase_price' => '0.000000','sale_price' => '4.000000'),
            array('type' => 'AGUARDIENTE','name' => 'CRISTAL SECO','presentation' => '375','deposit_count' => '0','liquor_store_count' => '0','purchase_price' => '0.000000','sale_price' => '4.000000'),
            array('type' => 'AGUARDIENTE','name' => 'NORTEÑO ESPECIAL','presentation' => '375','deposit_count' => '0','liquor_store_count' => '0','purchase_price' => '0.000000','sale_price' => '4.000000'),
            array('type' => 'AGUARDIENTE','name' => 'TROPICO SECO','presentation' => '375','deposit_count' => '0','liquor_store_count' => '0','purchase_price' => '3.333300','sale_price' => '4.000000'),
            array('type' => 'WHISKY','name' => 'GENIO','presentation' => '375','deposit_count' => '0','liquor_store_count' => '0','purchase_price' => '1.620000','sale_price' => '2.500000'),
            array('type' => 'VINO','name' => 'ANTHONY MORA LIGHT LATA','presentation' => '375','deposit_count' => '14','liquor_store_count' => '0','purchase_price' => '1.120000','sale_price' => '1.750000'),
            array('type' => 'AGUARDIENTE','name' => 'CAÑA ROSE','presentation' => '600','deposit_count' => '32','liquor_store_count' => '0','purchase_price' => '5.000000','sale_price' => '6.000000'),
            array('type' => 'WHISKY','name' => 'GRANTS VERDE','presentation' => '700','deposit_count' => '4','liquor_store_count' => '3','purchase_price' => '32.080000','sale_price' => '38.500000'),
            array('type' => 'WHISKY','name' => 'JAMES KING','presentation' => '700','deposit_count' => '0','liquor_store_count' => '0','purchase_price' => '0.000000','sale_price' => '10.000000'),
            array('type' => 'WHISKY','name' => 'LABEL 5','presentation' => '700','deposit_count' => '3','liquor_store_count' => '0','purchase_price' => '9.160000','sale_price' => '11.000000'),
            array('type' => 'WHISKY','name' => 'PASSPORT SCOTCH','presentation' => '700','deposit_count' => '1','liquor_store_count' => '0','purchase_price' => '11.250000','sale_price' => '13.500000'),
            array('type' => 'WHISKY','name' => 'PASSPORT SELECTION','presentation' => '700','deposit_count' => '7','liquor_store_count' => '2','purchase_price' => '10.580000','sale_price' => '13.000000'),
            array('type' => 'WHISKY','name' => 'BALLANTINES','presentation' => '700','deposit_count' => '6','liquor_store_count' => '0','purchase_price' => '16.500000','sale_price' => '20.000000'),
            array('type' => 'AGUARDIENTE','name' => 'ZHUMIR NARANJILLA','presentation' => '700','deposit_count' => '0','liquor_store_count' => '0','purchase_price' => '3.584900','sale_price' => '4.500000'),
            array('type' => 'WHISKY','name' => 'OLD TIMES BLACK','presentation' => '745','deposit_count' => '1','liquor_store_count' => '1','purchase_price' => '12.080000','sale_price' => '14.500000'),
            array('type' => 'VODKA','name' => 'ABSOLUT','presentation' => '750','deposit_count' => '0','liquor_store_count' => '0','purchase_price' => '0.000000','sale_price' => '25.000000'),
            array('type' => 'RON','name' => 'ABUELO AÑEJO','presentation' => '750','deposit_count' => '19','liquor_store_count' => '2','purchase_price' => '10.420000','sale_price' => '12.500000'),
            array('type' => 'VINO','name' => 'ALTA GAMMA','presentation' => '750','deposit_count' => '0','liquor_store_count' => '0','purchase_price' => '0.000000','sale_price' => '5.000000'),
            array('type' => 'VINO','name' => 'ANTHONY MORA','presentation' => '750','deposit_count' => '1','liquor_store_count' => '1','purchase_price' => '6.470000','sale_price' => '8.000000'),
            array('type' => 'VINO','name' => 'ANTHONY ROSE','presentation' => '750','deposit_count' => '3','liquor_store_count' => '1','purchase_price' => '6.030000','sale_price' => '7.500000'),
            array('type' => 'AGUARDIENTE','name' => 'ANTIOQUEÑO AZUL','presentation' => '750','deposit_count' => '5','liquor_store_count' => '3','purchase_price' => '12.500000','sale_price' => '15.000000'),
            array('type' => 'AGUARDIENTE','name' => 'ANTIOQUEÑO ROJO','presentation' => '750','deposit_count' => '0','liquor_store_count' => '0','purchase_price' => '0.000000','sale_price' => '14.000000'),
            array('type' => 'AGUARDIENTE','name' => 'ANTIOQUEÑO VERDE','presentation' => '750','deposit_count' => '0','liquor_store_count' => '0','purchase_price' => '12.500000','sale_price' => '14.000000'),
            array('type' => 'WHISKY','name' => 'ASTILLA DE ROBLE','presentation' => '750','deposit_count' => '0','liquor_store_count' => '0','purchase_price' => '0.000000','sale_price' => '6.000000'),
            array('type' => 'TEQUILA','name' => 'AZTECA ORO','presentation' => '750','deposit_count' => '11','liquor_store_count' => '3','purchase_price' => '12.990000','sale_price' => '16.000000'),
            array('type' => 'WHISKY','name' => 'BACARDI CARTA ORO','presentation' => '750','deposit_count' => '17','liquor_store_count' => '2','purchase_price' => '14.580000','sale_price' => '17.500000'),
            array('type' => 'WHISKY','name' => 'BELLOW','presentation' => '750','deposit_count' => '0','liquor_store_count' => '0','purchase_price' => '0.000000','sale_price' => '12.000000'),
            array('type' => 'WHISKY','name' => 'BLACK AND WHITE','presentation' => '750','deposit_count' => '0','liquor_store_count' => '0','purchase_price' => '0.000000','sale_price' => '17.500000'),
            array('type' => 'WHISKY','name' => 'BLACK OWL','presentation' => '750','deposit_count' => '8','liquor_store_count' => '1','purchase_price' => '11.500000','sale_price' => '14.500000'),
            array('type' => 'WHISKY','name' => 'BLACK WILLIAMS','presentation' => '750','deposit_count' => '26','liquor_store_count' => '5','purchase_price' => '10.830000','sale_price' => '13.000000'),
            array('type' => 'VINO','name' => 'BLANCO TOCORNAL','presentation' => '750','deposit_count' => '0','liquor_store_count' => '0','purchase_price' => '0.000000','sale_price' => '7.000000'),
            array('type' => 'ESPUMANTE','name' => 'BLUE NUN 24K GOLD','presentation' => '750','deposit_count' => '8','liquor_store_count' => '1','purchase_price' => '12.500000','sale_price' => '15.000000'),
            array('type' => 'ESPUMANTE','name' => 'BLUE NUN 24K ROSE','presentation' => '750','deposit_count' => '0','liquor_store_count' => '0','purchase_price' => '12.500000','sale_price' => '15.000000'),
            array('type' => 'VINO','name' => 'BLANCO BLUE NUN RIVANER (AZUL)','presentation' => '750','deposit_count' => '5','liquor_store_count' => '0','purchase_price' => '11.660000','sale_price' => '14.000000'),
            array('type' => 'VINO','name' => 'BOONES APPLE','presentation' => '750','deposit_count' => '1','liquor_store_count' => '1','purchase_price' => '6.660000','sale_price' => '8.000000'),
            array('type' => 'WHISKY','name' => 'BUCHANANS DELUXE','presentation' => '750','deposit_count' => '4','liquor_store_count' => '2','purchase_price' => '54.000000','sale_price' => '56.000000'),
            array('type' => 'RON','name' => 'CABALLO VIEJO CON VASO','presentation' => '750','deposit_count' => '4','liquor_store_count' => '1','purchase_price' => '10.000000','sale_price' => '12.000000'),
            array('type' => 'VINO','name' => 'CALVET BORDEAUX','presentation' => '750','deposit_count' => '0','liquor_store_count' => '0','purchase_price' => '0.000000','sale_price' => '8.000000'),
            array('type' => 'VINO','name' => 'CALVET BORDEAUX RESERVE','presentation' => '750','deposit_count' => '0','liquor_store_count' => '0','purchase_price' => '10.830000','sale_price' => '13.000000'),
            array('type' => 'VINO','name' => 'CALVET VARIETALS CABERNET SAUVIGNON','presentation' => '750','deposit_count' => '5','liquor_store_count' => '0','purchase_price' => '6.660000','sale_price' => '8.000000'),
            array('type' => 'VINO','name' => 'CALVET VARIETALS MERLOT','presentation' => '750','deposit_count' => '6','liquor_store_count' => '0','purchase_price' => '7.500000','sale_price' => '9.000000'),
            array('type' => 'AGUARDIENTE','name' => 'CAÑA MANABITA NEGRA','presentation' => '750','deposit_count' => '28','liquor_store_count' => '2','purchase_price' => '5.830000','sale_price' => '7.000000'),
            array('type' => 'AGUARDIENTE','name' => 'CAÑA MANABITA NEGRA ESPECIAL','presentation' => '750','deposit_count' => '0','liquor_store_count' => '0','purchase_price' => '10.000000','sale_price' => '12.000000'),
            array('type' => 'AGUARDIENTE','name' => 'CAÑA MANABITA ROJA','presentation' => '750','deposit_count' => '0','liquor_store_count' => '0','purchase_price' => '6.870000','sale_price' => '8.250000'),
            array('type' => 'AGUARDIENTE','name' => 'CAÑA MANABITA ROJA ESPECIAL (TUBO)','presentation' => '750','deposit_count' => '0','liquor_store_count' => '1','purchase_price' => '10.000000','sale_price' => '12.000000'),
            array('type' => 'AGUARDIENTE','name' => 'CAÑA MANABITA VERDE','presentation' => '750','deposit_count' => '0','liquor_store_count' => '0','purchase_price' => '0.000000','sale_price' => '6.000000'),
            array('type' => 'ESPUMANTE','name' => 'CAPRICCIO NOVECENTO (DORADO)','presentation' => '750','deposit_count' => '3','liquor_store_count' => '0','purchase_price' => '10.000000','sale_price' => '12.000000'),
            array('type' => 'RON','name' => 'CARTAVIO BLACK','presentation' => '750','deposit_count' => '5','liquor_store_count' => '0','purchase_price' => '7.080000','sale_price' => '8.500000'),
            array('type' => 'RON','name' => 'CARTAVIO SILVER (BLANCO)','presentation' => '750','deposit_count' => '6','liquor_store_count' => '6','purchase_price' => '7.920000','sale_price' => '9.500000'),
            array('type' => 'VINO','name' => 'CASILLERO DEL DIABLO','presentation' => '750','deposit_count' => '4','liquor_store_count' => '0','purchase_price' => '15.000000','sale_price' => '18.000000'),
            array('type' => 'RON','name' => 'CASTILLO BLANCO','presentation' => '750','deposit_count' => '17','liquor_store_count' => '0','purchase_price' => '7.500000','sale_price' => '9.000000'),
            array('type' => 'VINO','name' => 'CATADOR','presentation' => '750','deposit_count' => '4','liquor_store_count' => '0','purchase_price' => '3.750000','sale_price' => '4.500000'),
            array('type' => 'ESPUMANTE','name' => 'CHIARLI MIO BLANCO','presentation' => '750','deposit_count' => '3','liquor_store_count' => '0','purchase_price' => '5.830000','sale_price' => '7.000000'),
            array('type' => 'ESPUMANTE','name' => 'CHIARLI MIO ROSADO','presentation' => '750','deposit_count' => '3','liquor_store_count' => '0','purchase_price' => '5.830000','sale_price' => '7.000000'),
            array('type' => 'ESPUMANTE','name' => 'CHIARLIL MIO ROJO','presentation' => '750','deposit_count' => '2','liquor_store_count' => '0','purchase_price' => '5.830000','sale_price' => '7.000000'),
            array('type' => 'WHISKY','name' => 'CHIVAS REGAL 12 YEARS','presentation' => '750','deposit_count' => '1','liquor_store_count' => '1','purchase_price' => '33.330000','sale_price' => '45.000000'),
            array('type' => 'WHISKY','name' => 'CLAN MACGREGOR','presentation' => '750','deposit_count' => '13','liquor_store_count' => '2','purchase_price' => '8.330000','sale_price' => '10.000000'),
            array('type' => 'COCKTAIL','name' => 'CREMA SABOR A WHISKY COLDS','presentation' => '750','deposit_count' => '1','liquor_store_count' => '0','purchase_price' => '7.000000','sale_price' => '9.000000'),
            array('type' => 'AGUARDIENTE','name' => 'CRISTAL ROJO CLASICO','presentation' => '750','deposit_count' => '6','liquor_store_count' => '0','purchase_price' => '6.660000','sale_price' => '10.000000'),
            array('type' => 'AGUARDIENTE','name' => 'CRISTAL SECO','presentation' => '750','deposit_count' => '12','liquor_store_count' => '0','purchase_price' => '6.250000','sale_price' => '7.500000'),
            array('type' => 'VINO','name' => 'CRUZARES','presentation' => '750','deposit_count' => '6','liquor_store_count' => '7','purchase_price' => '3.750000','sale_price' => '4.500000'),
            array('type' => 'RON','name' => 'CUBANERO ORO','presentation' => '750','deposit_count' => '0','liquor_store_count' => '0','purchase_price' => '0.000000','sale_price' => '7.000000'),
            array('type' => 'VINO','name' => 'DIABLO','presentation' => '750','deposit_count' => '0','liquor_store_count' => '1','purchase_price' => '21.660000','sale_price' => '26.000000'),
            array('type' => 'WHISKY','name' => 'DIXONS AZUL','presentation' => '750','deposit_count' => '0','liquor_store_count' => '0','purchase_price' => '0.000000','sale_price' => '13.000000'),
            array('type' => 'WHISKY','name' => 'DIXONS VERDE','presentation' => '750','deposit_count' => '0','liquor_store_count' => '0','purchase_price' => '0.000000','sale_price' => '8.000000'),
            array('type' => 'TEQUILA','name' => 'EL CHARRO AGUIJON','presentation' => '750','deposit_count' => '2','liquor_store_count' => '0','purchase_price' => '10.830000','sale_price' => '13.000000'),
            array('type' => 'TEQUILA','name' => 'EL CHARRO MARGARITA FRESA','presentation' => '750','deposit_count' => '2','liquor_store_count' => '0','purchase_price' => '0.000000','sale_price' => '10.000000'),
            array('type' => 'TEQUILA','name' => 'EL CHARRO MARGARITA LIMON','presentation' => '750','deposit_count' => '6','liquor_store_count' => '0','purchase_price' => '0.000000','sale_price' => '10.000000'),
            array('type' => 'TEQUILA','name' => 'EL CHARRO GOLD','presentation' => '750','deposit_count' => '3','liquor_store_count' => '0','purchase_price' => '14.160000','sale_price' => '17.000000'),
            array('type' => 'TEQUILA','name' => 'EL CHARRO REPOSADO','presentation' => '750','deposit_count' => '0','liquor_store_count' => '0','purchase_price' => '0.000000','sale_price' => '24.000000'),
            array('type' => 'TEQUILA','name' => 'EL CHARRO SILVER','presentation' => '750','deposit_count' => '7','liquor_store_count' => '9','purchase_price' => '14.160000','sale_price' => '17.000000'),
            array('type' => 'RON','name' => 'ESTELAR','presentation' => '750','deposit_count' => '2','liquor_store_count' => '1','purchase_price' => '7.080000','sale_price' => '8.500000'),
            array('type' => 'VINO','name' => 'FRAILE','presentation' => '750','deposit_count' => '0','liquor_store_count' => '3','purchase_price' => '3.280000','sale_price' => '4.000000'),
            array('type' => 'VINO','name' => 'FRONTERA MERLOT','presentation' => '750','deposit_count' => '0','liquor_store_count' => '1','purchase_price' => '7.910000','sale_price' => '9.500000'),
            array('type' => 'AGUARDIENTE','name' => 'FRONTERA','presentation' => '750','deposit_count' => '0','liquor_store_count' => '0','purchase_price' => '0.000000','sale_price' => '8.000000'),
            array('type' => 'VINO','name' => 'GATO NEGRO CABERNET SAUVIGNON','presentation' => '750','deposit_count' => '0','liquor_store_count' => '1','purchase_price' => '5.000000','sale_price' => '6.250000'),
            array('type' => 'WHISKY','name' => 'GENIO','presentation' => '750','deposit_count' => '0','liquor_store_count' => '17','purchase_price' => '1.000000','sale_price' => '3.750000'),
            array('type' => 'ESPUMANTE','name' => 'GRAND DUVAL','presentation' => '750','deposit_count' => '0','liquor_store_count' => '0','purchase_price' => '0.000000','sale_price' => '6.500000'),
            array('type' => 'WHISKY','name' => 'GRAND OLD PAR','presentation' => '750','deposit_count' => '28','liquor_store_count' => '0','purchase_price' => '42.500000','sale_price' => '51.000000'),
            array('type' => 'ESPUMANTE','name' => 'GRAND VAN DUSH AZUL','presentation' => '750','deposit_count' => '0','liquor_store_count' => '0','purchase_price' => '0.000000','sale_price' => '7.250000'),
            array('type' => 'ESPUMANTE','name' => 'GRAND VAN DUSH ROSADO','presentation' => '750','deposit_count' => '20','liquor_store_count' => '7','purchase_price' => '6.040000','sale_price' => '7.250000'),
            array('type' => 'WHISKY','name' => 'GRANTS AZUL','presentation' => '750','deposit_count' => '6','liquor_store_count' => '5','purchase_price' => '20.000000','sale_price' => '24.000000'),
            array('type' => 'WHISKY','name' => 'GRANTS ROJO','presentation' => '750','deposit_count' => '10','liquor_store_count' => '12','purchase_price' => '13.960000','sale_price' => '18.500000'),
            array('type' => 'WHISKY','name' => 'HIGHLAND LEGEND','presentation' => '750','deposit_count' => '0','liquor_store_count' => '0','purchase_price' => '0.000000','sale_price' => '8.000000'),
            array('type' => 'WHISKY','name' => 'JACK DANIEL\'S HONEY','presentation' => '750','deposit_count' => '1','liquor_store_count' => '0','purchase_price' => '54.160000','sale_price' => '65.000000'),
            array('type' => 'WHISKY','name' => 'JAGERMEITER','presentation' => '750','deposit_count' => '0','liquor_store_count' => '0','purchase_price' => '0.000000','sale_price' => '30.000000'),
            array('type' => 'WHISKY','name' => 'JOHN MORRIS BLACK','presentation' => '750','deposit_count' => '1','liquor_store_count' => '1','purchase_price' => '10.000000','sale_price' => '12.000000'),
            array('type' => 'WHISKY','name' => 'JOHN MORRIS BLUE','presentation' => '750','deposit_count' => '0','liquor_store_count' => '0','purchase_price' => '0.000000','sale_price' => '14.000000'),
            array('type' => 'WHISKY','name' => 'JHONNIE DORADO','presentation' => '750','deposit_count' => '2','liquor_store_count' => '1','purchase_price' => '79.160000','sale_price' => '95.000000'),
            array('type' => 'WHISKY','name' => 'JHONNIE DOUBLE BLACK','presentation' => '750','deposit_count' => '2','liquor_store_count' => '2','purchase_price' => '44.690000','sale_price' => '69.000000'),
            array('type' => 'WHISKY','name' => 'JHONNIE GREEN LABEL','presentation' => '750','deposit_count' => '0','liquor_store_count' => '0','purchase_price' => '0.000000','sale_price' => '107.000000'),
            array('type' => 'WHISKY','name' => 'JHONNIE NEGRO','presentation' => '750','deposit_count' => '15','liquor_store_count' => '2','purchase_price' => '50.000000','sale_price' => '60.000000'),
            array('type' => 'WHISKY','name' => 'JHONNIE ROJO','presentation' => '750','deposit_count' => '20','liquor_store_count' => '5','purchase_price' => '0.000000','sale_price' => '23.500000'),
            array('type' => 'WHISKY','name' => 'JOHN BARR NEGRO','presentation' => '750','deposit_count' => '3','liquor_store_count' => '0','purchase_price' => '12.500000','sale_price' => '15.000000'),
            array('type' => 'WHISKY','name' => 'JOHN BARR ROJO','presentation' => '750','deposit_count' => '0','liquor_store_count' => '0','purchase_price' => '0.000000','sale_price' => '12.000000'),
            array('type' => 'TEQUILA','name' => 'JOSE CUERVO BLANCO','presentation' => '750','deposit_count' => '1','liquor_store_count' => '0','purchase_price' => '41.660000','sale_price' => '50.000000'),
            array('type' => 'TEQUILA','name' => 'JOSE CUERVO ORO','presentation' => '750','deposit_count' => '1','liquor_store_count' => '0','purchase_price' => '41.660000','sale_price' => '50.000000'),
            array('type' => 'VINO','name' => 'JP CHENET','presentation' => '750','deposit_count' => '0','liquor_store_count' => '0','purchase_price' => '0.000000','sale_price' => '9.000000'),
            array('type' => 'TEQUILA','name' => 'KATRINA CANNABIS','presentation' => '750','deposit_count' => '31','liquor_store_count' => '36','purchase_price' => '7.880000','sale_price' => '10.000000'),
            array('type' => 'TEQUILA','name' => 'KATRINA PINK','presentation' => '750','deposit_count' => '0','liquor_store_count' => '2','purchase_price' => '8.500000','sale_price' => '10.500000'),
            array('type' => 'VINO','name' => 'LA CATEDRA','presentation' => '750','deposit_count' => '0','liquor_store_count' => '0','purchase_price' => '0.000000','sale_price' => '4.500000'),
            array('type' => 'VINO','name' => 'LA PARRA','presentation' => '750','deposit_count' => '0','liquor_store_count' => '0','purchase_price' => '3.950000','sale_price' => '4.750000'),
            array('type' => 'VINO','name' => 'LA VID BLEND','presentation' => '750','deposit_count' => '6','liquor_store_count' => '0','purchase_price' => '3.750000','sale_price' => '4.500000'),
            array('type' => 'VINO','name' => 'LAMBRUSCO TINTO CASSETA MARIA','presentation' => '750','deposit_count' => '1','liquor_store_count' => '0','purchase_price' => '6.660000','sale_price' => '8.000000'),
            array('type' => 'VINO','name' => 'LAMBRUSCO ROSATO ANTONIO MACCIERI','presentation' => '750','deposit_count' => '9','liquor_store_count' => '4','purchase_price' => '9.580000','sale_price' => '11.500000'),
            array('type' => 'VINO','name' => 'LAMBRUSCO ROSATO CASSETA MARIA','presentation' => '750','deposit_count' => '17','liquor_store_count' => '0','purchase_price' => '6.660000','sale_price' => '8.000000'),
            array('type' => 'VINO','name' => 'MIRAFLORES','presentation' => '750','deposit_count' => '9','liquor_store_count' => '10','purchase_price' => '3.840000','sale_price' => '5.000000'),
            array('type' => 'RON','name' => 'MONTAÑITA','presentation' => '750','deposit_count' => '0','liquor_store_count' => '0','purchase_price' => '0.000000','sale_price' => '6.000000'),
            array('type' => 'AGUARDIENTE','name' => 'NORTEÑO ESPECIAL','presentation' => '750','deposit_count' => '12','liquor_store_count' => '0','purchase_price' => '5.830000','sale_price' => '7.000000'),
            array('type' => 'VINO','name' => 'NOVECENTO NIGHT','presentation' => '750','deposit_count' => '0','liquor_store_count' => '0','purchase_price' => '0.000000','sale_price' => '10.000000'),
            array('type' => 'WHISKY','name' => 'OLD TIMES RED','presentation' => '750','deposit_count' => '5','liquor_store_count' => '2','purchase_price' => '8.330000','sale_price' => '10.000000'),
            array('type' => 'COCKTAIL','name' => 'PIÑA COLADA COLDS','presentation' => '750','deposit_count' => '0','liquor_store_count' => '1','purchase_price' => '7.000000','sale_price' => '9.000000'),
            array('type' => 'COCKTAIL','name' => 'PIÑA COLADA ZHUMIR','presentation' => '750','deposit_count' => '0','liquor_store_count' => '0','purchase_price' => '0.000000','sale_price' => '10.000000'),
            array('type' => 'WHISKY','name' => 'RED WILLIAMS MÁS VASO','presentation' => '750','deposit_count' => '4','liquor_store_count' => '4','purchase_price' => '7.940000','sale_price' => '10.000000'),
            array('type' => 'VINO','name' => 'RESERVADO AZUL (MALBEC)','presentation' => '750','deposit_count' => '0','liquor_store_count' => '0','purchase_price' => '0.000000','sale_price' => '8.000000'),
            array('type' => 'VINO','name' => 'RESERVADO MERLOT CONCHA Y TORO (MORADO)','presentation' => '750','deposit_count' => '4','liquor_store_count' => '0','purchase_price' => '6.660000','sale_price' => '8.000000'),
            array('type' => 'VINO','name' => 'RESERVADO CABERNET SAUVIGNON CONCHA Y TORO (ROJO)','presentation' => '750','deposit_count' => '11','liquor_store_count' => '3','purchase_price' => '7.270000','sale_price' => '10.000000'),
            array('type' => 'COCKTAIL','name' => 'ROMANOSKY AZUL','presentation' => '750','deposit_count' => '5','liquor_store_count' => '5','purchase_price' => '2.950000','sale_price' => '3.500000'),
            array('type' => 'COCKTAIL','name' => 'ROMANOSKY ROSA','presentation' => '750','deposit_count' => '14','liquor_store_count' => '0','purchase_price' => '2.950000','sale_price' => '3.500000'),
            array('type' => 'WHISKY','name' => 'RUSS KAYA AZUL','presentation' => '750','deposit_count' => '0','liquor_store_count' => '0','purchase_price' => '0.000000','sale_price' => '10.000000'),
            array('type' => 'WHISKY','name' => 'RUSS KAYA BLANCO','presentation' => '750','deposit_count' => '0','liquor_store_count' => '0','purchase_price' => '0.000000','sale_price' => '10.000000'),
            array('type' => 'WHISKY','name' => 'RUSS KAYA NARANJA','presentation' => '750','deposit_count' => '0','liquor_store_count' => '0','purchase_price' => '0.000000','sale_price' => '10.000000'),
            array('type' => 'WHISKY','name' => 'RUSS KAYA PINK','presentation' => '750','deposit_count' => '0','liquor_store_count' => '0','purchase_price' => '0.000000','sale_price' => '10.000000'),
            array('type' => 'WHISKY','name' => 'RUSS KAYA ROJO','presentation' => '750','deposit_count' => '0','liquor_store_count' => '0','purchase_price' => '0.000000','sale_price' => '10.000000'),
            array('type' => 'WHISKY','name' => 'RUSS KAYA TRICOLOR','presentation' => '750','deposit_count' => '0','liquor_store_count' => '0','purchase_price' => '0.000000','sale_price' => '10.000000'),
            array('type' => 'WHISKY','name' => 'RUSS KAYA VERDE','presentation' => '750','deposit_count' => '0','liquor_store_count' => '0','purchase_price' => '0.000000','sale_price' => '10.000000'),
            array('type' => 'COCKTAIL','name' => 'SAN MIGUEL DAIQUIRI ROJO','presentation' => '750','deposit_count' => '5','liquor_store_count' => '1','purchase_price' => '5.830000','sale_price' => '7.000000'),
            array('type' => 'COCKTAIL','name' => 'SAN MIGUEL MOJITO VERDE','presentation' => '750','deposit_count' => '2','liquor_store_count' => '2','purchase_price' => '8.330000','sale_price' => '10.000000'),
            array('type' => 'COCKTAIL','name' => 'SAN MIGUEL PEACH NARANJA','presentation' => '750','deposit_count' => '0','liquor_store_count' => '0','purchase_price' => '0.000000','sale_price' => '9.000000'),
            array('type' => 'RON','name' => 'SAN MIGUEL SILVER','presentation' => '750','deposit_count' => '0','liquor_store_count' => '1','purchase_price' => '7.500000','sale_price' => '9.000000'),
            array('type' => 'VODKA','name' => 'SIBERIAN NEUTRO (AZUL)','presentation' => '750','deposit_count' => '3','liquor_store_count' => '4','purchase_price' => '6.250000','sale_price' => '7.500000'),
            array('type' => 'VODKA','name' => 'SIBERIAN ROJO','presentation' => '750','deposit_count' => '8','liquor_store_count' => '0','purchase_price' => '5.830000','sale_price' => '7.000000'),
            array('type' => 'VODKA','name' => 'SIBERIAN GREEN APPLE','presentation' => '750','deposit_count' => '8','liquor_store_count' => '14','purchase_price' => '5.470000','sale_price' => '7.000000'),
            array('type' => 'VODKA','name' => 'SKYY','presentation' => '750','deposit_count' => '6','liquor_store_count' => '0','purchase_price' => '11.660000','sale_price' => '14.000000'),
            array('type' => 'VINO','name' => 'SMIRNOFF','presentation' => '750','deposit_count' => '0','liquor_store_count' => '0','purchase_price' => '0.000000','sale_price' => '12.000000'),
            array('type' => 'WHISKY','name' => 'JHONNY SWING','presentation' => '750','deposit_count' => '2','liquor_store_count' => '2','purchase_price' => '71.777250','sale_price' => '88.000000'),
            array('type' => 'AGUARDIENTE','name' => 'TAPA ROJA','presentation' => '750','deposit_count' => '0','liquor_store_count' => '0','purchase_price' => '0.000000','sale_price' => '14.000000'),
            array('type' => 'VINO','name' => 'TINTO TOCORNAL CARMENERE (NARANJA)','presentation' => '750','deposit_count' => '0','liquor_store_count' => '1','purchase_price' => '5.800000','sale_price' => '7.000000'),
            array('type' => 'VINO','name' => 'TOCORNAL CABERNET SAUVIGNON','presentation' => '750','deposit_count' => '5','liquor_store_count' => '0','purchase_price' => '5.150000','sale_price' => '7.000000'),
            array('type' => 'VINO','name' => 'TOCORNAL MERLOT','presentation' => '750','deposit_count' => '0','liquor_store_count' => '0','purchase_price' => '5.830000','sale_price' => '7.000000'),
            array('type' => 'VINO','name' => 'TOCORNAL MERLOT MORADO','presentation' => '750','deposit_count' => '1','liquor_store_count' => '0','purchase_price' => '0.000000','sale_price' => '10.000000'),
            array('type' => 'AGUARDIENTE','name' => 'TROPICO SECO','presentation' => '750','deposit_count' => '6','liquor_store_count' => '0','purchase_price' => '6.250000','sale_price' => '7.500000'),
            array('type' => 'VINO','name' => 'UNDURRAGA','presentation' => '750','deposit_count' => '0','liquor_store_count' => '0','purchase_price' => '0.000000','sale_price' => '10.000000'),
            array('type' => 'WHISKY','name' => 'VAT 69','presentation' => '750','deposit_count' => '0','liquor_store_count' => '0','purchase_price' => '0.000000','sale_price' => '15.000000'),
            array('type' => 'RON','name' => 'VIEJO DE CALDAS','presentation' => '750','deposit_count' => '2','liquor_store_count' => '2','purchase_price' => '11.660000','sale_price' => '14.000000'),
            array('type' => 'ESPUMANTE','name' => 'VIÑA DEL MAR','presentation' => '750','deposit_count' => '0','liquor_store_count' => '0','purchase_price' => '0.000000','sale_price' => '4.000000'),
            array('type' => 'VINO','name' => 'VIÑA MAIPO','presentation' => '750','deposit_count' => '6','liquor_store_count' => '0','purchase_price' => '6.660000','sale_price' => '8.000000'),
            array('type' => 'WHISKY','name' => 'WILLIAM LAWSONS','presentation' => '750','deposit_count' => '0','liquor_store_count' => '0','purchase_price' => '0.000000','sale_price' => '15.000000'),
            array('type' => 'AGUARDIENTE','name' => 'ZHUMIR CRANBERRY','presentation' => '750','deposit_count' => '0','liquor_store_count' => '0','purchase_price' => '0.000000','sale_price' => '4.500000'),
            array('type' => 'AGUARDIENTE','name' => 'ZHUMIR DURAZNO','presentation' => '750','deposit_count' => '6','liquor_store_count' => '0','purchase_price' => '3.580000','sale_price' => '4.500000'),
            array('type' => 'AGUARDIENTE','name' => 'ZHUMIR PINK','presentation' => '750','deposit_count' => '11','liquor_store_count' => '0','purchase_price' => '7.500000','sale_price' => '9.000000'),
            array('type' => 'AGUARDIENTE','name' => 'ZHUMIR SECA SUAVE','presentation' => '750','deposit_count' => '11','liquor_store_count' => '0','purchase_price' => '3.325000','sale_price' => '4.500000'),
            array('type' => 'WHISKY','name' => 'SOMETHING SPECIAL','presentation' => '750','deposit_count' => '17','liquor_store_count' => '5','purchase_price' => '15.500000','sale_price' => '18.500000'),
            array('type' => 'WHISKY','name' => 'JACK DANIEL"S OLD N ° 7','presentation' => '750','deposit_count' => '3','liquor_store_count' => '1','purchase_price' => '54.160000','sale_price' => '65.000000'),
            array('type' => 'VINO','name' => 'ANTHONY FRAMBUESA','presentation' => '750','deposit_count' => '1','liquor_store_count' => '1','purchase_price' => '6.160000','sale_price' => '8.000000'),
            array('type' => 'WHISKY','name' => 'BLACK KING GREEN APPLE','presentation' => '750','deposit_count' => '0','liquor_store_count' => '0','purchase_price' => '0.000000','sale_price' => '5.500000'),
            array('type' => 'VINO','name' => 'BLANCO LIEBFRAUMILCH PETER METER','presentation' => '750','deposit_count' => '1','liquor_store_count' => '0','purchase_price' => '5.400000','sale_price' => '7.000000'),
            array('type' => 'VODKA','name' => 'RUSSOCK NARANJILLA','presentation' => '750','deposit_count' => '0','liquor_store_count' => '1','purchase_price' => '3.540000','sale_price' => '4.250000'),
            array('type' => 'VODKA','name' => 'RUSSOCK SANDÍA','presentation' => '750','deposit_count' => '0','liquor_store_count' => '0','purchase_price' => '3.540000','sale_price' => '4.250000'),
            array('type' => 'WHISKY','name' => 'SPECIAL QUEEN','presentation' => '750','deposit_count' => '54','liquor_store_count' => '17','purchase_price' => '4.370000','sale_price' => '5.750000'),
            array('type' => 'VINO','name' => 'VIEJO VIÑEDO','presentation' => '750','deposit_count' => '0','liquor_store_count' => '1','purchase_price' => '3.750000','sale_price' => '4.500000'),
            array('type' => 'RON','name' => 'POM PON PON','presentation' => '750','deposit_count' => '1','liquor_store_count' => '2','purchase_price' => '3.350000','sale_price' => '4.250000'),
            array('type' => 'COCKTAIL','name' => 'MAZERATTO LICOR CREMA SABOR A WHISKY','presentation' => '750','deposit_count' => '2','liquor_store_count' => '3','purchase_price' => '6.000000','sale_price' => '7.500000'),
            array('type' => 'WHISKY','name' => 'JHONNIE ROJO MAS IMPERIAL','presentation' => '750','deposit_count' => '0','liquor_store_count' => '0','purchase_price' => '0.000000','sale_price' => '24.500000'),
            array('type' => 'WHISKY','name' => 'JHONNIE DORADO CON COPA','presentation' => '750','deposit_count' => '0','liquor_store_count' => '0','purchase_price' => '0.000000','sale_price' => '100.000000'),
            array('type' => 'COCKTAIL','name' => 'PIÑA COLADA SUMMER LOVE','presentation' => '750','deposit_count' => '3','liquor_store_count' => '0','purchase_price' => '5.850000','sale_price' => '7.250000'),
            array('type' => 'COCKTAIL','name' => 'PIÑA COLADA BAMBUCA','presentation' => '750','deposit_count' => '3','liquor_store_count' => '3','purchase_price' => '5.750000','sale_price' => '7.000000'),
            array('type' => 'RON','name' => '100 FUEGOS','presentation' => '750','deposit_count' => '0','liquor_store_count' => '0','purchase_price' => '0.000000','sale_price' => '13.000000'),
            array('type' => 'WHISKY','name' => 'JACK DANIELS HONEY CON VASO','presentation' => '750','deposit_count' => '0','liquor_store_count' => '3','purchase_price' => '54.160000','sale_price' => '65.000000'),
            array('type' => 'TEQUILA','name' => 'AZTECA BLANCO','presentation' => '750','deposit_count' => '19','liquor_store_count' => '4','purchase_price' => '12.990000','sale_price' => '16.000000'),
            array('type' => 'WHISKY','name' => 'BUCHANANS MASTER','presentation' => '750','deposit_count' => '4','liquor_store_count' => '3','purchase_price' => '54.730000','sale_price' => '66.000000'),
            array('type' => 'WHISKY','name' => 'BLACK AND WHITE CON 2 VASOS','presentation' => '750','deposit_count' => '16','liquor_store_count' => '3','purchase_price' => '12.400000','sale_price' => '15.000000'),
            array('type' => 'RON','name' => 'SAN MIGUEL ORO MAS COLA 1 L','presentation' => '750','deposit_count' => '2','liquor_store_count' => '2','purchase_price' => '7.850000','sale_price' => '10.000000'),
            array('type' => 'VINO','name' => 'BLANCO LIEFRAUMILCH KLAUS LANGHOFF','presentation' => '750','deposit_count' => '66','liquor_store_count' => '14','purchase_price' => '5.350000','sale_price' => '7.000000'),
            array('type' => 'WHISKY','name' => 'JHONNIE ROJO CAJA POR 2 UNIDADES','presentation' => '750','deposit_count' => '3','liquor_store_count' => '0','purchase_price' => '37.500000','sale_price' => '45.000000'),
            array('type' => 'AGUARDIENTE','name' => 'ZHUMIR DE COCO','presentation' => '750','deposit_count' => '8','liquor_store_count' => '0','purchase_price' => '3.590000','sale_price' => '4.500000'),
            array('type' => 'AGUARDIENTE','name' => 'CRISTAL DURAZNO','presentation' => '750','deposit_count' => '3','liquor_store_count' => '0','purchase_price' => '4.250000','sale_price' => '5.500000'),
            array('type' => 'TEQUILA','name' => 'GUERO','presentation' => '750','deposit_count' => '1','liquor_store_count' => '3','purchase_price' => '3.522500','sale_price' => '5.000000'),
            array('type' => 'VODKA','name' => 'RUSO NEGRO (BOTELLA ROJA)','presentation' => '750','deposit_count' => '4','liquor_store_count' => '3','purchase_price' => '3.192500','sale_price' => '4.500000'),
            array('type' => 'VODKA','name' => 'RUSO NEGRO BLUEBERRY','presentation' => '750','deposit_count' => '4','liquor_store_count' => '3','purchase_price' => '3.095800','sale_price' => '4.500000'),
            array('type' => 'VINO','name' => 'CONO SUR BICICLETA CABERNET SAUVIGNON','presentation' => '750','deposit_count' => '3','liquor_store_count' => '0','purchase_price' => '8.333300','sale_price' => '10.000000'),
            array('type' => 'VINO','name' => 'CONO SUR BICICLETA MERLOT','presentation' => '750','deposit_count' => '3','liquor_store_count' => '0','purchase_price' => '8.333300','sale_price' => '10.000000'),
            array('type' => 'VINO','name' => 'LAMBRUSCO DULCE (TINTO) ANTONIO MACCIERI','presentation' => '750','deposit_count' => '8','liquor_store_count' => '2','purchase_price' => '8.333300','sale_price' => '10.000000'),
            array('type' => 'VINO','name' => 'CHARLI LAMBRUSCO BLANCO','presentation' => '750','deposit_count' => '0','liquor_store_count' => '0','purchase_price' => '5.833300','sale_price' => '7.000000'),
            array('type' => 'VINO','name' => 'CHARLI LAMBRUSCO ROSADO','presentation' => '750','deposit_count' => '0','liquor_store_count' => '0','purchase_price' => '5.833300','sale_price' => '7.000000'),
            array('type' => 'VINO','name' => 'CHARLI LAMBRUSCO TINTO','presentation' => '750','deposit_count' => '0','liquor_store_count' => '0','purchase_price' => '5.833300','sale_price' => '7.000000'),
            array('type' => 'VINO','name' => 'ANTHONY FRESA','presentation' => '750','deposit_count' => '4','liquor_store_count' => '1','purchase_price' => '6.290000','sale_price' => '8.000000'),
            array('type' => 'VINO','name' => 'ANTHONY MANZANA','presentation' => '750','deposit_count' => '0','liquor_store_count' => '1','purchase_price' => '6.650800','sale_price' => '7.500000'),
            array('type' => 'VINO','name' => 'ANTHONY SANGRÍA','presentation' => '750','deposit_count' => '0','liquor_store_count' => '1','purchase_price' => '6.540000','sale_price' => '7.500000'),
            array('type' => 'VINO','name' => 'ANTHONY BLANCO','presentation' => '750','deposit_count' => '2','liquor_store_count' => '1','purchase_price' => '6.530000','sale_price' => '7.500000'),
            array('type' => 'VINO','name' => 'TINTO BALDORE','presentation' => '750','deposit_count' => '7','liquor_store_count' => '7','purchase_price' => '3.330000','sale_price' => '4.000000'),
            array('type' => 'VINO','name' => 'TOCORNAL RED BLEND','presentation' => '750','deposit_count' => '47','liquor_store_count' => '12','purchase_price' => '4.496500','sale_price' => '6.000000'),
            array('type' => 'TEQUILA','name' => 'AZTECA SILVER GUITARRA','presentation' => '800','deposit_count' => '2','liquor_store_count' => '3','purchase_price' => '14.750000','sale_price' => '18.000001'),
            array('type' => 'WHISKY','name' => 'PREMIUM TRIBUTE GUITARRA','presentation' => '800','deposit_count' => '2','liquor_store_count' => '1','purchase_price' => '13.750000','sale_price' => '17.000000'),
            array('type' => 'WHISKY','name' => 'BACARDI CARTA BLANCA','presentation' => '980','deposit_count' => '1','liquor_store_count' => '2','purchase_price' => '14.580000','sale_price' => '17.500000'),
            array('type' => 'AGUARDIENTE','name' => 'ANTIOQUEÑO AZUL','presentation' => '1000','deposit_count' => '19','liquor_store_count' => '0','purchase_price' => '15.000000','sale_price' => '17.000000'),
            array('type' => 'WHISKY','name' => 'CARTAGO','presentation' => '1000','deposit_count' => '0','liquor_store_count' => '0','purchase_price' => '5.500000','sale_price' => '6.600000'),
            array('type' => 'TEQUILA','name' => 'CARTAVIO BLANCO','presentation' => '1000','deposit_count' => '0','liquor_store_count' => '0','purchase_price' => '0.000000','sale_price' => '7.000000'),
            array('type' => 'RON','name' => 'DON CASTELO','presentation' => '1000','deposit_count' => '19','liquor_store_count' => '9','purchase_price' => '3.780000','sale_price' => '5.000000'),
            array('type' => 'SANGRIA','name' => 'FIESTA BRAVA','presentation' => '1000','deposit_count' => '3','liquor_store_count' => '1','purchase_price' => '4.350000','sale_price' => '5.500000'),
            array('type' => 'WHISKY','name' => 'GRAND OLD PAR','presentation' => '1000','deposit_count' => '0','liquor_store_count' => '0','purchase_price' => '51.660000','sale_price' => '62.000000'),
            array('type' => 'WHISKY','name' => 'JOHN MORRIS BLACK','presentation' => '1000','deposit_count' => '4','liquor_store_count' => '7','purchase_price' => '10.600000','sale_price' => '14.000000'),
            array('type' => 'WHISKY','name' => 'JOHN MORRIS BLUE','presentation' => '1000','deposit_count' => '0','liquor_store_count' => '0','purchase_price' => '0.000000','sale_price' => '15.000000'),
            array('type' => 'WHISKY','name' => 'JHONNIE ROJO','presentation' => '1000','deposit_count' => '21','liquor_store_count' => '2','purchase_price' => '24.610000','sale_price' => '33.000000'),
            array('type' => 'WHISKY','name' => 'LABEL 5','presentation' => '1000','deposit_count' => '0','liquor_store_count' => '0','purchase_price' => '0.000000','sale_price' => '14.000000'),
            array('type' => 'WHISKY','name' => 'OLD TIMES RED','presentation' => '1000','deposit_count' => '0','liquor_store_count' => '0','purchase_price' => '0.000000','sale_price' => '12.000000'),
            array('type' => 'WHISKY','name' => 'ROYAL BLEND','presentation' => '1000','deposit_count' => '8','liquor_store_count' => '0','purchase_price' => '7.920000','sale_price' => '9.500000'),
            array('type' => 'VODKA','name' => 'RUSSOCK GUARANA AZUL','presentation' => '1000','deposit_count' => '39','liquor_store_count' => '12','purchase_price' => '4.160000','sale_price' => '5.000000'),
            array('type' => 'VODKA','name' => 'RUSSOCK BLANCO','presentation' => '1000','deposit_count' => '12','liquor_store_count' => '0','purchase_price' => '4.160000','sale_price' => '5.000000'),
            array('type' => 'VODKA','name' => 'RUSSOCK STRAWBERRY ROSA','presentation' => '1000','deposit_count' => '11','liquor_store_count' => '1','purchase_price' => '4.160000','sale_price' => '5.000000'),
            array('type' => 'WHISKY','name' => 'ZANDER','presentation' => '1000','deposit_count' => '20','liquor_store_count' => '12','purchase_price' => '4.030000','sale_price' => '5.000000'),
            array('type' => 'WHISKY','name' => 'JHONNIE NEGRO','presentation' => '1000','deposit_count' => '0','liquor_store_count' => '0','purchase_price' => '0.000000','sale_price' => '75.000000'),
            array('type' => 'WHISKY','name' => 'SOMETHING SPECIAL','presentation' => '1000','deposit_count' => '13','liquor_store_count' => '8','purchase_price' => '19.300000','sale_price' => '23.500000'),
            array('type' => 'WHISKY','name' => 'GLENGOLD GREEN APPLE','presentation' => '1000','deposit_count' => '4','liquor_store_count' => '2','purchase_price' => '6.000000','sale_price' => '7.500000'),
            array('type' => 'WHISKY','name' => 'GLENGOLD PINEAPPLE','presentation' => '1000','deposit_count' => '4','liquor_store_count' => '2','purchase_price' => '6.000000','sale_price' => '7.500000'),
            array('type' => 'WHISKY','name' => 'GLENGOLD','presentation' => '1000','deposit_count' => '0','liquor_store_count' => '2','purchase_price' => '5.500000','sale_price' => '7.000000'),
            array('type' => 'TEQUILA','name' => 'CARTAGO','presentation' => '1000','deposit_count' => '1','liquor_store_count' => '0','purchase_price' => '5.830000','sale_price' => '7.000000'),
            array('type' => 'WHISKY','name' => 'MR ALLEN','presentation' => '1000','deposit_count' => '47','liquor_store_count' => '9','purchase_price' => '4.500000','sale_price' => '5.750000'),
            array('type' => 'WHISKY','name' => 'SANDY MAC','presentation' => '1000','deposit_count' => '4','liquor_store_count' => '1','purchase_price' => '19.950000','sale_price' => '24.000000'),
            array('type' => 'WHISKY','name' => 'GENIO','presentation' => '1000','deposit_count' => '15','liquor_store_count' => '13','purchase_price' => '3.750000','sale_price' => '5.000000'),
            array('type' => 'VODKA','name' => 'SWITCH','presentation' => '1400','deposit_count' => '0','liquor_store_count' => '18','purchase_price' => '2.210000','sale_price' => '3.000000'),
            array('type' => 'COCKTAIL','name' => 'VENETTO 1500ML','presentation' => '1500','deposit_count' => '0','liquor_store_count' => '0','purchase_price' => '2.920000','sale_price' => '3.500000'),
            array('type' => '(OTRO TIPO)','name' => 'AGUA TONICA','presentation' => '200','deposit_count' => '0','liquor_store_count' => '3','purchase_price' => '1.770000','sale_price' => '2.500000')
        );        
    }
}
