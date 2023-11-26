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
}
