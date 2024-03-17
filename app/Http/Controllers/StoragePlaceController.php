<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\StoragePlace;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StoragePlaceController extends Controller
{
    public function loadPage($productId = null) {
        if ($productId == null) {
            return view('storage.productBreak.productBreak');
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
        return view('storage.productBreak.productBreak', [
            'product' => $row
        ]);
    }

    public function addRow(Request $request) {
        if ($request['newStoragePlace'] == $request['oldStoragePlace']) {
            return redirect()->back()->with('error', 'A kettő raktárhelység megegyezik, így nem történt semmilyen változtatás!');
        }
        $maxIndex = StoragePlace::where('productId', '=', $request['brokenProductId'])->max('index');
        StoragePlace::create([
           'productId' => $request['brokenProductId'],
           'index' => $maxIndex + 1,
           'howMany' => $request['selectedQuantity'],
           'storagePlace' => $request['newStoragePlace']
        ]);
        StoragePlace::where([['productId', '=', $request['brokenProductId']],['index', '=', $request['brokenIndex']]])->decrement('howMany', $request['selectedQuantity']);

        return redirect()->to('/storage/productBreak/getProduct')->with('success', 'Sikeresen végrehajtott művelet! Az új adatot megtalálod a raktárböngésző oldalon!')->send();
    }
}
