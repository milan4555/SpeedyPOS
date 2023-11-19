<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    public function cashRegisterItems(Request $request) {
        if (isset($request->lastProductId)) {
            $lastProduct = Product::find($request->lastProductId);
            if ($lastProduct == null) {
                $lastProduct = 'Nem megfelelő kódot adtál meg!';
            }
        } else {
            $lastProduct = 'Üres a kosár!';
        }
        if (isset($request->productIds)) {
            $productIds = $request->productIds;
            $products = Product::all()->whereIn('productId', $productIds)->where('productId', '!=', $request->lastProductId);
            $sumPrice = DB::table('products')
                ->select('bPrice')
                ->whereIn('productId', $productIds)
                ->sum('bPrice');
            $howMany = $request["howMany"];
        } else {
            $productIds = [];
            $products = null;
            $sumPrice = 0;
            $howMany = [];
        }
        if (isset($productIds)) {
            if (in_array($request->lastProductId, $productIds) and $howMany != null) {
                $i = 0;
                foreach ($howMany as $data) {
                    $dataExplode = explode('-', $data);
                    if ($dataExplode[0] == $request->lastProductId) {
                        unset($howMany[$i]);
                        array_push($howMany, $dataExplode[0] . "-" . intval($dataExplode[1]) + 1);
                    }
                    $i++;
                }
            } else if (isset($request->lastProductId)){
                if ($howMany != null) {
                    array_push($howMany, $request->lastProductId."-1");
                } else {
                    $howMany[] = $request->lastProductId . "-1";
                }
            }
            array_push($productIds, $request->lastProductId);
            if (Product::find($request->lastProductId) != null) {
                $sumPrice += Product::find($request->lastProductId)->bPrice;
            }
        }
        return view('cashRegister/cashRegister',
            [
                'lastProduct' => $lastProduct,
                'products' => $products,
                'productIds' => $productIds,
                'sumPrice' => $sumPrice,
                'howMany' => $howMany
            ]);
    }

    public static function getHowManyInCart($id, $array) {
        foreach ($array as $data) {
            $dataExplode = explode('-', $data);
            if ($dataExplode[0] == $id){
                return $dataExplode[1];
            }
        }
        return 0;
    }
}
