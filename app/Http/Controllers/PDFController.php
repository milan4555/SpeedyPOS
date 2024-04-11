<?php

namespace App\Http\Controllers;

use App\Models\FilePath;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class PDFController extends Controller
{
    public function loadSelector() {
        return view('storage.documents.documentSelector');
    }
    public function getAllPDFByType($PDFtype) {
        $pdfPaths = FilePath::where([['fileType', '=', 'PDF'],['category', '=', $PDFtype]])->get();
        $cardName = '';
        switch ($PDFtype) {
            case 'productIn':
                $cardName = 'Termék bevétel formanyomtatvány';
                break;
            case 'productOut':
                $cardName = 'Termék kiadás formanyomtatvány';
                break;
            case 'inventory':
                $cardName = 'Leltár formanyomtatvány';
                break;
            case 'forStore':
                $cardName = 'Bolti kiadás formanyomtatvány';
        }
        return view('storage.documents.documents', [
            'pdfPaths' => $pdfPaths,
            'PDFtype' => $PDFtype,
            'cardName' => $this->getCardName($PDFtype)
        ]);
    }
    public function getAllPDFByDate(Request $request) {
        $pdfPaths = FilePath::where([['fileType', '=', 'PDF'],['category', '=', $request['PDFtype']]])
            ->where('created_at', '>=', $request['startDate'])
            ->where('created_at', '<=', $request['endDate'])
            ->get();
        return view('storage.documents.documents', [
            'pdfPaths' => $pdfPaths,
            'PDFtype' => $request['PDFtype'],
            'startDate' => $request['startDate'],
            'endDate' => $request['endDate'],
            'cardName' => $this->getCardName($request['PDFtype'])
        ]);
    }
    public function getCardName($PDFtype) {
        $cardName = '';
        switch ($PDFtype) {
            case 'productIn':
                $cardName = 'Termék bevétel formanyomtatvány';
                break;
            case 'productOut':
                $cardName = 'Termék kiadás formanyomtatvány';
                break;
            case 'inventory':
                $cardName = 'Leltár formanyomtatvány';
                break;
            case 'forStore':
                $cardName = 'Bolti kiadás formanyomtatvány';
        }

        return $cardName;
    }
}
