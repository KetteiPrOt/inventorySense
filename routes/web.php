<?php

use App\Http\Controllers\ClientController;
use App\Http\Controllers\Products\PresentationController;
use App\Http\Controllers\Products\ProductController;
use App\Http\Controllers\Products\TypeController;
use App\Http\Controllers\ProviderController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn() => redirect()->route('login'));

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

require __DIR__.'/auth.php';

Route::middleware(['auth'])->controller(ProductController::class)->group(function(){
    Route::get('/productos', 'index')->name('products.index');
    Route::get('/productos/crear', 'create')->name('products.create');
    Route::post('/productos', 'store')->name('products.store');
    Route::get('/productos/{product}', 'show')->name('products.show');
    Route::get('/productos/{product}/editar', 'edit')->name('products.edit');
    Route::put('/productos/{product}', 'update')->name('products.update');
    Route::delete('/productos/{product}', 'destroy')->name('products.destroy');
});

Route::middleware(['auth'])->controller(TypeController::class)->group(function(){
    Route::get('/tipos', 'index')->name('product-types.index');
    Route::delete('/tipos/{type}', 'destroy')->name('product-types.destroy');
});

Route::middleware(['auth'])->controller(PresentationController::class)->group(function(){
    Route::get('/presentaciones', 'index')->name('product-presentations.index');
    Route::delete('/presentaciones/{presentation}', 'destroy')->name('product-presentations.destroy');
});

Route::middleware(['auth'])->controller(ProviderController::class)->group(function(){
    Route::get('/proveedores', 'index')->name('providers.index');
    Route::get('/proveedores/crear', 'create')->name('providers.create');
    Route::post('/proveedores', 'store')->name('providers.store');
    Route::get('/proveedores/{provider}', 'show')->name('providers.show');
    Route::get('/proveedores/{provider}/editar', 'edit')->name('providers.edit');
    Route::put('/proveedores/{provider}', 'update')->name('providers.update');
    Route::delete('/proveedores/{provider}', 'destroy')->name('providers.destroy');
});

Route::middleware(['auth'])->controller(ClientController::class)->group(function(){
    Route::get('/clientes', 'index')->name('clients.index');
    Route::get('/clientes/crear', 'create')->name('clients.create');
    Route::post('/clientes', 'store')->name('clients.store');
    Route::get('/clientes/{client}', 'show')->name('clients.show');
    Route::get('/clientes/{client}/editar', 'edit')->name('clients.edit');
    Route::put('/clientes/{client}', 'update')->name('clients.update');
    Route::delete('/clientes/{client}', 'destroy')->name('clients.destroy');
});

Route::middleware(['auth'])->controller(UserController::class)->group(function(){
    Route::get('/usuarios', 'index')->name('users.index');
    Route::get('/usuarios/crear', 'create')->name('users.create');
    Route::post('/usuarios', 'store')->name('users.store');
    Route::get('/usuarios/{user}', 'show')->name('users.show');
    // Route::get('/usuarios/{users}/editar', 'edit')->name('users.edit');
    // Route::put('/usuarios/{users}', 'update')->name('users.update');
    // Route::delete('/usuarios/{users}', 'destroy')->name('users.destroy');
});
