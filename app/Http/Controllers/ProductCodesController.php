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
}
