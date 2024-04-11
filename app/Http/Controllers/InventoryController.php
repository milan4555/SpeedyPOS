<?php

namespace App\Http\Controllers;

use App\Models\FilePath;
use App\Models\Inventory;
use App\Models\StoragePlace;
use App\Models\StorageUnit;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class InventoryController extends Controller
{
    public function showStorageUnitInventory($storageUnitId)
    {
        if ($storageUnitId == 0) {
            return view('storage.inventoryPage', [
                'storageUnits' => StorageUnit::all(),
                'selectedStorageId' => $storageUnitId
            ]);
        } else {
            $rowIds = StoragePlaceController::getAllUsedPlaces($storageUnitId);
            foreach ($rowIds as $rowId) {
                if (Inventory::where('storagePlaceId', '=', $rowId)->count() == 0) {
                    $productId = StoragePlace::find($rowId)->productId;
                    $sameProductIdsRaw = StoragePlace::where('productId', '=', $productId)->get('id')->toArray();
                    $sameProductIdsArray = [];
                    foreach ($sameProductIdsRaw as $item) {
                        $sameProductIdsArray[] = $item['id'];
                    }
                    $sameProductInventory = Inventory::whereIn('storagePlaceId', $sameProductIdsArray)->first();
                    Inventory::create(['storagePlaceId' => $rowId, 'quantityDiff' => ($sameProductInventory == null ? 0 : $sameProductInventory->quantityDiff)]);
                }
            }
            $lastUpdated = DB::table('storage_places')
                ->join('products', 'storage_places.productId', 'products.productId')
                ->join('inventories', 'storage_places.id', 'inventories.storagePlaceId')
                ->select('*')
                ->whereIn('storage_places.id', $rowIds)
                ->orderBy('inventories.updated_at', 'DESC')
                ->first();
            $products = DB::table('storage_places')
                ->join('products', 'storage_places.productId', 'products.productId')
                ->join('inventories', 'storage_places.id', 'inventories.storagePlaceId')
                ->select('*')
                ->whereIn('storage_places.id', $rowIds)
                ->whereNot('storagePlaceId', $lastUpdated->storagePlaceId)
                ->orderBy('isFound')
                ->orderBy('storage_places.productId')
                ->orderBy('index')
                ->get()
                ->toArray();
            return view('storage.inventoryPage', [
                'storageUnits' => StorageUnit::all(),
                'selectedStorageId' => $storageUnitId,
                'selectedStorage' => StorageUnit::find($storageUnitId),
                'products' => $products,
                'lastProduct' => $lastUpdated
            ]);
        }
    }

    public static function getBgColor($isFound, $changedPlace, $quantityDiff) {
        if ($quantityDiff != 0) {
            return "table-warning";
        } else if ($changedPlace) {
            return "table-info";
        } else if ($isFound) {
            return "table-success";
        }
        return "table-danger";
    }

    public function fullReset($storageUnitId) {
        $rowIds = StoragePlaceController::getAllUsedPlaces($storageUnitId);
        $changedLocations = Inventory::whereIn('storagePlaceId', $rowIds)
            ->where('oldStoragePlace', '!=', null)
            ->get()
            ->collect();
       foreach ($changedLocations as $changedLocation) {
           StoragePlace::find($changedLocation->storagePlaceId)
               ->update(['storagePlace' => $changedLocation->oldStoragePlace]);
       }
        Inventory::whereIn('storagePlaceId', $rowIds)->update([
            'isFound' => false,
            'changedPlace' => false,
            'oldStoragePlace' => null,
            'quantityDiff' => 0
        ]);

        return redirect()->back()->with('success', 'Teljesen visszállítottad a leltározást, Mostmár tiszta lappal kezdhetsz!');
    }

    public function checkProductId($storageId, $productId) {
        $explodedProductId = explode('-', $productId);
        $productRow = StoragePlace::where([['productId', '=', $explodedProductId[0]],['index', '=', $explodedProductId[1]]])->first();
        if (Inventory::where([['storagePlaceId', '=', $productRow->id],['isFound', '=', true]])->count() > 0) {
            return redirect()->back()->with('info',
                'Ezt a kódot már egyszer beolvastad! Szeretnéd visszavonni a beolvasást?
                <a class="btn btn-primary btn-sm mt-2" href="/storage/inventoryUnFindItem/'.$productRow->id.'">Visszavonás</a>');
        }
        if ($productRow == null) {
            return redirect()->back()->with('error', 'Ilyen kód nem létezik az adatbázisban! Kérlek adj meg egy másikat vagy próbáld meg újra!');
        }
        $explodedStoragePlace = explode('-', $productRow->storagePlace);
        if ($storageId != $explodedStoragePlace[0]) {
            $originalStorage = StorageUnit::find($storageId);
            return redirect()
                ->back()
                ->with('info', '
                Ez a termék rossz helyen lett beolvasva!
                Az eredeti helye a '.$originalStorage->storageName.' nevű raktárban van a '.$productRow->storagePlace.' polchelyen.
                Szeretnéd áthelyezni ebbe a raktárba?
                <a href="/storage/inventoryHelper/'.$storageId.'/'.$productId.'" class="btn btn-sm btn-primary my-2">Igen</a>
                ');
        }
        Inventory::where('storagePlaceId', '=', $productRow->id)->update(['isFound' => true]);
        return redirect()->back();
    }
    public function helperRoute($storageId, $productId) {
        return redirect()->to('/storage/productBreak/getProduct/'.$productId)->with(['actionInventory' => 'move', 'redirectStorageId' => $storageId]);
    }
    public function updateChangedQuantity($rowId, $quantity) {
        $inventoryRow = Inventory::where('storagePlaceId', '=', $rowId)->first();
        $storagePlaceRow = StoragePlace::find($rowId);
        $productId = StoragePlace::find($rowId)->productId;
        $allStoragePlaceIdsRaw = StoragePlace::where('productId', '=', $productId)->get('id')->toArray();
        $allStoragePlaceIdsArray = [];
        foreach ($allStoragePlaceIdsRaw as $item) {
            $allStoragePlaceIdsArray[] = $item['id'];
        }
        $difference = $quantity - $storagePlaceRow->howMany;
        $inventoryRow->update(['isFound' => true]);
        $inventoryRow->increment('quantityDiff', $difference);
        foreach ($allStoragePlaceIdsArray as $item) {
            if ($item != $rowId) {
                Inventory::where('storagePlaceId', '=', $item)->increment('quantityDiff', $difference);
            }
        }

        return redirect()->back();
    }
    public function unFindItem($storagePlaceId) {
        Inventory::where('storagePlaceId', '=', $storagePlaceId)->update(['isFound' => false]);
        return redirect()->back();
    }

    public function makeInventoryPdfView($storageUnitId) {
        $storageUnitInfo = StorageUnit::find($storageUnitId);
        $rowIds = StoragePlaceController::getAllUsedPlaces($storageUnitId);
        $products = DB::table('storage_places')
            ->join('products', 'storage_places.productId', 'products.productId')
            ->join('inventories', 'storage_places.id', 'inventories.storagePlaceId')
            ->select('*')
            ->whereIn('storage_places.id', $rowIds)
            ->orderBy('isFound')
            ->orderBy('storage_places.productId')
            ->orderBy('index')
            ->get()
            ->toArray();
        $user = Auth::User();
        $notFoundCount = Inventory::whereIn('storagePlaceId', $rowIds)->where('isFound', '=', false)->count();

        $fileName = date('Y_m_d').'_'.str_replace(' ', '-', $storageUnitInfo->storageName).'_leltar.pdf';
        $pdf = Pdf::loadView('storage.PDFViews.inventoryPdfView', [
            'storageUnitInfo' => $storageUnitInfo,
            'products' => $products,
            'userInfo' => $user,
            'notFoundCount' => $notFoundCount
        ])->save('../public/PDF/'.$fileName);
        FilePath::create(['fileName' => $fileName, 'fileType' => 'PDF', 'category' => 'inventory', 'outerId' => $storageUnitInfo->storageId]);

        $notFoundRows = Inventory::whereIn('storagePlaceId', $rowIds)->where('isFound', '=', false)->get();
        foreach ($notFoundRows as $notFoundRow) {
            Inventory::where('storagePlaceId', '=', $notFoundRow->storagePlaceId)->update(['quantityDiff' => StoragePlace::find($notFoundRow->storagePlaceId)->howMany*-1]);
        }
        Inventory::whereIn('storagePlaceId', $rowIds)->where('quantityDiff', '=', 0)->delete();

        return redirect()->to('/storage/inventory/0')->with('success','
        Sikeresen befejeződött a leltározás! Az arról szóló PDF-et megtalálod a dokumentumok között. Az eltérő mennyiségű termékek megmaradtak, remélhetőleg később megtalálásra kerülnek!
        ');
    }
}
