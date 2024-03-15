<?php

namespace App\Http\Controllers;

use App\Models\FilePath;
use App\Models\Product;
use App\Models\ProductOut;
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
            ->join('products', 'product_outs.productId', 'products.productId')
            ->where('orderNumber', '=', $orderNumber)
            ->orderBy('storagePlace')
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
    public function foundProduct($orderNumber, $productId) {
        $product = Product::find($productId);
        if ($product == null) {
            return redirect()->back()->with('error', 'A cikkszám nem található! Próbáld meg újra, vagy keress utána a megfelelő cikkszámnak!');
        }
        $row = ProductOut::where([['orderNumber', '=', $orderNumber],['productId', '=', $productId]])->first();
        if ($row->howMany == 0) {
            return redirect()->back()->with('error', 'Ebből a cikkszámból már a megfelelő mennyiséget megtaláltad!');
        }
        ProductOut::find($row->id)->decrement('howManyLeft', 1);

        return redirect()->back()->with('success', 'Sikeres találat! Ilyen szerencsét kívánok az ötös lottóhoz is!');
    }

    public function restoreProgress($orderNumber) {
        Productout::where('orderNumber', '=', $orderNumber)->update(['howManyLeft' => DB::raw('"howMany"')]);
        return redirect()->back()->with('success', 'Sikeresen nulláztad a folyamatot, de ezáltal újra is kell kezdened mindent!');
    }

    public function finishOrder($orderNumber) {
        $howManyNotZero = ProductOut::where([['orderNumber', '=', $orderNumber],['howMany', '!=', 0]])->count();
//        if ($howManyNotZero > 0) {
//            return redirect()->back()->with('error', 'Sikertelen művelet, még vannak olyan termékek, amelyből nem megfelelő mennyiség van csomagolva!');
//        }
        ProductOut::where([['orderNumber', '=', $orderNumber],['howMany', '!=', 0]])->update(['isCompleted' => true]);
        $viewArray = [
            'products' => DB::table('product_outs')
                ->join('products', 'product_outs.productId', 'products.productId')
                ->where('orderNumber', '=', $orderNumber)
                ->orderBy('storagePlace')
                ->get()
                ->toArray(),
            'orderInfo' => self::getOrderInfo($orderNumber),
            'worker' => Auth::user()
        ];
        $fileName = date('Y_m_d').'_'.$orderNumber.'_rendeles.pdf';
        Pdf::loadView('storage.PDFViews.productOutPDFView', $viewArray)->save('../public/PDF/'.$fileName);
        FilePath::create(['fileName' => $fileName, 'fileType' => 'PDF', 'category' => 'productOut', 'outerId' => $orderNumber]);
        return redirect()->to('/storage/productOut/selector')->with('success', 'Sikeresen elkészítetted a rendelést! Az arról szóló dokumnetumot megtalálod a fájlok között!');
    }
}
