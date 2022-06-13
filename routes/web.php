<?php

use App\Http\Controllers\Shopify\CheckoutController;
use App\Http\Middleware\ShopifyShopMiddleware;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::middleware(['verify.shopify'])->group(function () {
    Route::get('/', function () {
        return view('welcome');
    })->name('home');


});

Route::group([
    'prefix' => '/checkout',
    'middleware' => ShopifyShopMiddleware::class,
], function (): void {
    Route::get('/', [CheckoutController::class, 'checkout'])->name('checkout');
    Route::post('/shipments', [CheckoutController::class, 'getShipments'])->name('shipments');
    Route::post('/shipments/add', [CheckoutController::class, 'addShipmentMethod'])->name('addShipments');
    Route::post('/confirm', [CheckoutController::class, 'confirm'])->name('confirm');
    Route::get('/success', [CheckoutController::class, 'success'])->name('success');
});

