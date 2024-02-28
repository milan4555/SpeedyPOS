<?php

namespace App\Http\Controllers;

use App\Http\Middleware\RedirectIfAuthenticated;
use App\Models\cashRegisterItem;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

class CashRegisterItemController extends Controller
{
    public function showItems() {
        $productIds = DB::table('cash_register_items')->select('productIdReg')->get()->toArray();
        dd($productIds);
        $lastProduct = DB::table('products')
            ->join('cash_register_items', 'productIdReg', 'productId')
            ->select('*')
            ->orderBy('cash_register_items.updated_at')
            ->limit(1)
            ->get()
            ->toArray();
        $products = DB::table('products')
            ->select('*', 'cash_register_items.howMany')
            ->join('cash_register_items', 'productId', 'productIdReg')
            ->leftJoin('categories', 'products.categoryId', 'categories.categoryId')
            ->whereIn('productId',  $productIds)->where('productId', '!=', $request->lastProductId)
            ->get()
            ->toArray();
        return view('cashRegister/cashRegister',
            [
                'lastProduct' => $lastProduct,
                'products' => $products,
                'productIds' => $productIds,
                'sumPrice' => $this->getSumPrice(),
            ]);
    }
    public function getItems(Request $request) {
        if (isset($request->lastProductId)) {
            $input = explode('*', $request->lastProductId);
            $productId = count($input) == 1 ? $input[0] : $input[1];
            $lastProduct = DB::table('products')
                ->select('*', 'cash_register_items.howMany')
                ->leftJoin('cash_register_items', 'productId', 'productIdReg')
                ->leftJoin('categories', 'products.categoryId', 'categories.categoryId')
                ->join('product_codes', 'products.productId', 'product_codes.productIdCode')
                ->where('productId', '=', $productId)
                ->orWhere('productCode', '=', $productId)
                ->get()
                ->toArray();
            if ($lastProduct == null) {
                $lastProduct = 'Nem megfelelő kódot adtál meg!';
            } else {
                $productIdReg = DB::table('products')
                    ->select('productId')
                    ->join('product_codes', 'products.productId', 'product_codes.productIdCode')
                    ->where('productId', '=', $productId)
                    ->orWhere('productCode', '=', $productId)
                    ->distinct()
                    ->get()
                    ->toArray();
                if (DB::table('cash_register_items')->where('productIdReg', '=', $productIdReg[0]->productId)->count() == 0) {
                    $addItem = [
                        'productIdReg' => $productIdReg[0]->productId,
                        'cashRegisterNumber' => 1,
                        'howMany' => count($input) == 2 ? $input[0] : 1
                    ];
                    cashRegisterItem::create($addItem);
                } else {
                    DB::table('cash_register_items')->where('productIdReg', '=', $productIdReg[0]->productId)->increment('howMany', count($input) == 2 ? $input[0] : 1);
                }
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
                ->whereIn('productId',  $productIds)->where('productId', '!=', $productIdReg[0]->productId ?? 0)
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
                ->join('cash_register_items', 'productIdReg', 'productId')
                ->leftJoin('categories', 'products.categoryId', 'categories.categoryId')
                ->select('*')
                ->orderBy('cash_register_items.updated_at')
                ->limit(1)
                ->get()
                ->toArray();
            $products = DB::table('products')
                ->select('*', 'cash_register_items.howMany')
                ->join('cash_register_items', 'productId', 'productIdReg')
                ->leftJoin('categories', 'products.categoryId', 'categories.categoryId')
                ->where('productId', '!=', $lastProduct[0]->productId)
                ->where('howMany', '!=', -1)
                ->whereIn('productId',  $productIds)
                ->get()
                ->toArray();
        }
        if (is_array($lastProduct)) {
            $lastProduct = $lastProduct[0];
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
            ->select('products.bPrice', 'cash_register_items.howMany')
            ->where('howMany', '!=', -1)
            ->get()
            ->toArray();
        foreach ($numbers as $row) {
            $sumPrice += ($row->bPrice*$row->howMany);
        }

        return $sumPrice;
    }

    public function emptyCashRegister() {
        cashRegisterItem::truncate();
        return Redirect('/cashRegister')->with('success', 'Sikeresen kiürítetted a kosarat! Jöhet a következő vásárló!');
    }

    public function changeQuantity(Request $request) {
        DB::table('cash_register_items')
            ->where('productIdReg', '=', $request->productId)
            ->update(['howMany' => $request->quantity]);

        return redirect()->to('/cashRegister');
    }

    public function itemDelete($cashRegisterNumber, $productId) {
        DB::table('cash_register_items')
            ->where([['cashRegisterNumber', '=', $cashRegisterNumber],['productIdReg', '=', $productId]])
            ->delete();
        return redirect()->back();
    }
}
