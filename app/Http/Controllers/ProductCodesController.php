<?php

namespace App\Http\Controllers;

use App\Models\productCodes;
use Illuminate\Http\Request;

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
}
