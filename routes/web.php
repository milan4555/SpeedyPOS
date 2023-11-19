<?php

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

Route::get('/cashRegister', [\App\Http\Controllers\ProductController::class, 'cashRegisterItems']);

//CashRegister routes end
