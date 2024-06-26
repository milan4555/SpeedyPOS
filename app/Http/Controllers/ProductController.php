<?php

namespace App\Http\Controllers;

use App\Models\cashRegisterItem;
use App\Models\Category;
use App\Models\Product;
use App\Models\StoragePlace;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    public function showAllProduct(Request $request) {
        $query = Product::query();
        $query->join('categories', 'products.categoryId', 'categories.categoryId')
            ->select('*');
        $query->when($request['columnOrderBy'] != '', function ($query) use ($request) {
           $query->orderBy($request['columnOrderBy']);
        });
        $query->when($request['columnSearch'] != '', function ($query) use ($request) {
            $query->where($request['columnSearch'], 'ilike', '%'.$request['search'].'%');
        });
        $products = $query->get();
        $selectOptions = [
            ['productId', 'Azonosító'], ['productName', 'Termék neve'], ['productShortName', 'Rövid név'], ['categoryName', 'Kategória']
        ];
        return view('cashRegister/productList', [
           'selectOptions' => $selectOptions,
           'products' => $products,
           'columnOrderBy' => $request['columnOrderBy'],
           'columnSearch' => $request['columnSearch'],
           'search' => $request['search']
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
        $validatorArray = [
            'productName' => ['required'],
            'productShortName' => ['required'],
            'nPrice' => ['required'],
            'bPrice' => ['required']
        ];
        if ($request['categoryId'] != null) {
            $validatorArray['categoryId'] = ['required'];
        } else {
            $validatorArray['newCategoryName'] = ['required'];
        }
        $validator = Validator::make($request->all(), $validatorArray);
        if ($validator->fails()) {
            return redirect()->back()
                ->with('error', 'Sikertelen művelet! Hiányzó adatok voltak a módosítás során!')
                ->withInput();
        }
        if ($request['categoryId'] == '' && $request['newCategoryName'] != '') {
            $request['categoryId'] = CategoryController::addCategory($request['newCategoryName'])->categoryId;
        }
        $lastProductInCategory = Product::where('categoryId', $request['categoryId'])->orderBy('productId', 'DESC')->first();
        if ($lastProductInCategory == null) {
            $productId = $request['categoryId'].'0000001';
        } else {
            $productId = $lastProductInCategory->productId+1;
        }
        $productHelper = [
            'productId' => $productId,
            'productName' => $request['productName'],
            'productShortName' => $request['productShortName'],
            'nPrice' => $request['nPrice'],
            'bPrice' => $request['bPrice'],
            'categoryId' => $request['categoryId'],
            'companyId' => $request['companyId'],
            'stock' => 0
        ];

        Product::create($productHelper);

        return Redirect::back()->with('success', 'Sikeresen felvetted az új terméket a listába!');
    }

    public function updateProduct(Request $request) {
        $validatorArray = [
            'productName' => ['required'],
            'productShortName' => ['required'],
        ];
        if ($request['categoryId'] != null) {
            $validatorArray['categoryId'] = ['required'];
        } else {
            $validatorArray['newCategoryName'] = ['required'];
        }
        $validator = Validator::make($request->all(), $validatorArray);
        if ($validator->fails()) {
            return redirect()->back()->with('error', 'Sikertelen művelet! Hiányzó adatok voltak a módosítás során!')->with('updatedProduct', $request['productId']);
        }
        if ($request['newCategoryName'] != '') {
            $newCategory = Category::create(['categoryName' => $request['newCategoryName']]);
            $request['categoryId'] = $newCategory->categoryId;
        }
        $productHelper = [
            'productName' => $request['productName'],
            'productShortName' => $request['productShortName'],
            'categoryId' => $request['categoryId'],
            'companyId' => $request['companyId'],
        ];

        Product::find($request['productId'])->update($productHelper);

        return Redirect::back()->with('success', 'Sikeresen megváltoztattad a termék paramétereit!')->with('redirectProductId', $request['productId']);
    }

    public static function getHowManyInStorage($productId) {
        return StoragePlace::where('productId', $productId)->sum('howMany');
    }
}
