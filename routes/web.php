<?php

use App\Http\Controllers\ClientController;
use App\Http\Controllers\Inventory\IndexController as InventoryIndexController;
use App\Http\Controllers\Inventory\WarehouseChangeController;
use App\Http\Controllers\Invoices\Sales\Controller as SaleController;
use App\Http\Controllers\Invoices\Purchases\Controller as PurchaseController;
use App\Http\Controllers\Invoices\Purchases\KardexController;
use App\Http\Controllers\Products\PresentationController;
use App\Http\Controllers\Products\ProductController;
use App\Http\Controllers\Products\TypeController;
use App\Http\Controllers\ProviderController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WarehouseController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn() => redirect()->route('login'));

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

require __DIR__.'/auth.php';

Route::middleware(['auth', 'can:products'])->controller(ProductController::class)->group(function(){
    Route::get('/productos', 'index')->name('products.index');
    Route::get('/productos/crear', 'create')->name('products.create');
    Route::post('/productos', 'store')->name('products.store');
    Route::get('/productos/{product}', 'show')->name('products.show');
    Route::get('/productos/{product}/editar', 'edit')->name('products.edit');
    Route::put('/productos/{product}', 'update')->name('products.update');
    Route::delete('/productos/{product}', 'destroy')->name('products.destroy');
});

Route::middleware(['auth', 'can:products'])->controller(TypeController::class)->group(function(){
    Route::get('/tipos', 'index')->name('product-types.index');
    Route::delete('/tipos/{type}', 'destroy')->name('product-types.destroy');
});

Route::middleware(['auth', 'can:products'])->controller(PresentationController::class)->group(function(){
    Route::get('/presentaciones', 'index')->name('product-presentations.index');
    Route::delete('/presentaciones/{presentation}', 'destroy')->name('product-presentations.destroy');
});

Route::middleware(['auth', 'can:providers'])->controller(ProviderController::class)->group(function(){
    Route::get('/proveedores', 'index')->name('providers.index');
    Route::get('/proveedores/crear', 'create')->name('providers.create');
    Route::post('/proveedores', 'store')->name('providers.store');
    Route::get('/proveedores/{provider}', 'show')->name('providers.show');
    Route::get('/proveedores/{provider}/editar', 'edit')->name('providers.edit');
    Route::put('/proveedores/{provider}', 'update')->name('providers.update');
    Route::delete('/proveedores/{provider}', 'destroy')->name('providers.destroy');
});

Route::middleware(['auth', 'can:clients'])->controller(ClientController::class)->group(function(){
    Route::get('/clientes', 'index')->name('clients.index');
    Route::get('/clientes/crear', 'create')->name('clients.create');
    Route::post('/clientes', 'store')->name('clients.store');
    Route::get('/clientes/{client}', 'show')->name('clients.show');
    Route::get('/clientes/{client}/editar', 'edit')->name('clients.edit');
    Route::put('/clientes/{client}', 'update')->name('clients.update');
    Route::delete('/clientes/{client}', 'destroy')->name('clients.destroy');
});

Route::middleware(['auth', 'can:users'])->controller(UserController::class)->group(function(){
    Route::get('/usuarios', 'index')->name('users.index');
    Route::get('/usuarios/crear', 'create')->name('users.create');
    Route::post('/usuarios', 'store')->name('users.store');
    Route::get('/usuarios/{user}', 'show')->name('users.show');
    Route::get('/usuarios/{user}/editar', 'edit')->name('users.edit');
    Route::put('/usuarios/{user}', 'update')->name('users.update');
    Route::put('/usuarios/{user}/permisos', 'updatePermissions')->name('users.update-permissions');
    Route::delete('/usuarios/{user}', 'destroy')->name('users.destroy');
});

Route::middleware(['auth', 'can:roles'])->controller(RoleController::class)->group(function(){
    Route::get('/roles', 'index')->name('roles.index');
    Route::get('/roles/crear', 'create')->name('roles.create');
    Route::post('/roles', 'store')->name('roles.store');
    Route::get('/roles/{role}', 'show')->name('roles.show');
    Route::get('/roles/{role}/editar', 'edit')->name('roles.edit');
    Route::put('/roles/{role}', 'update')->name('roles.update');
    Route::delete('/roles/{role}', 'destroy')->name('roles.destroy');
});

Route::middleware(['auth'])->controller(PurchaseController::class)->group(function(){
    Route::middleware(['can:purchases-report'])
        ->get('/compras/consultar-reporte', 'queryIndex')->name('purchases.query-index');
    Route::middleware(['can:purchases-report'])
        ->get('/compras', 'index')->name('purchases.index');
    Route::middleware(['can:create-purchases'])
        ->get('/compras/crear', 'create')->name('purchases.create');
    Route::middleware(['can:create-purchases'])
        ->post('/compras', 'store')->name('purchases.store');
});

Route::middleware(['auth'])->controller(KardexController::class)->group(function(){
    Route::middleware(['can:kardex'])
        ->get('/compras/consultar-kardex', 'queryKardex')->name('purchases.query-kardex');
    Route::middleware(['can:kardex'])
        ->get('/compras/kardex', 'kardex')->name('purchases.kardex');
});

Route::get('/compras/{invoice}', [PurchaseController::class, 'show'])->name('purchases.show');
Route::put('/compras/{invoice}', [PurchaseController::class, 'update'])->name('purchases.update');

Route::middleware(['auth'])->controller(SaleController::class)->group(function(){
    Route::middleware(['can:sales-report'])
        ->get('/ventas/consultar-reporte', 'queryIndex')->name('sales.query-index');
    Route::middleware(['can:sales-report'])
        ->get('/ventas', 'index')->name('sales.index');
    Route::middleware(['can:create-sales'])
        ->get('/ventas/seleccionar-bodega', 'selectWarehouse')->name('sales.select-warehouse');
    Route::middleware(['can:create-sales'])
        ->post('/ventas/guardar-bodega-seleccionada', 'saveSelectedWarehouse')->name('sales.save-selected-warehouse');
    Route::middleware(['can:create-sales'])
        ->get('/ventas/crear', 'create')->name('sales.create');
    Route::middleware(['can:create-sales'])
        ->post('/ventas', 'store')->name('sales.store');
    Route::middleware(['can:cash-closing'])
        ->get('/ventas/consultar-cierre-de-caja', 'queryCashClosing')->name('sales.query-cash-closing');
    Route::middleware(['can:cash-closing'])
        ->get('/ventas/cierre-de-caja', 'cashClosing')->name('sales.cash-closing');
    Route::get('/ventas/{invoice}', 'show')->name('sales.show');
    Route::put('/ventas/{invoice}', 'update')->name('sales.update');
});

Route::middleware(['auth'])->controller(InventoryIndexController::class)->group(function(){
    Route::middleware(['can:inventory'])
        ->get('/inventario/consultar', 'queryIndex')->name('inventory.query-index');
    Route::middleware(['can:inventory'])
        ->get('/inventario', 'index')->name('inventory.index');
});

Route::middleware(['auth', 'can:inventory'])->controller(WarehouseController::class)->group(function(){
    Route::get('/bodegas', 'index')->name('warehouses.index');
    Route::get('/bodegas/crear', 'create')->name('warehouses.create');
    Route::post('/bodegas', 'store')->name('warehouses.store');
    Route::get('/bodegas/{warehouse}', 'show')->name('warehouses.show');
    Route::get('/bodegas/{warehouse}/editar', 'edit')->name('warehouses.edit');
    Route::put('/bodegas/{warehouse}', 'update')->name('warehouses.update');
    // Route::delete('/bodegas/{warehouse}', 'destroy')->name('warehouses.destroy');
});

Route::middleware(['auth', 'can:inventory'])->controller(WarehouseChangeController::class)->group(function(){
    Route::get('/cambio-de-bodega/bodegas', 'selectWarehouses')->name('warehouse-change.select-warehouses');
    Route::get('/cambio-de-bodega/productos', 'selectProducts')->name('warehouse-change.select-products');
    Route::post('/cambio-de-bodega', 'change')->name('warehouse-change.change');
});
