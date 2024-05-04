<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\StoragePlace;
use App\Models\StorageUnit;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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

    public function searchStorageUnit($searchedId) {
        if (!StoragePlaceController::checkIfStoragePlaceExists($searchedId)) {
            return redirect()->back()->with('error', 'Sikertelen keresés! Ilyen raktárhelykód nem létezik! Próbáld meg újra, vagy adj meg egy másikat!');
        }
        $explodedStoragePlace = explode('-', $searchedId);
        $heightNumber = substr($explodedStoragePlace[1], 1);
        return redirect()->to('/storage/storageUnit/'.$explodedStoragePlace[0].'/'.$explodedStoragePlace[1][0].'/'.$heightNumber.'/'.$explodedStoragePlace[2]);
    }

    public static function checkIfStorageItemIsEmpty($storagePlace) {
        if (StoragePlace::where('storagePlace', '=', $storagePlace)->count() > 0) {
            return true;
        }

        return false;
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

    public function showStorageUnitItems($storageUnitId, $letter, $height, $width) {
        return view('storage.storageUnits.storageUnitItems', [
            'letter' => $letter,
            'selectedStorage' => StorageUnit::find($storageUnitId),
            'selectedStorageId' => $storageUnitId,
            'storageUnits' => StorageUnit::all(),
            'width' => $width,
            'height' => $height,
            'products' => DB::table('products')
                ->leftJoin('storage_places', 'products.productId', 'storage_places.productId')
                ->leftJoin('categories', 'products.categoryId', 'categories.categoryId')
                ->leftJoin('companies', 'products.companyId', 'companies.companyId')
                ->select('*')
                ->where('storage_places.storagePlace', '=', $storageUnitId.'-'.$letter.$height.'-'.$width)
                ->get()
                ->toArray()
        ]);
    }

    public function printStorageLabels($labelType, $storageId, $letter = '', $width = 0, $height = 0) {
        $storage = StorageUnit::find($storageId);
        try {
            $fp = pfsockopen("127.0.0.1", 9100);
        } catch (Exception $e) {
            return \redirect()->back()->with('error', 'Sikertelen művelet! A nyomatató nem elérhető a számítógép számára a hálózaton keresztül!');
        }
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
