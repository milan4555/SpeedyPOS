<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public static function addCategory($categoryName) {
        $last = Category::orderBy('created_at', 'DESC')->first();
        if ($last == null) {
            $id = 800;
        } else {
            $id = $last->categoryId;
        }
        return Category::create(['categoryId' => $id+1,'categoryName' => $categoryName]);
    }
}
