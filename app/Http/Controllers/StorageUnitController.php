<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\StorageUnit;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Zebra\Zpl\Builder;

class StorageUnitController extends Controller
{
    public function showStorageUnit($storageUnitId) {
        if ($storageUnitId == 0) {
            return view('storage.storageUnits.storageUnits', [
                'storageUnits' => StorageUnit::all(),
                'selectedStorageId' => $storageUnitId
            ]);
        } else {
            return view('storage.storageUnits.storageUnits', [
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
        return view('storage.storageUnits.storageUnitRow', [
           'letter' => $letter,
           'selectedStorage' => StorageUnit::find($storageUnitId),
           'selectedStorageId' => $storageUnitId,
           'storageUnits' => StorageUnit::all()
        ]);
    }

    public function showStorageUnitItems($storageUnitId, $letter, $width, $height) {
        return view('storage.storageUnits.storageUnitItems', [
            'letter' => $letter,
            'selectedStorage' => StorageUnit::find($storageUnitId),
            'selectedStorageId' => $storageUnitId,
            'storageUnits' => StorageUnit::all(),
            'width' => $width,
            'height' => $height,
            'products' => Product::all()->where('storagePlace', '=', $storageUnitId.'-'.$letter.$height.'-'.$width)
        ]);
    }

    public function printStorageLabels($labelType, $storageId, $letter = '', $width = 0, $height = 0) {
        $zpl = '';
        $storage = StorageUnit::find($storageId);
        $fp=pfsockopen("127.0.0.1",9100);
        if ($labelType == 'rows') {
            $abc = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
            for ($i = 0; $i < $storage->numberOfRows; $i++) {
                $zpl = '
                ^XA
                ^CI28
                ^FWR
                ^CF0,900
                ^FO0,30^FD'.$abc[$i].'^FS
                ^XZ
                ';
                fputs($fp,$zpl);
            }
        } else if ($labelType == 'row') {
            for ($i = $storage->heightNumber;$i < 0;$i--) {
                for ($j = 1;$j < $storage->widthNumber+1;$j++) {
                    $zpl = '
                    ^XA
                    ^CI28
                    ^CF0,100
                    ^FO270,40^FD'.$storage->storageName.'^FS
                    ^FO370,150^FD'.$storage->storageId.'-'.$letter.($i > 9 ? $i : '0'.$i).'-'.($j > 9 ? $j : '0'.$j).'^FS
                    ^BY4,3,70
                    ^FO270,240^BCN,280,N^FDSLF-1-A01-01^FS
                    ^XZ
                    ';

                    fputs($fp,$zpl);
                }
            }
        } else if ($labelType == 'specific') {
            $zpl = '
            ^XA
            ^CI28
            ^CF0,100
            ^FO270,40^FD'.$storage->storageName.'^FS
            ^FO370,150^FD'.$storage->storageId.'-'.$letter.($height > 9 ? $height : '0'.$height).'-'.($width > 9 ? $width : '0'.$width).'^FS
            ^BY4,3,70
            ^FO270,240^BCN,280,N^FDSLF-1-A01-01^FS
            ^XZ
            ';
            fputs($fp,$zpl);
        }
        fclose($fp);
        return Redirect::back()->with('success', 'Nyomtatás megkezdődött!');
    }
}
