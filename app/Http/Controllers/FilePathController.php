<?php

namespace App\Http\Controllers;

use App\Models\FilePath;
use Illuminate\Http\Request;

class FilePathController extends Controller
{
    public static function getFileInfo($category, $outerId) {
        return FilePath::where([['category', '=', $category],['outerId', '=', $outerId]])->first();
    }
}
