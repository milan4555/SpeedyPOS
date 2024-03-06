<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Product;
use App\Models\productCodes;
use App\Models\ProductInOut;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductInOutController extends Controller
{
    public function loadPage() {
        $products = DB::table('products')
            ->join('product_in_outs', 'products.productId', 'product_in_outs.productId')
            ->join('categories', 'products.categoryId', 'categories.categoryId')
            ->where('inOrOut', '=', 'IN')
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
            'selectedSupplierId' => $selectedSupplierId
        ]);
    }
    public function addNewRow($productIdentifier) {
        if (ProductInOut::where('productId', '=', $productIdentifier)
                ->orWhere('productId', '=', ProductCodesController::whichProduct($productIdentifier))
                ->count() > 0)
        {
            ProductInOut::where('productId', '=', $productIdentifier)
                ->orWhere('productId', '=', ProductCodesController::whichProduct($productIdentifier))
                ->increment('howMany', 1);
            return redirect()->back();
        }
        $product = DB::table('products')
            ->join('categories', 'products.categoryId', 'categories.categoryId')
            ->join('product_codes' , 'products.productId', 'product_codes.productIdCode')
            ->select('products.*', 'categories.categoryName')
            ->where('productId', '=', $productIdentifier)
            ->orWhere('productCode', '=', $productIdentifier)
            ->distinct()
            ->get()
            ->toArray();
        if ($product == null) {
            return redirect()->back();
        }
        ProductInOut::create([
            'productId' => $product[0]->productId,
            'howMany' => 0,
            'inOrOut' => 'IN',
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
                'inOrOut' => 'IN',
            ]);
        }
        return redirect()->back();
    }

    public function removeRow($productCode) {
        ProductInOut::where('productId', '=', $productCode)->delete();
        return redirect()->back();
    }
}
