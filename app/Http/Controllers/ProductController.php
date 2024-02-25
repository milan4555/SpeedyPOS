<?php

namespace App\Http\Controllers;

use App\Models\cashRegisterItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

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
           'products' => $products
        ]);
    }

    public function showProductsPage() {
        $products = DB::table('products')
            ->join('categories', 'products.categoryId', 'categories.categoryId')
            ->leftJoin('companies', 'products.companyId', 'companies.companyId')
            ->select('*')
            ->get()
            ->toArray();
        return view('storage.productsPage', [
            'products' => $products
        ]);
    }

    public function addProduct(Request $request) {
        if ($request['categoryId'] == '' && $request['newCategoryName'] != '') {
            $request['categoryId'] = CategoryController::addCategory($request['newCategoryName'])->categoryId;
        }
        $productHelper = [
            'productName' => $request['productName'],
            'productShortName' => $request['productShortName'],
            'nPrice' => $request['nPrice'],
            'bPrice' => $request['bPrice'],
            'categoryId' => $request['categoryId'],
            'companyId' => $request['companyId'],
            'stock' => 0
        ];

        Product::create($productHelper);

        return Redirect::back();
    }

    public function updateProduct(Request $request) {
        $productHelper = [
            'productName' => $request['productName'],
            'productShortName' => $request['productShortName'],
            'categoryId' => $request['categoryId'],
            'companyId' => $request['companyId'],
        ];

        Product::find($request['productId'])->update($productHelper);

        return Redirect::back();
    }
}
