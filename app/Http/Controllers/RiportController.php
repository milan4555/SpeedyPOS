<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use function PHPUnit\Framework\isNan;
use function Symfony\Component\String\b;

class RiportController extends Controller
{
    public function loadPageDefault() {
        return view('storage.riports.riportPageTemplate');
    }

    public function salesRiport(Request $request) {
        $allProduct = Product::all();
        if (count($request->all()) == 0) {
            return view('storage.riports.salesRiport', [
                'allProduct' => $allProduct
            ]);
        }
        $wholeYear = self::getDatesBetween($request['startDate'], $request['endDate']);
        $sumSales = [];
        $quantityList = [];
        foreach ($wholeYear as $singleDate) {
            $explodedSingleDate = explode('-', $singleDate);
            $salesNumber = DB::table('product_outs')
                ->select('howMany')
                ->where('isCompleted', '=', true)
                ->where('productId', '=', $request['productId'])
                ->whereYear('created_at',  $explodedSingleDate[0])
                ->whereMonth('created_at',  $explodedSingleDate[1])
                ->orderBy('created_at')
                ->sum('howMany');
            $sumSales[$singleDate] = self::getAtTimePrice($request['productId'], $singleDate) * 1.8 * $salesNumber;
            $quantityList[$singleDate] = $salesNumber;
        }
        $sumBought = [];
        $quantityBoughtList = [];
        foreach ($wholeYear as $singleDate) {
            $explodedSingleDate = explode('-', $singleDate);
            $boughtNumber = DB::table('product_in_outs')
                ->select('howMany')
                ->where('isFinished', '=', true)
                ->where('productId', '=', $request['productId'])
                ->whereYear('created_at',  $explodedSingleDate[0])
                ->whereMonth('created_at',  $explodedSingleDate[1])
                ->orderBy('created_at')
                ->sum('howMany');
            $sumBought[$singleDate] = self::getAtTimePrice($request['productId'], $singleDate) * $boughtNumber;
            $quantityBoughtList[$singleDate] = $boughtNumber;
        }
        $priceChange = [];
        foreach ($wholeYear as $singleDate) {
            $explodedSingleDate = explode('-', $singleDate);
            $price = DB::table('product_in_outs')
                ->select('newBPrice')
                ->whereYear('created_at', '<=', $explodedSingleDate[0])
                ->whereMonth('created_at','<=', $explodedSingleDate[1])
                ->where('productId', '=', $request['productId'])
                ->where('isFinished', '=', true)
                ->orderBy('created_at', 'DESC')
                ->first();
            $priceChange[$singleDate] = isset($price->newBPrice ) ? $price->newBPrice : 0;
        }
        return view('storage.riports.salesRiport', [
            'allProduct' => $allProduct,
            'selectedProductId' => $request['productId'],
            'startDate' => $request['startDate'],
            'endDate' => $request['endDate'],
            'salesNumber' => $quantityList,
            'sumSales' => $sumSales,
            'sumQuantity' => $quantityList,
            'sumBought' => $sumBought,
            'quantityBoughtList' => $quantityBoughtList,
            'priceChange' => $priceChange
        ]);
    }
    public function salesRiportAll(Request $request) {
        if (count($request->all()) == 0) {
            return view('storage.riports.salesRiportAll');
        }
        $explodedStartDate = explode('-', $request['startDate']);
        $explodedEndDate = explode('-', $request['endDate']);
        $wholeYear = self::getDatesBetween($request['startDate'], $request['endDate']);
        $topTenProduct = DB::table('product_outs')
            ->selectRaw('"productId", sum("howMany")')
            ->whereYear('created_at', '>=', $explodedStartDate[0])
            ->whereYear('created_at', '<=', $explodedEndDate[0])
            ->whereMonth('created_at', '>=', $explodedStartDate[1])
            ->whereMonth('created_at', '<=', $explodedEndDate[1])
            ->groupBy('productId')
            ->orderBy('sum', 'DESC')
            ->limit(10)
            ->get()
            ->toArray();
        $allBought = [];
        $allSold = [];
        foreach ($wholeYear as $singleDate) {
            $explodedSingleDate = explode('-', $singleDate);
            $allBoughtSum = DB::table('product_in_outs')
                ->select('howMany')
                ->where('isFinished', '=', true)
                ->whereYear('created_at', '=', $explodedSingleDate[0])
                ->whereMonth('created_at', '=', $explodedSingleDate[1])
                ->sum('howMany');
            $allSoldSum = DB::table('product_outs')
                ->select('howMany')
                ->where('isCompleted', '=', true)
                ->whereYear('created_at', '=', $explodedSingleDate[0])
                ->whereMonth('created_at', '=', $explodedSingleDate[1])
                ->sum('howMany');
            $allBought[$singleDate] = $allBoughtSum;
            $allSold[$singleDate] = $allSoldSum;
        }
        $allBoughtPriceSum = [];
        $allSoldPriceSum = [];
        foreach ($wholeYear as $singleDate) {
            $explodedSingleDate = explode('-', $singleDate);
            $allBoughtSum = DB::table('product_in_outs')
                ->selectRaw('"productId", sum("howMany")')
                ->where('isFinished', '=', true)
                ->whereYear('created_at', '=', $explodedSingleDate[0])
                ->whereMonth('created_at', '=', $explodedSingleDate[1])
                ->groupBy('productId')
                ->get()
                ->toArray();
            $allSoldSum = DB::table('product_outs')
                ->selectRaw('"productId", sum("howMany")')
                ->where('isCompleted', '=', true)
                ->whereYear('created_at', '=', $explodedSingleDate[0])
                ->whereMonth('created_at', '=', $explodedSingleDate[1])
                ->groupBy('productId')
                ->get()
                ->toArray();
            $sumBought = 0;
            foreach ($allBoughtSum as $row) {
                $sumBought += self::getAtTimePrice($row->productId, $singleDate)*$row->sum;
            }
            $allBoughtPriceSum[$singleDate] = $sumBought;
            $sumSold = 0;
            foreach ($allSoldSum as $row) {
                $sumSold += intval(self::getAtTimePrice($row->productId, $singleDate)*1.8*$row->sum);
            }
            $allSoldPriceSum[$singleDate] = $sumSold;
        }
        return view('storage.riports.salesRiportAll', [
            'startDate' => $request['startDate'],
            'endDate' => $request['endDate'],
            'topTenProduct' => $topTenProduct,
            'allBought' => $allBought,
            'allSold' => $allSold,
            'allBoughtPriceSum' => $allBoughtPriceSum,
            'allSoldPriceSum' => $allSoldPriceSum,
        ]);
    }
    public static function getAtTimePrice($productId, $date) {
        $explodedDate = explode('-', $date);
        $price = DB::table('product_in_outs')
            ->select('newBPrice')
            ->where('productId', '=', $productId)
            ->whereYear('created_at', '<=', $explodedDate[0])
            ->whereMonth('created_at', '<=', $explodedDate[1])
            ->orderBy('created_at', 'DESC')
            ->first();
        if ($price == null) {
            $price = Product::find($productId)->bPrice;
        } else {
            $price = $price->newBPrice;
        }
        return $price;
    }
    public function getDatesBetween($startDate, $endDate) {
        $dates = [];
        $current = $startDate;
        for ($i = 0; $i < 12; $i++) {
            if ($current > $endDate) {
                break;
            }
            $dates[] = $current;
            $current = date('Y-m',strtotime(date('Y-m', strtotime($current)).'+1 month'));
        }

        return $dates;
    }
}
