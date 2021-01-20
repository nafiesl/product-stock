<?php

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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

/*
 * Products Routes
 */
Route::resource('products', App\Http\Controllers\ProductController::class);
Route::post('/products/{product}/stocks', [App\Http\Controllers\Products\StockController::class, 'store'])->name('products.stocks.store');

/*
 * Partners Routes
 */
Route::resource('partners', App\Http\Controllers\PartnerController::class);

/*
 * ProductUnits Routes
 */
Route::resource('product_units', App\Http\Controllers\ProductUnitController::class);
