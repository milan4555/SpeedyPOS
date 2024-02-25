<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public static function addCategory($categoryName) {
        return Category::create(['categoryName' => $categoryName]);
    }
}
