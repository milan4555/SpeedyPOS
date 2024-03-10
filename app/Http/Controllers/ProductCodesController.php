<?php

namespace App\Http\Controllers;

use App\Models\productCodes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductCodesController extends Controller
{
    public static function getAllCodesByProductId($productId) {
        return productCodes::all()
            ->where('productIdCode', '=', $productId);
    }

    public static function makeTable($productId) {
        $productCodes = self::getAllCodesByProductId($productId);
        $tableString = '<h4>Termékhez tartozó kódok:</h4>
                   <table class="table">';
        foreach ($productCodes as $productCode) {
            $tableString .= '<tr>
                    <td>'.$productCode->productCode.'</td>
                  </tr>';
        }
        $tableString .= '</table>';

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
}
