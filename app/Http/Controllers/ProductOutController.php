<?php

namespace App\Http\Controllers;

use App\Models\FilePath;
use App\Models\Product;
use App\Models\ProductOut;
use App\Models\StoragePlace;
use App\Models\Variable;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ProductOutController extends Controller
{
    public function loadPage() {
        return view('storage.productOut.productOutSelector', [
            'orderNumbers' => $this->getOrderNumbers(false)
        ]);
    }

    public function completedOrders() {
        $orderNumbers = $this->getOrderNumbers(true);
        if (count($orderNumbers) == 0) {
            return redirect()->back()->with('error', 'Még nincsenek megjeleníthető adatok!');
        }
        return view('storage.productOut.productOutCompletedOrders', [
            'orderNumbers' => $this->getOrderNumbers(true)
        ]);
    }
    public function getOrderNumbers($isCompleted) {
        return DB::table('product_outs')
            ->select('orderNumber')
            ->where('isCompleted', '=', $isCompleted)
            ->where('orderNumber', '!=', -1)
            ->distinct()
            ->oldest('orderNumber')
            ->get()
            ->toArray();
    }

    public static function getOrderInfo($orderNumber) {
        return DB::table('product_outs')
            ->join('products', 'product_outs.productId', 'products.productId')
            ->select(DB::raw('sum("howMany") as totalNumberOfItems'), DB::raw('sum("nPrice"*"howMany") as totalSum'), DB::raw('sum("bPrice"*"howMany") as totalBSum'), 'product_outs.created_at')
            ->where('orderNumber', '=', $orderNumber)
            ->groupBy('product_outs.created_at')
            ->first();
    }
    public function loadOrderPage($orderNumber) {
        $orderItems = DB::table('product_outs')
            ->where('orderNumber', '=', $orderNumber)
            ->get()
            ->toArray();
        $orderInfo = self::getOrderInfo($orderNumber);
        $sameRowCount = ProductOut::where([['orderNumber', '=', $orderNumber], ['howManyLeft', '=', DB::raw('"howMany"')]])->count();
        $howManyNotZero = ProductOut::where([['orderNumber', '=', $orderNumber],['howMany', '!=', 0]])->count();

        return view('storage.productOut.productOutOrderPage', [
           'orderItems' => $orderItems,
           'orderInfo' =>  $orderInfo,
           'orderNumber' => $orderNumber,
           'howManyNotZero' => $howManyNotZero,
           'sameRowCount' => $sameRowCount
        ]);
    }
    public static function getBestStoragePlaceInfo($productId) {
        return DB::table('storage_places')
            ->join('products', 'storage_places.productId', 'products.productId')
            ->where('storage_places.productId', $productId)
            ->where('howMany', '!=', 0)
            ->orderBy('howMany')
            ->orderBy('storagePlace')
            ->first();
    }
    public function foundProduct($orderNumber, $productId) {
        $explodedProductId = explode('-', $productId);
        $productId = $explodedProductId[0];
        $index = $explodedProductId[1];
        $productRow = StoragePlace::where([['productId', $productId], ['index', $index]])->first();
        if ($productRow == null) {
            return redirect()->back()->with('error', 'A cikkszám nem található! Próbáld meg újra, vagy keress utána a megfelelő cikkszámnak!');
        }
        $row = ProductOut::where([['orderNumber', '=', $orderNumber],['productId', '=', $productId]])->first();
        if ($row->howMany == 0) {
            return redirect()->back()->with('error', 'Ebből a cikkszámból már a megfelelő mennyiséget megtaláltad!');
        }
        $productOutRow = ProductOut::find($row->id);

        $oldHowManyLeft = $productOutRow->howManyLeft;
        $oldHowMany = $productRow->howMany;
        $oldHelper = $productOutRow->helper;
        $oldHelper[] = [intval($orderNumber), $productRow->id, $oldHowManyLeft > $oldHowMany ? $oldHowMany : $oldHowManyLeft];
        $productRow->decrement('howMany', ($oldHowManyLeft < $oldHowMany ? $oldHowManyLeft : $oldHowMany));
        $productOutRow->decrement('howManyLeft', ($oldHowManyLeft > $oldHowMany ? $oldHowMany : $oldHowManyLeft));
        $productOutRow->update(['helper' => $oldHelper]);
        return redirect()->back()->with('success', 'Sikeres találat! Ilyen szerencsét kívánok az ötös lottóhoz is!');
    }

    public function restoreProgress($orderNumber) {
        Productout::where('orderNumber', '=', $orderNumber)->update(['howManyLeft' => DB::raw('"howMany"')]);
        $usedRows = ProductOut::where('helper', 'like', '%['.$orderNumber.',%')->get();
        foreach ($usedRows as $usedRow) {
            $helperData = $usedRow->helper;
            foreach ($helperData as $singleArray) {
                StoragePlace::find($singleArray[1])->increment('howMany', $singleArray[2]);
            }
        }
        $usedRow->update(['helper' => null]);
        return redirect()->back()->with('success', 'Sikeresen nulláztad a folyamatot, de ezáltal újra is kell kezdened mindent!');
    }

    public function finishOrder($orderNumber) {
        $howManyNotZero = ProductOut::where([['orderNumber', '=', $orderNumber],['howMany', '!=', 0]])->count();
        if ($howManyNotZero > 0) {
            return redirect()->back()->with('error', 'Sikertelen művelet, még vannak olyan termékek, amelyből nem megfelelő mennyiség van csomagolva!');
        }
        $usedRows = ProductOut::where('helper', 'like', '%['.$orderNumber.',%')->get();
        foreach ($usedRows as $usedRow) {
            $helperData = $usedRow->helper;
            for ($i = 0; $i < count($helperData); $i++) {
                if ($i == (count($helperData)-1)) {
                    break;
                }
                StoragePlace::find($helperData[$i][1])->delete();
            }
        }
        ProductOut::where([['orderNumber', '=', $orderNumber],['howMany', '!=', 0]])->update(['isCompleted' => true]);
        $viewArray = [
            'products' => DB::table('product_outs')
                ->join('products', 'product_outs.productId', 'products.productId')
                ->where('orderNumber', '=', $orderNumber)
                ->get()
                ->toArray(),
            'orderInfo' => self::getOrderInfo($orderNumber),
            'worker' => Auth::user()
        ];
        if ($orderNumber == -1) {
            $fileName = date('Y_m_d').'_bolti_kiadas.pdf';
            ProductOut::where('orderNumber', '=', -1)->delete();
        } else {
            $fileName = date('Y_m_d').'_'.$orderNumber.'_rendeles.pdf';
        }
        Pdf::loadView('storage.PDFViews.productOutPDFView', $viewArray)->save('../public/PDF/'.$fileName);
        FilePath::create(['fileName' => $fileName, 'fileType' => 'PDF', 'category' => 'productOut', 'outerId' => $orderNumber]);
        return redirect()->to('/storage/productOut/selector')->with('success', 'Sikeresen elkészítetted a rendelést! Az arról szóló dokumentumot megtalálod a fájlok között!');
    }

    public function forStore() {
        $storeRows = ProductOut::where('orderNumber', '=', -1)->get();
        if (count($storeRows) == 0) {
            return view('storage.productOut.productOutForStore');
        }
        return view('storage.productOut.productOutForStore', [
            'orderItems' => $storeRows
        ]);
    }

    public function addProductToList(Request $request) {
        $product = Product::find($request['productIdOrder']);
        if ($product == null) {
            return redirect()->back()->with('error', 'Nem megfelelő termékkódot adtál meg! Próbáld meg újra, vagy adj meg egy másikat!');
        }
        $productStock = ProductController::getHowManyInStorage($request['productIdOrder']);
        if ($productStock < $request['howManyOrder']) {
            return redirect()->back()->with('error', 'Összesen nincsen ennyi darabszám a raktárban! Elérhető mennyiség: '.$productStock);
        }
        ProductOut::create([
           'productId' => $request['productIdOrder'],
            'howMany' => $request['howManyOrder'],
            'howManyLeft' => $request['howManyOrder'],
            'orderNumber' => -1,
            'isCompleted' => false,
            'helper' => null
        ]);
        return redirect()->back()->with('success', 'Sikeres felírás!');
    }

    public function forStoreRemoveRow($orderItemId) {
        ProductOut::find($orderItemId)->delete();
        return redirect()->back()->with('success', 'Sikeres törlés!');
    }

    public function forStoreUpdateRow($orderItemId, $quantity) {
        ProductOut::find($orderItemId)->update(['howMany' => $quantity, 'howManyLeft' => $quantity]);
        return redirect()->back()->with('success', 'Sikeres módosítás!');
    }

    public function forStoreRestart() {
        $notNullHelper = ProductOut::where([['orderNumber', '=', -1],['helper', '!=', null]])->count();
        if ($notNullHelper > 0) {
            return redirect()->back()->with('error', 'Már van olyan elem, amely megtalálásra került a rendszerben! Először ott kell újrakezdeni ha szükséges!');
        }
        ProductOut::where('orderNumber', '=', -1)->delete();
        return redirect()->back()->with('success', 'Sikeres törölted a rendelést!');
    }
}
