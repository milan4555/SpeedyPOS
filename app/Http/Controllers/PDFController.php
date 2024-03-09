<?php

namespace App\Http\Controllers;

use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class PDFController extends Controller
{
    public static function makeProductInPDF($viewArray) {
        $pdf = Pdf::loadView('storage.PDFViews.productInPDFView', $viewArray);
        return $pdf->stream();
    }
}
