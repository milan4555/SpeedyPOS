<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\productCodes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductCodesController extends Controller
{
    public static function getAllCodesByProductId($productId) {
        return productCodes::all()
            ->where('productIdCode', '=', $productId)
            ->sortByDesc('created_at');
    }

    public static function makeTable($productId) {
        $productCodes = self::getAllCodesByProductId($productId);
        $tableString = '
                   <div class="row">
                   <div class="col-md-6">
                   <h4>Termékhez tartozó kódok:</h4>
                        <input type="number" placeholder="Új kód felvétele" class="form-control my-2 border-dark" id="newProductCode'.$productId.'" data-productId="'.$productId.'" name="newProductCode">
                        <button class="btn button-blue" onclick="addProductCode('.$productId.')">Felvétel</button>
                   </div>
                   <div class="col-md-6" style="overflow: auto; height: 200px">
                   <table class="table border border-2 border-dark">';
        if (count($productCodes) == 0) {
            $tableString .= '
                  <tr>
                    <td class="align-middle">Ehhez a termékhez nem tartozik egyetlen kód sem!</td>
                  </tr>';
        } else {
            foreach ($productCodes as $productCode) {
                $tableString .= '
                  <tr>
                    <td class="align-middle">' . $productCode->productCode . '</td>
                  </tr>';
            }
        }
        $tableString .= '</table>
                        </div>
                        </div>
                        ';

        return $tableString;
    }

    public static function whichProduct($productCode) {
        $result = DB::table('product_codes')
            ->select('productIdCode')
            ->where('productCode', '=', $productCode)
            ->get()
            ->toArray();

        if ($result != null) {
            return $result[0]->productIdCode;
        } else {
            return false;
        }
    }

    public function newProductCode($productId, $productCode) {
        $exists = productCodes::where('productCode', $productCode)->first();
        if ($exists != null) {
            $product = Product::find($exists->productIdCode);
            return redirect()
                ->back()
                ->with('error', 'Sikertelen művelet! Ez a kód már hozzá van rendelve egy termékhez!<br><b>'.$product->productId.'<br>'.$product->productName.'</0pr></b>')
                ->with('redirectProductId', $productId);
        }
        productCodes::create(['productIdCode' => $productId, 'productCode' => $productCode]);
        return redirect()->back()->with('success', 'Sikeres művelet! Felvettél egy új kódot a kiválasztott termékhez!')->with('redirectProductId', $productId);
    }

    public function deleteProductCode($productCodeId) {
        $row = productCodes::find($productCodeId);
        $productId = $row->productIdCode;
        $row->delete();
        return redirect()->back()->with('success', 'Sikeres művelet! Kitörölted a külsős cikkszámot, így újra fel tudod használni máshol!')->with('redirectProductId', $productId);
    }
}
