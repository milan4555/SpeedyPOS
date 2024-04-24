<?php

namespace App\Http\Controllers;

use App\Http\Middleware\RedirectIfAuthenticated;
use App\Models\cashRegisterItem;
use App\Models\Company;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

class CashRegisterItemController extends Controller
{
    public function getItems(Request $request) {
        if (isset($request->lastProductId)) {
            if (!UserTimeLogController::doesHaveOpenCashRegister(Auth::id())) {
                return \redirect()->back()->with('error', 'Sikertelen művelet! Először nyisd ki a kasszát, majd próbáld meg újra!');
            }
            if (UserTimeLogController::isOnBreak(Auth::id())) {
                return \redirect()->back()->with('error', 'Sikertelen művelet! Először fejezd be a szüneted, majd próbáld meg újra!');
            }
            $input = explode('*', $request->lastProductId);
            $productId = count($input) == 1 ? $input[0] : $input[1];
            if (count($input) == 2 and $input[0] < 0) {
                return \redirect()->back()->with('error', 'Sikertelen művelet! A darabszám csak pozitív szám lehet!');
            }
            $product = DB::table('products')
                ->select('*')
                ->leftJoin('product_codes', 'products.productId', 'product_codes.productIdCode')
                ->where('productId', '=', $productId)
                ->orWhere('productCode', '=', $productId)
                ->count();
            if ($product == 0) {
                $lastProduct = 'Nem megfelelő kódot adtál meg!';
            } else {
                $productIdReg = DB::table('products')
                    ->select('productId')
                    ->leftJoin('product_codes', 'products.productId', 'product_codes.productIdCode')
                    ->where('productId', '=', $productId)
                    ->orWhere('productCode', '=', $productId)
                    ->first();
                if (DB::table('cash_register_items')->where('productIdReg', '=', $productIdReg->productId)->count() == 0) {
                    $addItem = [
                        'productIdReg' => $productIdReg->productId,
                        'cashRegisterNumber' => 1,
                        'howMany' => count($input) == 2 ? $input[0] : 1,
                        'currentPrice' => Product::find($productIdReg->productId)->nPrice
                    ];
                    cashRegisterItem::create($addItem);
                    Product::find($productIdReg->productId)->decrement('stock', count($input) == 2 ? $input[0] : 1);
                } else {
                    DB::table('cash_register_items')->where('productIdReg', '=', $productIdReg->productId)->increment('howMany', count($input) == 2 ? $input[0] : 1);
                    Product::find($productIdReg->productId)->decrement('stock', count($input) == 2 ? $input[0] : 1);
                }
                $lastProduct = DB::table('products')
                    ->select('*', 'cash_register_items.howMany')
                    ->leftJoin('cash_register_items', 'productId', 'productIdReg')
                    ->leftJoin('categories', 'products.categoryId', 'categories.categoryId')
                    ->leftJoin('product_codes', 'products.productId', 'product_codes.productIdCode')
                    ->where('productId', '=', $productId)
                    ->orWhere('productCode', '=', $productId)
                    ->first();
            }
        } else {
            $lastProduct = 'Üres a kosár!';
        }
        if (cashRegisterItem::all()->where('howMany', '!=', -1)->count() >= 0) {
            $productIds = [];
            foreach (DB::table('cash_register_items')->select('productIdReg')->get()->toArray() as $singleItem) {
                array_push($productIds, $singleItem->productIdReg);
            }
            $products = DB::table('products')
                ->select('*', 'cash_register_items.howMany')
                ->join('cash_register_items', 'productId', 'productIdReg')
                ->leftJoin('categories', 'products.categoryId', 'categories.categoryId')
                ->where('howMany', '!=', -1)
                ->whereIn('productId',  $productIds)->where('productId', '!=', $productIdReg->productId ?? 0)
                ->get()
                ->toArray();
        } else {
            $productIds = [];
            $products = null;
        }
        if (count($request->all()) == 0 and (cashRegisterItem::all()->where('howMany', '!=', -1)->count() > 0)) {
            $productIds = [];
            foreach (DB::table('cash_register_items')->select('productIdReg')->get()->toArray() as $singleItem) {
                array_push($productIds, $singleItem->productIdReg);
            }
            $lastProduct = DB::table('products')
                ->leftJoin('cash_register_items', 'productIdReg', 'productId')
                ->leftJoin('categories', 'products.categoryId', 'categories.categoryId')
                ->select('*')
                ->orderBy('cash_register_items.updated_at')
                ->limit(1)
                ->first();
            $products = DB::table('products')
                ->select('*', 'cash_register_items.howMany')
                ->leftJoin('cash_register_items', 'productId', 'productIdReg')
                ->leftJoin('categories', 'products.categoryId', 'categories.categoryId')
                ->where('productId', '!=', $lastProduct->productId)
                ->where('howMany', '!=', -1)
                ->whereIn('productId',  $productIds)
                ->get()
                ->toArray();
        }
        $isThereCompany = DB::table('cash_register_items')->select('productIdReg')->where('howMany', '=', -1)->get()->toArray();
        if ($isThereCompany != null) {
            $company = Company::find($isThereCompany[0]->productIdReg);
        } else {
            $company = null;
        }
        return view('cashRegister/cashRegister',
            [
                'lastProduct' => $lastProduct,
                'products' => $products,
                'productIds' => $productIds,
                'sumPrice' => $this->getSumPrice(),
                'companyCurrent' => $company
            ]);
    }

    public static function getSumPrice() {
        $sumPrice = 0;
        $numbers = DB::table('cash_register_items')
            ->join('products', 'productIdReg', 'productId')
            ->select('cash_register_items.currentPrice', 'cash_register_items.howMany')
            ->where('howMany', '!=', -1)
            ->get()
            ->toArray();
        foreach ($numbers as $row) {
            $sumPrice += ($row->currentPrice*$row->howMany);
        }

        return $sumPrice;
    }

    public function emptyCashRegister() {
        cashRegisterItem::truncate();
        return Redirect('/cashRegister')->with('success', 'Sikeresen kiürítetted a kosarat! Jöhet a következő vásárló!');
    }

    public function changeQuantity($productIds, $value) {
        if ($value < 1) {
            return \redirect()->back()->with('error', 'Sikertelen művelet! Az ár nem lehet kisebb mint 1 db!');
        }
        $productIds = json_decode($productIds);
        DB::table('cash_register_items')
            ->whereIn('productIdReg', $productIds)
            ->update(['howMany' => $value]);

        foreach ($productIds as $productId) {
            $row = CashRegisterItem::where('productIdReg', $productId)->first();
            Product::find($row->productIdReg)->increment('stock', ($row->howMany-$value));
        }

        return redirect()->to('/cashRegister');
    }

    public function changePrice($productIds, $value) {
        if ($value < 1) {
            return \redirect()->back()->with('error', 'Sikertelen művelet! Az ár nem lehet kisebb mint 1 Ft!');
        }
        $productIds = json_decode($productIds);
        DB::table('cash_register_items')
            ->whereIn('productIdReg', $productIds)
            ->update(['currentPrice' => $value]);

        return redirect()->to('/cashRegister');
    }

    public function pricePercent($productIds, $value) {
        if ($value < 1 || $value > 99) {
            return \redirect()->back()->with('error', 'Sikereteln művelet! A kedvezmény 1% és 99% közötti érték kell hogy legyen!');
        }
        $productIds = json_decode($productIds);
        foreach ($productIds as $productId) {
            $row = CashRegisterItem::where('productIdReg', '=', $productId)->first();
            $row->update(['currentPrice' => round($row->currentPrice*(1-($value/100)))]);
        }

        return redirect()->to('/cashRegister');
    }

    public function itemDelete($cashRegisterNumber, $productId) {
        $row = DB::table('cash_register_items')
            ->where([['cashRegisterNumber', '=', $cashRegisterNumber],['productIdReg', '=', $productId]])
            ->first();
        CashRegisterItem::find($row->id)->delete();
        Product::find($row->productIdReg)->increment('stock', $row->howMany);
        return redirect()->back();
    }
}
