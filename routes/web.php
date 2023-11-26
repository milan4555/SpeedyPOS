<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

//Home route
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index']);

//CashRegister routes

Route::match(array('GET', 'POST'), '/cashRegister', [\App\Http\Controllers\CashRegisterItemController::class, 'getItems']);
Route::get('/cashRegister/empty', [\App\Http\Controllers\CashRegisterItemController::class, 'emptyCashRegister']);
Route::get('/cashRegister/deleteItem/{cashRegisterNumber}/{productId}', [\App\Http\Controllers\CashRegisterItemController::class, 'itemDelete']);
Route::get('/cashRegister/makeReceipt/{paymentType}', [\App\Http\Controllers\ReceiptController::class, 'makeReceipt']);
Route::post('/cashRegister/changeQuantity', [\App\Http\Controllers\CashRegisterItemController::class, 'changeQuantity']);

//CashRegister routes end

//ProductList routes

Route::match(array('GET', 'POST'),'/cashRegister/productList', [\App\Http\Controllers\ProductController::class, 'showAllProduct']);

//ProductList routes end
