<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\StorageUnit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class StorageUnitController extends Controller
{
    public function showStorageUnit($storageUnitId) {
        if ($storageUnitId == 0) {
            return view('storage.storageUnits', [
                'storageUnits' => StorageUnit::all(),
                'selectedStorageId' => $storageUnitId
            ]);
        } else {
            return view('storage.storageUnits', [
                'storageUnits' => StorageUnit::all(),
                'selectedStorageId' => $storageUnitId,
                'selectedStorage' => StorageUnit::find($storageUnitId)
            ]);
        }
    }

    public function addStorageUnit(Request $request) {
        $nextNumber = StorageUnit::where('storageName', 'like', '%Raktárhelység #%')->count();
        $addHelper = [
            'storageName' => $request['storageName'] != '' ? $request['storageName'] : 'Raktárhelység #'.$nextNumber+1,
            'numberOfRows' => $request['storageNumberOfRow'],
            'widthNumber' => $request['storageWidth'],
            'heightNumber' => $request['storageHeight']
        ];

        StorageUnit::create($addHelper);

        return Redirect::back()->with('success', 'Sikeresen felvettél eg új raktárhelységet! Kezdődhet a feltöltés!');
    }

    public function showStorageUnitRow($storageUnitId, $letter) {
        return view('storage.storageUnitRow', [
           'letter' => $letter,
           'selectedStorage' => StorageUnit::find($storageUnitId),
           'selectedStorageId' => $storageUnitId,
           'storageUnits' => StorageUnit::all()
        ]);
    }

    public function showStorageUnitItems($storageUnitId, $letter, $width, $height) {
        return view('storage.storageUnitItems', [
            'letter' => $letter,
            'selectedStorage' => StorageUnit::find($storageUnitId),
            'selectedStorageId' => $storageUnitId,
            'storageUnits' => StorageUnit::all(),
            'width' => $width,
            'height' => $height,
            'products' => Product::all()->where('storagePlace', '=', $storageUnitId.'-'.$letter.$height.'-'.$width)
        ]);
    }
}
