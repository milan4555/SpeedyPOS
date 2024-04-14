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
});
//Auth routes start

Route::post('/login', [\App\Http\Controllers\UserController::class, 'login']);
Route::post('/logout', [\App\Http\Controllers\UserController::class, 'logout']);

//Auth routes end

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index']); //(Home route)

//CashRegister routes

Route::match(array('GET', 'POST'), '/cashRegister', [\App\Http\Controllers\CashRegisterItemController::class, 'getItems']);
Route::get('/cashRegister/empty', [\App\Http\Controllers\CashRegisterItemController::class, 'emptyCashRegister']);
Route::get('/cashRegister/deleteItem/{cashRegisterNumber}/{productId}', [\App\Http\Controllers\CashRegisterItemController::class, 'itemDelete']);
Route::get('/cashRegister/makeReceipt/{paymentType}/{cashGiven}', [\App\Http\Controllers\ReceiptController::class, 'makeReceipt']);
Route::post('/cashRegister/changeQuantity', [\App\Http\Controllers\CashRegisterItemController::class, 'changeQuantity']);
Route::get('/cashRegister/changeCompany', [\App\Http\Controllers\CompanyController::class, 'addToCurrentCart']);

//UserTimeLog routes start

Route::get('/cashRegister/open/{employeeId}', [\App\Http\Controllers\UserTimeLogController::class, 'openCashRegister']);
Route::get('/cashRegister/close/{employeeId}', [\App\Http\Controllers\UserTimeLogController::class, 'closeCashRegister']);
Route::get('/cashRegister/haveABreak/{employeeId}', [\App\Http\Controllers\UserTimeLogController::class, 'haveABreak']);
Route::get('/cashRegister/closeBreak/{employeeId}', [\App\Http\Controllers\UserTimeLogController::class, 'closeBreak']);

//UserTimeLog routes end

Route::get('/cashRegister/closeDay', [\App\Http\Controllers\DailyCloseController::class, 'closeDay']);

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

//Storage routes start

Route::get('/storage/menu', function () {return view('storage.storageMenu');});

//StorageProductPage routes start

Route::match(array('GET', 'POST'), '/storage/productsList', [\App\Http\Controllers\ProductController::class, 'showProductsPage']);
Route::post('/storage/addProduct', [\App\Http\Controllers\ProductController::class, 'addProduct']);
Route::post('/storage/updateProduct', [\App\Http\Controllers\ProductController::class, 'updateProduct']);

//StorageProductPage routes end

//StorageStorageUnits routes start

Route::get('/storage/storageUnits/{storageId}', [\App\Http\Controllers\StorageUnitController::class, 'showStorageUnit']);
Route::get('/storage/storageUnit/{storageId}/{letter}', [\App\Http\Controllers\StorageUnitController::class, 'showStorageUnitRow']);
Route::get('/storage/storageUnit/{storageId}/{letter}/{height}/{width}', [\App\Http\Controllers\StorageUnitController::class, 'showStorageUnitItems']);
Route::post('/storage/storageUnits/add', [\App\Http\Controllers\StorageUnitController::class, 'addStorageUnit']);
Route::get('/storage/print/{labelType}/{storageId}/{letter?}/{width?}/{height?}', [\App\Http\Controllers\StorageUnitController::class, 'printStorageLabels']);

//StorageStorageUnits routes end

//ProductIn routes start

Route::get('/storage/productIn', [\App\Http\Controllers\ProductInOutController::class, 'loadPage']);
Route::get('/storage/productIn/addRow/{productIdentifier}', [\App\Http\Controllers\ProductInOutController::class, 'addNewRow']);
Route::get('/storage/productIn/changeQuantity/{productId}/{quantity}', [\App\Http\Controllers\ProductInOutController::class, 'changeQuantity']);
Route::get('/storage/productIn/changeBPrice/{productId}/{bPrice}', [\App\Http\Controllers\ProductInOutController::class, 'changeBPrice']);
Route::get('/storage/productIn/addSupplier/{supplierId}', [\App\Http\Controllers\ProductInOutController::class, 'addSupplier']);
Route::get('/storage/productIn/removeRow/{productCode}', [\App\Http\Controllers\ProductInOutController::class, 'removeRow']);
Route::get('/storage/productIn/finish', [\App\Http\Controllers\ProductInOutController::class, 'finish']);
Route::get('/storage/productIn/fullDelete', [\App\Http\Controllers\ProductInOutController::class, 'fullDelete']);

//ProductIn routes end

//ProductOut routes start

Route::get('/storage/productOut/selector', [\App\Http\Controllers\ProductOutController::class, 'loadPage']);
Route::get('/storage/productOut/orders/{orderNumber}', [\App\Http\Controllers\ProductOutController::class, 'loadOrderPage']);
Route::get('/storage/productOut/orders/{orderNumber}/{productId}', [\App\Http\Controllers\ProductOutController::class, 'foundProduct']);
Route::get('/storage/productOut/restoreProgress/{orderNumber}', [\App\Http\Controllers\ProductOutController::class, 'restoreProgress']);
Route::get('/storage/productOut/finishOrder/{orderNumber}', [\App\Http\Controllers\ProductOutController::class, 'finishOrder']);
Route::get('/storage/productOut/completedOrders', [\App\Http\Controllers\ProductOutController::class, 'completedOrders']);

//ForStore route start

Route::get('/storage/productOut/forStore', [\App\Http\Controllers\ProductOutController::class, 'forStore']);
Route::post('/storage/productOut/forStore/addProductToList', [\App\Http\Controllers\ProductOutController::class, 'addProductToList']);
Route::get('/storage/productOut/forStore/remove/{orderItemId}', [\App\Http\Controllers\ProductOutController::class, 'forStoreRemoveRow']);
Route::get('/storage/productOut/forStore/update/{orderItemId}/{quantity}', [\App\Http\Controllers\ProductOutController::class, 'forStoreUpdateRow']);
Route::get('/storage/productOut/forStore/restart', [\App\Http\Controllers\ProductOutController::class, 'forStoreRestart']);

//ForStore route end

//ProductOut routes end

//ProductBreak/Move routes start

Route::match(['GET', 'POST'], '/storage/productBreak/getProduct/{productId?}', [\App\Http\Controllers\StoragePlaceController::class, 'loadPage']);
Route::post('/storage/productBreak/addRow', [\App\Http\Controllers\StoragePlaceController::class, 'addProductBreakRow']);
Route::post('/storage/productMove/addRow', [\App\Http\Controllers\StoragePlaceController::class, 'updateProductMoveRow']); //(ProductMove route)

//ProductBreak/Move routes end

//UnassignedProducts routes start

Route::get('/storage/unassignedProducts', [\App\Http\Controllers\StoragePlaceController::class, 'loadUnassignedProductsPage']);
Route::get('/storage/assignProduct/{productId}/{storagePlace}', [\App\Http\Controllers\StoragePlaceController::class, 'assignProduct']);

//UnassignedProducts routes end

//InventoryPage routes start

Route::get('/storage/inventory/{storageId}', [\App\Http\Controllers\InventoryController::class, 'showStorageUnitInventory']);
Route::get('/storage/inventoryFullReset/{storageId}', [\App\Http\Controllers\InventoryController::class, 'fullReset']);
Route::get('/storage/inventoryInput/{storageId}/{productId}', [\App\Http\Controllers\InventoryController::class, 'checkProductId']);
Route::get('/storage/inventoryHelper/{storageId}/{productId}', [\App\Http\Controllers\InventoryController::class, 'helperRoute']);
Route::get('/storage/inventoryChangeQuantity/{rowId}/{quantity}', [\App\Http\Controllers\InventoryController::class, 'updateChangedQuantity']);
Route::get('/storage/inventoryUnFindItem/{storagePlaceId}', [\App\Http\Controllers\InventoryController::class, 'unFindItem']);
Route::get('/storage/inventoryMakePDF/{storageUnitId}', [\App\Http\Controllers\InventoryController::class, 'makeInventoryPdfView']);

//InventoryPage routes end

//RiportPage routes start

Route::get('/storage/riportPage', [\App\Http\Controllers\RiportController::class, 'loadPageDefault']);
Route::match(['GET', 'POST'],'/storage/riportPage/salesRiport', [\App\Http\Controllers\RiportController::class, 'salesRiport']);
Route::match(['GET', 'POST'],'/storage/riportPage/salesRiportAll', [\App\Http\Controllers\RiportController::class, 'salesRiportAll']);

//RiportPage routes end

//Documents routes start

Route::get('/storage/documentsMenu', [\App\Http\Controllers\PDFController::class, 'loadSelector']);
Route::get('/storage/documents/{PDFtype}', [\App\Http\Controllers\PDFController::class, 'getAllPDFByType']);
Route::post('/storage/documents/getByDate/{PDFtype}', [\App\Http\Controllers\PDFController::class, 'getAllPDFByDate']);

//Documents routes end

//Storage routes end
