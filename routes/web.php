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
    return view('auth.login');
})->middleware('redirectIfAuth');
//Auth routes start

Route::post('/login', [\App\Http\Controllers\UserController::class, 'login']);
Route::post('/logout', [\App\Http\Controllers\UserController::class, 'logout'])->middleware('auth');

//Auth routes end

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->middleware('auth'); //(Home route)

//CashRegister routes

Route::match(array('GET', 'POST'), '/cashRegister', [\App\Http\Controllers\CashRegisterItemController::class, 'getItems'])->middleware('ByPositionGroup');
Route::get('/cashRegister/empty', [\App\Http\Controllers\CashRegisterItemController::class, 'emptyCashRegister'])->middleware('ByPositionGroup');
Route::get('/cashRegister/deleteItem/{cashRegisterNumber}/{productId}', [\App\Http\Controllers\CashRegisterItemController::class, 'itemDelete'])->middleware('ByPositionGroup');
Route::get('/cashRegister/makeReceipt/{paymentType}/{cashGiven}', [\App\Http\Controllers\ReceiptController::class, 'makeReceipt'])->middleware('ByPositionGroup');
Route::get('/cashRegister/changeQuantity/{productIds}/{value}', [\App\Http\Controllers\CashRegisterItemController::class, 'changeQuantity'])->middleware('ByPositionGroup');
Route::get('/cashRegister/changePrice/{productIds}/{value}', [\App\Http\Controllers\CashRegisterItemController::class, 'changePrice'])->middleware('ByPositionGroup');
Route::get('/cashRegister/pricePercent/{productIds}/{value}', [\App\Http\Controllers\CashRegisterItemController::class, 'pricePercent'])->middleware('ByPositionGroup');
Route::get('/cashRegister/changeCompany/{companyId}', [\App\Http\Controllers\CompanyController::class, 'addToCurrentCart'])->middleware('ByPositionGroup');

//UserTimeLog routes start

Route::get('/cashRegister/open/{employeeId}', [\App\Http\Controllers\UserTimeLogController::class, 'openCashRegister'])->middleware('ByPositionGroup');
Route::get('/cashRegister/close/{employeeId}', [\App\Http\Controllers\UserTimeLogController::class, 'closeCashRegister'])->middleware('ByPositionGroup');
Route::get('/cashRegister/haveABreak/{employeeId}', [\App\Http\Controllers\UserTimeLogController::class, 'haveABreak'])->middleware('ByPositionGroup');
Route::get('/cashRegister/closeBreak/{employeeId}', [\App\Http\Controllers\UserTimeLogController::class, 'closeBreak'])->middleware('ByPositionGroup');

//UserTimeLog routes end

Route::get('/cashRegister/closeDay', [\App\Http\Controllers\DailyCloseController::class, 'closeDay'])->middleware('ByPositionGroup');

//CashRegister routes end

//ProductList routes

Route::match(array('GET', 'POST'),'/cashRegister/productList', [\App\Http\Controllers\ProductController::class, 'showAllProduct'])->middleware('ByPositionGroup');

//ProductList routes end

//CompanyList routes

Route::match(array('GET', 'POST'), '/cashRegister/companyList', [\App\Http\Controllers\CompanyController::class, 'showAllCompany'])->middleware('ByPositionGroup');
Route::get('/cashRegister/companyList/delete/{companyId}', [\App\Http\Controllers\CompanyController::class, 'deleteCompany'])->middleware('ByPositionGroup');
Route::post('/cashRegister/companyList/edit', [\App\Http\Controllers\CompanyController::class, 'editCompany'])->middleware('ByPositionGroup');
Route::match(array('GET', 'POST'), '/cashRegister/companyList/newCompany', [\App\Http\Controllers\CompanyController::class, 'newCompany'])->middleware('ByPositionGroup');

//CompanyList routes end

//ReceiptList routes start

Route::match(array('GET', 'POST'),'/cashRegister/receiptList', [\App\Http\Controllers\ReceiptController::class, 'showReceipt'])->middleware('ByPositionGroup');

//ReceiptList routes end

//Setting routes start

Route::match(array('GET', 'POST'), '/settings/variables', [\App\Http\Controllers\VariableController::class, 'getAllVariables'])->middleware('adminGroup');
Route::post('/saveSettings', [\App\Http\Controllers\VariableController::class, 'updateVariables'])->middleware('adminGroup');
Route::match(array('GET', 'POST'),'/settings/newEmployee', [\App\Http\Controllers\UserController::class, 'newEmployee'])->middleware('adminGroup');
Route::get('/settings/userRights', [\App\Http\Controllers\UserRightController::class, 'getView'])->middleware('adminGroup');
Route::get('/settings/userRights/{rightsId}/{optionName}', [\App\Http\Controllers\UserRightController::class, 'changeRight'])->middleware('adminGroup');
Route::get('/settings/profile', [\App\Http\Controllers\UserController::class, 'loadProfilePage'])->middleware('auth');
Route::post('/settings/setNewPassword', [\App\Http\Controllers\UserController::class, 'setNewPassword'])->middleware('auth');
Route::get('/settings/setDefaultPassword/{employeeId}', [\App\Http\Controllers\UserController::class, 'setDefaultPassword'])->middleware('adminGroup');
Route::get('/settings/userDelete/{employeeId}', [\App\Http\Controllers\UserController::class, 'userDelete'])->middleware('adminGroup');

//Setting routes end

//Storage routes start

Route::get('/storage/menu', function () {return view('storage.storageMenu');})->middleware('ByPositionGroup')->middleware('ByPositionGroup');

//StorageProductPage routes start

Route::match(array('GET', 'POST'), '/storage/productsList', [\App\Http\Controllers\ProductController::class, 'showProductsPage'])->middleware('ByPositionGroup');
Route::post('/storage/addProduct', [\App\Http\Controllers\ProductController::class, 'addProduct'])->middleware('ByPositionGroup');
Route::post('/storage/updateProduct', [\App\Http\Controllers\ProductController::class, 'updateProduct'])->middleware('ByPositionGroup');
Route::get('/storage/newProductCode/{productId}/{productCode}', [\App\Http\Controllers\ProductCodesController::class, 'newProductCode'])->middleware('ByPositionGroup');

//StorageProductPage routes end

//StorageStorageUnits routes start

Route::get('/storage/storageUnits/{storageId}', [\App\Http\Controllers\StorageUnitController::class, 'showStorageUnit'])->middleware('ByPositionGroup');
Route::get('/storage/storageUnit/{storageId}/{letter}', [\App\Http\Controllers\StorageUnitController::class, 'showStorageUnitRow'])->middleware('ByPositionGroup');
Route::get('/storage/storageUnit/{storageId}/{letter}/{height}/{width}', [\App\Http\Controllers\StorageUnitController::class, 'showStorageUnitItems'])->middleware('ByPositionGroup');
Route::post('/storage/storageUnits/add', [\App\Http\Controllers\StorageUnitController::class, 'addStorageUnit'])->middleware('ByPositionGroup');
Route::get('/storage/print/{labelType}/{storageId}/{letter?}/{width?}/{height?}', [\App\Http\Controllers\StorageUnitController::class, 'printStorageLabels'])->middleware('ByPositionGroup');
Route::get('/storage/searchUnit/{searchedId}', [\App\Http\Controllers\StorageUnitController::class, 'searchStorageUnit'])->middleware('ByPositionGroup');

//StorageStorageUnits routes end

//ProductIn routes start

Route::get('/storage/productIn', [\App\Http\Controllers\ProductInOutController::class, 'loadPage'])->middleware('ByPositionGroup');
Route::get('/storage/productIn/addRow/{productIdentifier}', [\App\Http\Controllers\ProductInOutController::class, 'addNewRow'])->middleware('ByPositionGroup');
Route::get('/storage/productIn/changeQuantity/{productId}/{quantity}', [\App\Http\Controllers\ProductInOutController::class, 'changeQuantity'])->middleware('ByPositionGroup');
Route::get('/storage/productIn/changeBPrice/{productId}/{bPrice}', [\App\Http\Controllers\ProductInOutController::class, 'changeBPrice'])->middleware('ByPositionGroup');
Route::get('/storage/productIn/addSupplier/{supplierId}', [\App\Http\Controllers\ProductInOutController::class, 'addSupplier'])->middleware('ByPositionGroup');
Route::get('/storage/productIn/removeRow/{productCode}', [\App\Http\Controllers\ProductInOutController::class, 'removeRow'])->middleware('ByPositionGroup');
Route::get('/storage/productIn/finish', [\App\Http\Controllers\ProductInOutController::class, 'finish'])->middleware('ByPositionGroup');
Route::get('/storage/productIn/fullDelete', [\App\Http\Controllers\ProductInOutController::class, 'fullDelete'])->middleware('ByPositionGroup');

//ProductIn routes end

//ProductOut routes start

Route::get('/storage/productOut/selector', [\App\Http\Controllers\ProductOutController::class, 'loadPage'])->middleware('ByPositionGroup');
Route::get('/storage/productOut/orders/{orderNumber}', [\App\Http\Controllers\ProductOutController::class, 'loadOrderPage'])->middleware('ByPositionGroup');
Route::get('/storage/productOut/orders/{orderNumber}/{productId}', [\App\Http\Controllers\ProductOutController::class, 'foundProduct'])->middleware('ByPositionGroup');
Route::get('/storage/productOut/restoreProgress/{orderNumber}', [\App\Http\Controllers\ProductOutController::class, 'restoreProgress'])->middleware('ByPositionGroup');
Route::get('/storage/productOut/finishOrder/{orderNumber}', [\App\Http\Controllers\ProductOutController::class, 'finishOrder'])->middleware('ByPositionGroup');
Route::get('/storage/productOut/completedOrders', [\App\Http\Controllers\ProductOutController::class, 'completedOrders'])->middleware('ByPositionGroup');

//ForStore route start

Route::get('/storage/productOut/forStore', [\App\Http\Controllers\ProductOutController::class, 'forStore'])->middleware('ByPositionGroup');
Route::post('/storage/productOut/forStore/addProductToList', [\App\Http\Controllers\ProductOutController::class, 'addProductToList'])->middleware('ByPositionGroup');
Route::get('/storage/productOut/forStore/remove/{orderItemId}', [\App\Http\Controllers\ProductOutController::class, 'forStoreRemoveRow'])->middleware('ByPositionGroup');
Route::get('/storage/productOut/forStore/update/{orderItemId}/{quantity}', [\App\Http\Controllers\ProductOutController::class, 'forStoreUpdateRow'])->middleware('ByPositionGroup');
Route::get('/storage/productOut/forStore/restart', [\App\Http\Controllers\ProductOutController::class, 'forStoreRestart'])->middleware('ByPositionGroup');

//ForStore route end

//ProductOut routes end

//ProductBreak/Move routes start

Route::match(['GET', 'POST'], '/storage/productBreak/getProduct/{productId?}', [\App\Http\Controllers\StoragePlaceController::class, 'loadPage'])->middleware('ByPositionGroup');
Route::post('/storage/productBreak/addRow', [\App\Http\Controllers\StoragePlaceController::class, 'addProductBreakRow'])->middleware('ByPositionGroup');
Route::post('/storage/productMove/addRow', [\App\Http\Controllers\StoragePlaceController::class, 'updateProductMoveRow'])->middleware('ByPositionGroup'); //(ProductMove route)

//ProductBreak/Move routes end

//UnassignedProducts routes start

Route::get('/storage/unassignedProducts', [\App\Http\Controllers\StoragePlaceController::class, 'loadUnassignedProductsPage'])->middleware('ByPositionGroup');
Route::get('/storage/assignProduct/{productId}/{storagePlace}', [\App\Http\Controllers\StoragePlaceController::class, 'assignProduct'])->middleware('ByPositionGroup');

//UnassignedProducts routes end

//InventoryPage routes start

Route::get('/storage/inventory/{storageId}', [\App\Http\Controllers\InventoryController::class, 'showStorageUnitInventory'])->middleware('ByPositionGroup');
Route::get('/storage/inventoryFullReset/{storageId}', [\App\Http\Controllers\InventoryController::class, 'fullReset'])->middleware('ByPositionGroup');
Route::get('/storage/inventoryInput/{storageId}/{productId}', [\App\Http\Controllers\InventoryController::class, 'checkProductId'])->middleware('ByPositionGroup');
Route::get('/storage/inventoryHelper/{storageId}/{productId}', [\App\Http\Controllers\InventoryController::class, 'helperRoute'])->middleware('ByPositionGroup');
Route::get('/storage/inventoryChangeQuantity/{rowId}/{quantity}', [\App\Http\Controllers\InventoryController::class, 'updateChangedQuantity'])->middleware('ByPositionGroup');
Route::get('/storage/inventoryUnFindItem/{storagePlaceId}', [\App\Http\Controllers\InventoryController::class, 'unFindItem'])->middleware('ByPositionGroup');
Route::get('/storage/inventoryMakePDF/{storageUnitId}', [\App\Http\Controllers\InventoryController::class, 'makeInventoryPdfView'])->middleware('ByPositionGroup');

//InventoryPage routes end

//RiportPage routes start

Route::get('/storage/riportPage', [\App\Http\Controllers\RiportController::class, 'loadPageDefault'])->middleware('ByPositionGroup');
Route::match(['GET', 'POST'],'/storage/riportPage/salesRiport', [\App\Http\Controllers\RiportController::class, 'salesRiport'])->middleware('ByPositionGroup');
Route::match(['GET', 'POST'],'/storage/riportPage/salesRiportAll', [\App\Http\Controllers\RiportController::class, 'salesRiportAll'])->middleware('ByPositionGroup');

//RiportPage routes end

//Documents routes start

Route::get('/storage/documentsMenu', [\App\Http\Controllers\PDFController::class, 'loadSelector'])->middleware('ByPositionGroup');
Route::get('/storage/documents/{PDFtype}', [\App\Http\Controllers\PDFController::class, 'getAllPDFByType'])->middleware('ByPositionGroup');
Route::post('/storage/documents/getByDate/{PDFtype}', [\App\Http\Controllers\PDFController::class, 'getAllPDFByDate'])->middleware('ByPositionGroup');

//Documents routes end

//Storage routes end
