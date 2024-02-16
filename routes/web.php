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
Route::get('/cashRegister/makeReceipt/{paymentType}/{cashGiven}', [\App\Http\Controllers\ReceiptController::class, 'makeReceipt']);
Route::post('/cashRegister/changeQuantity', [\App\Http\Controllers\CashRegisterItemController::class, 'changeQuantity']);
Route::get('/cashRegister/changeCompany', [\App\Http\Controllers\CompanyController::class, 'addToCurrentCart']);

//CashRegister routes end

//ProductList routes

Route::match(array('GET', 'POST'),'/cashRegister/productList', [\App\Http\Controllers\ProductController::class, 'showAllProduct']);

//ProductList routes end

//CompanyList routes

Route::match(array('GET', 'POST'), '/cashRegister/companyList', [\App\Http\Controllers\CompanyController::class, 'showAllCompany']);
Route::get('/cashRegister/companyList/delete/{companyId}', [\App\Http\Controllers\CompanyController::class, 'deleteCompany']);
Route::post('/cashRegister/companyList/edit', [\App\Http\Controllers\CompanyController::class, 'editCompany']);
Route::match(array('GET', 'POST'), '/cashRegister/companyList/newCompany', [\App\Http\Controllers\CompanyController::class, 'newCompany']);

//CompanyList routes end

//ReceiptList routes start

Route::get('/cashRegister/receiptList/{receiptId}', [\App\Http\Controllers\ReceiptController::class, 'showReceipt']);

//ReceiptList routes end

//Setting routes start

Route::match(array('GET', 'POST'), '/settings/variables', [\App\Http\Controllers\VariableController::class, 'getAllVariables']);
Route::post('/saveSettings', [\App\Http\Controllers\VariableController::class, 'updateVariables']);
Route::match(array('GET', 'POST'),'/settings/newEmployee', [\App\Http\Controllers\UserController::class, 'newEmployee']);
Route::get('/settings/userRights', [\App\Http\Controllers\UserRightController::class, 'getView']);
Route::get('/settings/userRights/{rightsId}/{optionName}', [\App\Http\Controllers\UserRightController::class, 'changeRight']);

//Setting routes end
