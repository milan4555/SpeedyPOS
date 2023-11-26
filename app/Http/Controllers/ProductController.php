<?php

namespace App\Http\Controllers;

use App\Models\cashRegisterItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    public static function getHowManyInCart($id, $array) {
        foreach ($array as $data) {
            $dataExplode = explode('-', $data);
            if ($dataExplode[0] == $id){
                return $dataExplode[1];
            }
        }
        return 0;
    }

    public function showAllProduct(Request $request) {
        if ($request->all() == null or ($request['columnSearch'] == '' and $request['columnOrderBy'] == '')) {
            $products = DB::table('products')
                ->join('categories', 'products.categoryId', 'categories.categoryId')
                ->select('*')
                ->orderBy('productId')
                ->get()
                ->toArray();
        } else if ($request['columnOrderBy'] != ''){
            $products = DB::table('products')
                ->join('categories', 'products.categoryId', 'categories.categoryId')
                ->select('*')
                ->orderBy($request['columnOrderBy'] == '' ? 'productId' : $request['columnOrderBy'])
                ->get()
                ->toArray();
        } else {
            $products = DB::table('products')
                ->join('categories', 'products.categoryId', 'categories.categoryId')
                ->select('*')
                ->where($request['columnSearch'], 'ilike', '%'.$request['search'].'%')
                ->orderBy($request['columnOrderBy'] == '' ? 'productId' : $request['columnOrderBy'])
                ->get()
                ->toArray();
        }
        return view('cashRegister/productList', [
           'products' => $products,
           'sumPrice' => CashRegisterItemController::getSumPrice()
        ]);
    }
}
