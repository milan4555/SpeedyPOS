<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Product;
use App\Models\productCodes;
use App\Models\ProductInOut;
use App\Models\Variable;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

class ProductInOutController extends Controller
{
    public function loadPage() {
        $products = DB::table('products')
            ->join('product_in_outs', 'products.productId', 'product_in_outs.productId')
            ->join('categories', 'products.categoryId', 'categories.categoryId')
            ->orderBy('product_in_outs.created_at')
            ->get()
            ->toArray();
        $selectedSupplierId = DB::table('product_in_outs')->select('productId')
            ->where('howMany', '=', -1)
            ->get()
            ->toArray();
        if ($selectedSupplierId != null) {
            $selectedSupplierId = $selectedSupplierId[0]->productId;
        } else {
            $selectedSupplierId = 0;
        }
        return view('storage.productIn', [
            'products' => $products,
            'suppliers'  => Company::all()->where('isSupplier', '=', true),
            'selectedSupplierId' => $selectedSupplierId,
            'howManyZeros' => ProductInOut::where('howMany', '=', 0)->count()
        ]);
    }
    public function addNewRow($productIdentifier) {
        $tableCount = ProductInOut::where('productId', '=', $productIdentifier)
            ->orWhere('productId', '=', ProductCodesController::whichProduct($productIdentifier))
            ->count();
        if ($tableCount > 0) {
            ProductInOut::where('productId', '=', $productIdentifier)
                ->orWhere('productId', '=', ProductCodesController::whichProduct($productIdentifier))
                ->increment('howMany', 1);
            return redirect()->back();
        }
        $product = DB::table('products')
            ->join('categories', 'products.categoryId', 'categories.categoryId')
            ->leftJoin('product_codes' , 'products.productId', 'product_codes.productIdCode')
            ->select('products.*', 'categories.categoryName')
            ->where('productId', '=', $productIdentifier)
            ->orWhere('productCode', '=', $productIdentifier)
            ->distinct()
            ->get()
            ->toArray();
        if ($product == null) {
            return redirect()->back()->with('error',
                'Ilyen azonosítóval nem szerepel termék az adatbázisban, vagy a termékkód még nincs semelyik termékhez kötve!<br>
                 Szeretnél újat létrehozni?<br>
                 <a class="btn btn-outline-success btn-sm mt-2 text-white" href="/storage/productsList">Felvétel</a><br>
                 ');
        }
        ProductInOut::create([
            'productId' => $product[0]->productId,
            'howMany' => 0,
            'newBPrice' => $product[0]->bPrice
        ]);

        return redirect()->back();
    }

    public function changeQuantity($productId, $quantity) {
        ProductInOut::where('productId', '=', $productId)->update(['howMany' => $quantity]);
        return redirect()->back();
    }

    public function changeBPrice($productId, $bPrice) {
        ProductInOut::where('productId', '=', $productId)->update(['newBPrice' => $bPrice]);
        return redirect()->back();
    }

    public function addSupplier($supplierId) {
        if ($supplierId == 0) {
            ProductInOut::where('howMany', '=', -1)->delete();
        } else if (ProductInOut::where('howMany', '=', -1)->count() > 0) {
            ProductInOut::where('howMany', '=', -1)->update(['productId' => $supplierId]);
        } else {
            ProductInOut::create([
                'productId' => $supplierId,
                'howMany' => -1,
            ]);
        }
        return redirect()->back();
    }

    public function removeRow($productCode) {
        ProductInOut::where('productId', '=', $productCode)->delete();
        return redirect()->back();
    }

    public function finish() {
        $fp=pfsockopen("127.0.0.1",9100);
        $allData = DB::table('product_in_outs')
            ->join('products', 'product_in_outs.productId', 'products.productId')
            ->select('*')
            ->where('howMany', '!=', -1)
            ->get()
            ->toArray();
        foreach ($allData as $data) {
            $product = Product::find($data->productId);
            $product->update(['bPrice' => $data->newBPrice, 'nPrice' => round($data->newBPrice*1.8, -1)]);
            $product->increment('stock', $data->howMany);
//            for ($i = 0; $i < $data->howMany; $i++) {
//                $zpl = '
//                ^XA
//                ^FO20,20
//                ^CI28
//                ^BY4,3,150
//                ^B3N,N,N,N
//                ^FD>:'.$data->productId.'^FS
//                ^FO230,190
//                ^A0N,80,80
//                ^FD'.$data->productId.'^FS
//                ^FO20,260
//                ^A0N,60,60
//                ^FD'.$product->productShortName.'^FS
//                ^FO20,330
//                ^A0N,60,60
//                ^FDÁr: '.$data->newBPrice*1.8.' Ft.^FS
//                ^XZ
//                ';
//                fputs($fp,$zpl);
//            }
        }
        $supplierId = ProductInOut::all()->where('howMany', '==', -1)->first()->productId;
        $supplier = Company::find($supplierId);
        $viewArray = [
            'products' => $allData,
            'user' => Auth::getUser(),
            'supplier' => $supplier,
            'worker' => Auth::user()
        ];
        Pdf::loadView('storage.PDFViews.productInPDFView', $viewArray)->save('../public/pdf/'.date('Y_m_d').'_'.str_replace(' ', '-', $supplier->companyName).'pdf');
        ProductInOut::truncate();

        return redirect()->back()->with('success', 'Sikeres árubevétel! A cimkék nyomtatása megkezdődött, a bevételről szóló pdf-et pedig a fájlkeresőben találod!');
    }

    public function fullDelete() {
        ProductInOut::truncate();
        return Redirect::back();
    }
}
