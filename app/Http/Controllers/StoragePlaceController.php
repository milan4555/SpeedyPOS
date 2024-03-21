<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\StoragePlace;
use App\Models\StorageUnit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StoragePlaceController extends Controller
{
    public function loadPage($productId = null) {
        if ($productId == null) {
            return view('storage.productBreak');
        }
        $explodedProductId = explode('-', $productId);
        $row = DB::table('storage_places')
            ->select('*')
            ->where([
                ['productId', '=', $explodedProductId[0]],
                ['index', '=', $explodedProductId[1]]
            ])
            ->first();
        if ($row == null) {
            return redirect()->back()->with('error', 'Ilyen termékkód nem létezik az adatbázisban! Kérlek ellenőrizd a kódot, vagy próbálj meg egy másikat!');
        }
        return view('storage.productBreak', [
            'product' => $row
        ]);
    }
    public function checkIfStoragePlaceExists($storageName) {
        $explodedName = explode('-', $storageName);
        $storage = StorageUnit::find($explodedName[0]);
        $charNumber = ord(strtoupper($explodedName[1][0])) - ord('A') + 1;
        if (count($explodedName) != 3) {
            return false;
        }
        if ($storage->numberOfRows <= $charNumber or $storage->heightNumber <= $explodedName[1][1] or $storage->widthNumber <= $explodedName[2]) {
            return false;
        }
        return true;
    }

    public function addProductBreakRow(Request $request) {
        if ($request['newStoragePlace'] == $request['oldStoragePlace']) {
            return redirect()->back()->with('error', 'A kettő raktárhelység megegyezik, így nem történt semmilyen változtatás!');
        }
        if (!$this->checkIfStoragePlaceExists($request['newStoragePlace'])) {
            return redirect()->back()->with('error', 'Ilyen polccimke nem létezik! Kérlek adj meg egy másikat!');
        }
        $brokenRow = StoragePlace::where([['productId', '=', $request['brokenProductId']],['index', '=', $request['brokenIndex']]])->first();
        $maxIndex = StoragePlace::where('productId', '=', $request['brokenProductId'])->max('index');
        if ($brokenRow->howMany == $request['selectedQuantity']) {
            return redirect()->back()->with('error', 'A kettő mennyiség megegyezik, így nem történt semmilyen változtatás!');
        }
        StoragePlace::create([
           'productId' => $request['brokenProductId'],
           'index' => $maxIndex + 1,
           'howMany' => $request['selectedQuantity'],
           'storagePlace' => $request['newStoragePlace']
        ]);
        StoragePlace::where([['productId', '=', $request['brokenProductId']],['index', '=', $request['brokenIndex']]])->decrement('howMany', $request['selectedQuantity']);

        return redirect()->to('/storage/productBreak/getProduct')->with('success', 'Sikeresen végrehajtott művelet! Az új adatot megtalálod a raktárböngésző oldalon!')->send();
    }

    public function updateProductMoveRow(Request $request) {
        if ($request['newStoragePlace'] == $request['oldStoragePlace']) {
            return redirect()->back()->with('error', 'A kettő raktárhelység megegyezik, így nem történt semmilyen változtatás!');
        }
        if (!$this->checkIfStoragePlaceExists($request['newStoragePlace'])) {
            return redirect()->back()->with('error', 'Ilyen polccimke nem létezik, vagy a formátum nem megfelelő! Kérlek adj meg egy másikat!');
        }
        $movedProduct = StoragePlace::where([['productId', '=', $request['brokenProductId']],['index', '=', $request['brokenIndex']]])->first();
        $sameProduct = DB::table('storage_places')
            ->where([['productId', '=', $request['brokenProductId']],['storagePlace', '=', $request['newStoragePlace']]])
            ->count();
        if ($sameProduct > 0) {
            DB::table('storage_places')
                ->where([['productId', '=', $request['brokenProductId']],['storagePlace', '=', $request['newStoragePlace']]])
                ->increment('howMany', $movedProduct->howMany);
            $movedProduct->delete();
            return redirect()->back()->with('success', 'Sikeresen áthelyezted a terméket, és mivel ezen a helyen már volt hasonló termék, így a kettő össze lett vonva.');
        }
        StoragePlace::find($movedProduct->id)->update(['storagePlace' => $request['newStoragePlace']]);

        return redirect()->to('/storage/productBreak/getProduct')->with('success', 'Sikeresen végrehajtott művelet! Az új adatot megtalálod a raktárböngésző oldalon!')->send();
    }

    public function loadUnassignedProductsPage() {
        $productsList = DB::table('storage_places')
            ->join('products', 'storage_places.productId', 'products.productId')
            ->select('*')
            ->where('storage_places.storagePlace', '=', null)
            ->get()
            ->toArray();
        return view('storage.unassignedProducts', [
           'products' =>  $productsList
        ]);
    }

    public function assignProduct($productId, $storagePlace) {
        if (!$this->checkIfStoragePlaceExists($storagePlace)) {
            return redirect()->back()->with('error', 'Ilyen polccimke nem létezik, vagy a formátum nem megfelelő! Kérlek adj meg egy másikat!');
        }
        $explodedProductId = explode('-', $productId);
        $unassignedRow = StoragePlace::where([['productId', '=', $explodedProductId[0]],['index', '=', $explodedProductId[1]]])->first();
        $sameProduct = DB::table('storage_places')
            ->where([['productId', '=', $explodedProductId[0]],['storagePlace', '=', $storagePlace]])
            ->count();
        if ($sameProduct > 0) {
            DB::table('storage_places')
                ->where([['productId', '=', $explodedProductId[0]],['storagePlace', '=', $storagePlace]])
                ->increment('howMany', $unassignedRow->howMany);
            $unassignedRow->delete();
            return redirect()->back()->with('success', 'Sikeresen elhelyezted a terméket, mivel ezen a helyen már volt hasonló termék, így a kettő össze lett vonva.');
        }
        $unassignedRow->update(['storagePlace' => $storagePlace]);
        return redirect()->back()->with('success', 'Sikeresen elhelyezted a terméket. Jöhet a következő!');
    }
}
