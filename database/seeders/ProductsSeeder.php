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
            // Product information
            $product = Product::create([
                'name' => mb_strtoupper($productData['name']),
                'type_id' => $this->types[$productData['type']],
                'presentation_id' => $this->presentations[$productData['content']],
            ]);
            // Product sale price
            SalePrice::create([
                'price' => $productData['price'],
                'units_number' => 1,
                'product_id' => $product->id
            ]);
            // Start Inventory
            $invoice = PurchaseInvoice::create([
                'number' => null,
                'comment' => null,
                'due_payment_date' => null,
                'paid' => true,
                'paid_date' => null,
                'user_id' => 1,
                'warehouse_id' => 1,
                'provider_id' => null
            ]);
            $expenseController = new StoreExpenseController;
            $expenseController->store([
                'amount' => 100,
                'unitary_purchase_price' => 10.00,
                'product_id' => $product->id,
                'invoice_id' => $invoice->id,
                'invoice_type' => PurchaseInvoice::class,
                'type_id' => MovementType::initialInventory()->id,
            ], 1);
            // Push expense in the other warehouse
            $invoice = PurchaseInvoice::create([
                'number' => null,
                'comment' => null,
                'due_payment_date' => null,
                'paid' => true,
                'paid_date' => null,
                'user_id' => 1,
                'warehouse_id' => 2,
                'provider_id' => null
            ]);
            $expenseController->store([
                'amount' => 100,
                'unitary_purchase_price' => 10.00,
                'product_id' => $product->id,
                'invoice_id' => $invoice->id,
                'invoice_type' => PurchaseInvoice::class,
                'type_id' => MovementType::purchase()->id,
            ], 2);
        }
    }

    public function defineProducts(): array
    {
        return [
            ['name' => '100 FUEGOS','type' => 'RON','content' => '750','price' => '13.00'],
            ['name' => 'ABSOLUT','type' => 'VODKA','content' => '750','price' => '25.00'],
            ['name' => 'ABUELO AÑEJO','type' => 'RON','content' => '750','price' => '12.00'],
            ['name' => 'AGUA TONICA','type' => '(OTRO TIPO)','content' => '1000','price' => '2.50'],
            ['name' => 'ALTA GAMMA','type' => 'VINO','content' => '750','price' => '5.00'],
            ['name' => 'ANTHONY FRAMBUESA LATA','type' => 'VINO','content' => '375','price' => '2.00'],
            ['name' => 'ANTHONY MORA','type' => 'VINO','content' => '750','price' => '7.50'],
            ['name' => 'ANTHONY MORA LATA','type' => 'VINO','content' => '375','price' => '2.00'],
            ['name' => 'ANTHONY ROSE','type' => 'VINO','content' => '750','price' => '7.50'],
            ['name' => 'ANTIOQUEÑO AZUL','type' => 'AGUARDIENTE','content' => '375','price' => '9.00'],
            ['name' => 'ANTIOQUEÑO AZUL','type' => 'AGUARDIENTE','content' => '750','price' => '15.00'],
            ['name' => 'ANTIOQUEÑO AZUL','type' => 'AGUARDIENTE','content' => '1000','price' => '18.50'],
            ['name' => 'ANTIOQUEÑO ROJO','type' => 'AGUARDIENTE','content' => '375','price' => '9.00'],
            ['name' => 'ANTIOQUEÑO ROJO','type' => 'AGUARDIENTE','content' => '750','price' => '14.00'],
            ['name' => 'ANTIOQUEÑO VERDE','type' => 'AGUARDIENTE','content' => '750','price' => '14.00'],
            ['name' => 'ASTILLA DE ROBLE','type' => 'WHISKY','content' => '750','price' => '6.00'],
            ['name' => 'AZTECA BLANCO','type' => 'TEQUILA','content' => '750','price' => '15.50'],
            ['name' => 'AZTECA ORO','type' => 'TEQUILA','content' => '750','price' => '15.50'],
            ['name' => 'BACARDI CARTA BLANCA','type' => 'WHISKY','content' => '750','price' => '17.50'],
            ['name' => 'BACARDI CARTA ORO','type' => 'WHISKY','content' => '750','price' => '17.00'],
            ['name' => 'BELLOW','type' => 'WHISKY','content' => '750','price' => '12.00'],
            ['name' => 'BLACK AND WHITE','type' => 'WHISKY','content' => '750','price' => '17.50'],
            ['name' => 'BLACK OWL','type' => 'WHISKY','content' => '750','price' => '15.00'],
            ['name' => 'BLACK WILLIAMS','type' => 'WHISKY','content' => '750','price' => '13.00'],
            ['name' => 'BLANCO TOCORNAL','type' => 'VINO','content' => '750','price' => '7.00'],
            ['name' => 'BLUE NUN 22K GOLD','type' => 'ESPUMANTE','content' => '750','price' => '15.00'],
            ['name' => 'BLUE NUN 24K ROSE','type' => 'ESPUMANTE','content' => '750','price' => '15.00'],
            ['name' => 'BLUE NUN RIVANER','type' => 'VINO','content' => '750','price' => '14.00'],
            ['name' => 'BOONES APPLE','type' => 'VINO','content' => '750','price' => '8.00'],
            ['name' => 'BUCHANANS DELUXE','type' => 'WHISKY','content' => '750','price' => '56.00'],
            ['name' => 'BUCHANANS MASTER','type' => 'WHISKY','content' => '750','price' => '65.00'],
            ['name' => 'CABALLO VIEJO','type' => 'RON','content' => '750','price' => '12.00'],
            ['name' => 'CALVET BORDEAUX','type' => 'VINO','content' => '750','price' => '8.00'],
            ['name' => 'CALVET BORDEAUX RESERVE','type' => 'VINO','content' => '750','price' => '13.00'],
            ['name' => 'CALVET CABERNET SAUVIGNON','type' => 'VINO','content' => '750','price' => '8.00'],
            ['name' => 'CALVET VARIETALS MERLOT','type' => 'VINO','content' => '750','price' => '9.00'],
            ['name' => 'CAÑA MANABITA NEGRA','type' => 'AGUARDIENTE','content' => '375','price' => '3.75'],
            ['name' => 'CAÑA MANABITA NEGRA','type' => 'AGUARDIENTE','content' => '750','price' => '7.00'],
            ['name' => 'CAÑA MANABITA NEGRA ESPECIAL','type' => 'AGUARDIENTE','content' => '750','price' => '12.00'],
            ['name' => 'CAÑA MANABITA ROJA','type' => 'AGUARDIENTE','content' => '375','price' => '4.25'],
            ['name' => 'CAÑA MANABITA ROJA','type' => 'AGUARDIENTE','content' => '750','price' => '8.25'],
            ['name' => 'CAÑA MANABITA ROJA ESPECIAL','type' => 'AGUARDIENTE','content' => '750','price' => '12.00'],
            ['name' => 'CAÑA MANABITA VERDE','type' => 'AGUARDIENTE','content' => '750','price' => '6.00'],
            ['name' => 'CAÑA ROSE','type' => 'AGUARDIENTE','content' => '600','price' => '6.00'],
            ['name' => 'CAPRICCIO NOVECENTO (DORADO)','type' => 'ESPUMANTE','content' => '750','price' => '12.00'],
            ['name' => 'CARTAGO','type' => 'WHISKY','content' => '1000','price' => '6.60'],
            ['name' => 'CARTAVIO BLACK','type' => 'RON','content' => '750','price' => '8.50'],
            ['name' => 'CARTAVIO BLANCO','type' => 'TEQUILA','content' => '1000','price' => '7.00'],
            ['name' => 'CARTAVIO BLANCO','type' => 'RON','content' => '750','price' => '8.50'],
            ['name' => 'CASILLERO DEL DIABLO','type' => 'VINO','content' => '750','price' => '18.00'],
            ['name' => 'CASTILLO BLANCO','type' => 'RON','content' => '750','price' => '9.00'],
            ['name' => 'CATADOR','type' => 'VINO','content' => '750','price' => '4.50'],
            ['name' => 'CHIARLI MIO BLANCO','type' => 'ESPUMANTE','content' => '750','price' => '7.00'],
            ['name' => 'CHIARLI MIO ROSADO','type' => 'ESPUMANTE','content' => '750','price' => '7.00'],
            ['name' => 'CHIARLIL MIO ROJO','type' => 'ESPUMANTE','content' => '750','price' => '7.00'],
            ['name' => 'CHIVAS REGAL ROJO','type' => 'WHISKY','content' => '750','price' => '49.00'],
            ['name' => 'CLAN MACGREGOR','type' => 'WHISKY','content' => '750','price' => '10.00'],
            ['name' => 'CREMA SABOR A WHISKY COLDS','type' => 'COCKTAIL','content' => '750','price' => '9.00'],
            ['name' => 'CRISTAL','type' => 'AGUARDIENTE','content' => '375','price' => '4.00'],
            ['name' => 'CRISTAL','type' => 'AGUARDIENTE','content' => '750','price' => '8.00'],
            ['name' => 'CRISTAL SECO','type' => 'AGUARDIENTE','content' => '375','price' => '4.00'],
            ['name' => 'CRISTAL SECO','type' => 'AGUARDIENTE','content' => '750','price' => '7.50'],
            ['name' => 'CRUZARES','type' => 'VINO','content' => '750','price' => '4.50'],
            ['name' => 'CUBANERO ORO','type' => 'RON','content' => '750','price' => '7.00'],
            ['name' => 'DIABLO','type' => 'VINO','content' => '750','price' => '26.00'],
            ['name' => 'DIXONS AZUL','type' => 'WHISKY','content' => '750','price' => '13.00'],
            ['name' => 'DIXONS VERDE','type' => 'WHISKY','content' => '750','price' => '8.00'],
            ['name' => 'DON CASTELO','type' => 'WHISKY','content' => '1000','price' => '5.50'],
            ['name' => 'EL CHARRO AGUIJON','type' => 'TEQUILA','content' => '750','price' => '13.00'],
            ['name' => 'EL CHARRO MARGARITA FRESA','type' => 'TEQUILA','content' => '750','price' => '10.00'],
            ['name' => 'EL CHARRO MARGARITA LIMON','type' => 'TEQUILA','content' => '750','price' => '10.00'],
            ['name' => 'EL CHARRO ORO','type' => 'TEQUILA','content' => '750','price' => '17.00'],
            ['name' => 'EL CHARRO REPOSADO','type' => 'TEQUILA','content' => '750','price' => '24.00'],
            ['name' => 'EL CHARRO SILVER','type' => 'TEQUILA','content' => '750','price' => '17.00'],
            ['name' => 'ESTELAR','type' => 'RON','content' => '750','price' => '8.50'],
            ['name' => 'FIESTA BRAVA','type' => 'SANGRIA','content' => '1000','price' => '5.50'],
            ['name' => 'FRAILE','type' => 'VINO','content' => '750','price' => '4.00'],
            ['name' => 'FRONTERA','type' => 'VINO','content' => '750','price' => '9.50'],
            ['name' => 'FRONTERA','type' => 'AGUARDIENTE','content' => '750','price' => '8.00'],
            ['name' => 'GATO NEGRO','type' => 'VINO','content' => '750','price' => '10.50'],
            ['name' => 'GENIO','type' => 'WHISKY','content' => '750','price' => '5.00'],
            ['name' => 'GRAND DUVAL','type' => 'ESPUMANTE','content' => '750','price' => '6.50'],
            ['name' => 'GRAND OLD PAR','type' => 'WHISKY','content' => '750','price' => '51.00'],
            ['name' => 'GRAND OLD PAR','type' => 'WHISKY','content' => '1000','price' => '62.00'],
            ['name' => 'GRAND VAN DUSH AZUL','type' => 'ESPUMANTE','content' => '750','price' => '7.25'],
            ['name' => 'GRAND VAN DUSH ROSADO','type' => 'ESPUMANTE','content' => '750','price' => '7.25'],
            ['name' => 'GRANTS AZUL','type' => 'WHISKY','content' => '750','price' => '24.00'],
            ['name' => 'GRANTS ROJO','type' => 'WHISKY','content' => '750','price' => '19.50'],
            ['name' => 'GRANTS VERDE','type' => 'WHISKY','content' => '750','price' => '38.50'],
            ['name' => 'HIGHLAND LEGEND','type' => 'WHISKY','content' => '750','price' => '8.00'],
            ['name' => 'JACK DANIELS','type' => 'WHISKY','content' => '750','price' => '65.00'],
            ['name' => 'JAGERMEITER','type' => 'WHISKY','content' => '750','price' => '30.00'],
            ['name' => 'JAMES KING','type' => 'WHISKY','content' => '700','price' => '10.00'],
            ['name' => 'JHON MORRIS BLACK','type' => 'WHISKY','content' => '1000','price' => '14.00'],
            ['name' => 'JHON MORRIS BLACK','type' => 'WHISKY','content' => '750','price' => '12.00'],
            ['name' => 'JHON MORRIS BLUE','type' => 'WHISKY','content' => '750','price' => '14.00'],
            ['name' => 'JHON MORRIS BLUE','type' => 'WHISKY','content' => '1000','price' => '15.00'],
            ['name' => 'JHONNIE DORADO','type' => 'WHISKY','content' => '1000','price' => '95.00'],
            ['name' => 'JHONNIE DOUBLE BLACK','type' => 'WHISKY','content' => '750','price' => '69.00'],
            ['name' => 'JHONNIE GREEN LABEL','type' => 'WHISKY','content' => '750','price' => '107.00'],
            ['name' => 'JHONNIE NEGRO','type' => 'WHISKY','content' => '750','price' => '60.00'],
            ['name' => 'JHONNIE NEGRO','type' => 'VODKA','content' => '1000','price' => '75.00'],
            ['name' => 'JHONNIE ROJO','type' => 'WHISKY','content' => '750','price' => '26.00'],
            ['name' => 'JHONNIE ROJO','type' => 'WHISKY','content' => '1000','price' => '32.00'],
            ['name' => 'JOHN BARR NEGRO','type' => 'WHISKY','content' => '750','price' => '15.00'],
            ['name' => 'JOHN BARR ROJO','type' => 'WHISKY','content' => '750','price' => '12.00'],
            ['name' => 'JOSE CUERVO BLANCO','type' => 'TEQUILA','content' => '750','price' => '50.00'],
            ['name' => 'JOSE CUERVO ORO','type' => 'TEQUILA','content' => '750','price' => '50.00'],
            ['name' => 'JP CHENET','type' => 'VINO','content' => '750','price' => '9.00'],
            ['name' => 'KATRINA CANNABIS','type' => 'TEQUILA','content' => '750','price' => '8.00'],
            ['name' => 'KATRINA PINK','type' => 'TEQUILA','content' => '750','price' => '8.00'],
            ['name' => 'KLAUS LANGHOFF','type' => 'VINO','content' => '750','price' => '7.00'],
            ['name' => 'LA CATEDRA','type' => 'VINO','content' => '750','price' => '4.50'],
            ['name' => 'LA PARRA','type' => 'VINO','content' => '750','price' => '4.75'],
            ['name' => 'LA VID BLEND','type' => 'VINO','content' => '750','price' => '4.50'],
            ['name' => 'LABEL 5','type' => 'WHISKY','content' => '750','price' => '11.00'],
            ['name' => 'LABEL 5','type' => 'WHISKY','content' => '1000','price' => '14.00'],
            ['name' => 'LAMBRUSCO ANTONIO MACCIERI','type' => 'ESPUMANTE','content' => '750','price' => '10.00'],
            ['name' => 'LAMBRUSCO ROJO CASSETA MARIA','type' => 'ESPUMANTE','content' => '750','price' => '8.00'],
            ['name' => 'LAMBRUSCO ROSATO ANTONIO MACCIERI','type' => 'ESPUMANTE','content' => '750','price' => '11.50'],
            ['name' => 'LAMBRUSCO ROSATO CASSETA MARIA','type' => 'ESPUMANTE','content' => '750','price' => '8.00'],
            ['name' => 'MIRAFLORES','type' => 'VINO','content' => '750','price' => '5.00'],
            ['name' => 'MONTAÑITA','type' => 'RON','content' => '750','price' => '6.00'],
            ['name' => 'MR ALLEN','type' => 'WHISKY','content' => '1000','price' => '6.00'],
            ['name' => 'NORTEÑO ESPECIAL','type' => 'AGUARDIENTE','content' => '375','price' => '4.00'],
            ['name' => 'NORTEÑO ESPECIAL','type' => 'AGUARDIENTE','content' => '750','price' => '7.00'],
            ['name' => 'NOVECENTO NIGHT','type' => 'VINO','content' => '750','price' => '10.00'],
            ['name' => 'OLD TIMES BLACK','type' => 'WHISKY','content' => '750','price' => '14.50'],
            ['name' => 'OLD TIMES RED','type' => 'WHISKY','content' => '750','price' => '10.00'],
            ['name' => 'OLD TIMES RED','type' => 'WHISKY','content' => '1000','price' => '12.00'],
            ['name' => 'PASSPORT SCOTCH','type' => 'WHISKY','content' => '750','price' => '13.50'],
            ['name' => 'PIÑA COLADA COLDS','type' => 'COCKTAIL','content' => '750','price' => '7.00'],
            ['name' => 'PIÑA COLADA ZHUMIR','type' => 'COCKTAIL','content' => '750','price' => '10.00'],
            ['name' => 'RED WILLIAMS','type' => 'WHISKY','content' => '750','price' => '10.00'],
            ['name' => 'RESERVADO AZUL (MALBEC)','type' => 'VINO','content' => '750','price' => '8.00'],
            ['name' => 'RESERVADO MORADO (MERLOT)','type' => 'VINO','content' => '750','price' => '8.00'],
            ['name' => 'RESERVADO ROJO (CABERNET SAUVIGNON)','type' => 'VINO','content' => '750','price' => '10.00'],
            ['name' => 'ROMANOSKY AZUL','type' => 'COCKTAIL','content' => '750','price' => '3.50'],
            ['name' => 'ROMANOSKY ROSA','type' => 'COCKTAIL','content' => '750','price' => '3.50'],
            ['name' => 'ROYAL BLEND','type' => 'WHISKY','content' => '750','price' => '9.50'],
            ['name' => 'RUSS KAYA AZUL','type' => 'WHISKY','content' => '750','price' => '10.00'],
            ['name' => 'RUSS KAYA BLANCO','type' => 'WHISKY','content' => '750','price' => '10.00'],
            ['name' => 'RUSS KAYA NARANJA','type' => 'WHISKY','content' => '750','price' => '10.00'],
            ['name' => 'RUSS KAYA PINK','type' => 'WHISKY','content' => '750','price' => '10.00'],
            ['name' => 'RUSS KAYA ROJO','type' => 'WHISKY','content' => '750','price' => '10.00'],
            ['name' => 'RUSS KAYA TRICOLOR','type' => 'WHISKY','content' => '750','price' => '10.00'],
            ['name' => 'RUSS KAYA VERDE','type' => 'WHISKY','content' => '750','price' => '10.00'],
            ['name' => 'RUSSOCK AZUL','type' => 'VODKA','content' => '1000','price' => '5.00'],
            ['name' => 'RUSSOCK BLANCO','type' => 'VODKA','content' => '1000','price' => '5.00'],
            ['name' => 'RUSSOCK ROSA','type' => 'VODKA','content' => '1000','price' => '5.00'],
            ['name' => 'SAN MIGUEL DAIQUIRI ROJO','type' => 'COCKTAIL','content' => '750','price' => '7.00'],
            ['name' => 'SAN MIGUEL GOLD','type' => 'RON','content' => '750','price' => '9.00'],
            ['name' => 'SAN MIGUEL MOJITO VERDE','type' => 'COCKTAIL','content' => '750','price' => '10.00'],
            ['name' => 'SAN MIGUEL PEACH NARANJA','type' => 'COCKTAIL','content' => '750','price' => '9.00'],
            ['name' => 'SAN MIGUEL SILVER','type' => 'RON','content' => '750','price' => '9.00'],
            ['name' => 'SANDY MAC','type' => 'WHISKY','content' => '750','price' => '19.00'],
            ['name' => 'SIBERIAN AZUL','type' => 'VODKA','content' => '750','price' => '7.00'],
            ['name' => 'SIBERIAN ROJO','type' => 'VODKA','content' => '750','price' => '7.00'],
            ['name' => 'SIBERIAN VERDE','type' => 'VODKA','content' => '750','price' => '7.00'],
            ['name' => 'SKYY','type' => 'VODKA','content' => '375','price' => '14.00'],
            ['name' => 'SMIRNOFF','type' => 'VINO','content' => '750','price' => '12.00'],
            ['name' => 'SOMETHING SPECIAL','type' => 'WHISKY','content' => '750','price' => '21.50'],
            ['name' => 'SOMETHING SPECIAL','type' => 'WHISKY','content' => '1000','price' => '27.00'],
            ['name' => 'SPECIAL QUEEN','type' => 'WHISKY','content' => '750','price' => '6.00'],
            ['name' => 'SWING','type' => 'WHISKY','content' => '750','price' => '83.00'],
            ['name' => 'SWITCH','type' => '(OTRO TIPO)','content' => '1000','price' => '3.00'],
            ['name' => 'TAPA ROJA','type' => 'AGUARDIENTE','content' => '750','price' => '14.00'],
            ['name' => 'TINTO TOCORNAL CARMENERE (NARANJA)','type' => 'VINO','content' => '750','price' => '7.00'],
            ['name' => 'TOCORNAL CABERNET SAUVIGNON','type' => 'VINO','content' => '750','price' => '7.00'],
            ['name' => 'TOCORNAL MERLOT','type' => 'VINO','content' => '750','price' => '7.00'],
            ['name' => 'TOCORNAL MERLOT MORADO','type' => 'VINO','content' => '750','price' => '10.00'],
            ['name' => 'TROPICO SECO','type' => 'AGUARDIENTE','content' => '375','price' => '4.00'],
            ['name' => 'TROPICO SECO','type' => 'AGUARDIENTE','content' => '750','price' => '7.50'],
            ['name' => 'UNDURRAGA','type' => 'VINO','content' => '750','price' => '10.00'],
            ['name' => 'VAT 69','type' => 'WHISKY','content' => '750','price' => '15.00'],
            ['name' => 'VENETTO 1500ML','type' => 'COCKTAIL','content' => '1500','price' => '3.50'],
            ['name' => 'VIEJO DE CALDAS','type' => 'RON','content' => '750','price' => '14.00'],
            ['name' => 'VIÑA DEL MAR','type' => 'ESPUMANTE','content' => '750','price' => '4.00'],
            ['name' => 'VIÑA MAIPO','type' => 'VINO','content' => '750','price' => '8.00'],
            ['name' => 'WILLIAM LAWSONS','type' => 'WHISKY','content' => '750','price' => '15.00'],
            ['name' => 'YACHTING AFRICAN CREAM','type' => 'COCKTAIL','content' => '700','price' => '14.00'],
            ['name' => 'YACHTING PIÑA COLADA','type' => 'COCKTAIL','content' => '700','price' => '8.00'],
            ['name' => 'ZANDER','type' => 'WHISKY','content' => '750','price' => '5.00'],
            ['name' => 'ZHUMIR CRANBERRY','type' => 'AGUARDIENTE','content' => '750','price' => '4.50'],
            ['name' => 'ZHUMIR DURAZNO','type' => 'AGUARDIENTE','content' => '750','price' => '4.50'],
            ['name' => 'ZHUMIR PINK','type' => 'AGUARDIENTE','content' => '750','price' => '9.00'],
            ['name' => 'ZHUMIR SECA SUAVE','type' => 'AGUARDIENTE','content' => '750','price' => '4.50']
        ];        
    }
}
