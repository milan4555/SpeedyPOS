<?php

namespace App\Http\Controllers;

use App\Models\cashRegisterItem;
use App\Models\Product;
use App\Models\Receipt;
use App\Models\recToProd;
use App\Models\Variable;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

class ReceiptController extends Controller
{
    /**
     * @param $paymentType
     * @return \Illuminate\Http\RedirectResponse
     *
     * Inputként megkapja, hogy milyen fizetési móddal történt a vásárlás, majd létrehozza az új sort a táblában,
     * majd a kapcsolótáblában, és legvégül kiüríti a CashRegister táblát, ezzel elindítva az újabb folyamatot.
     */
    public function makeReceipt($paymentType, $cashGiven) {
        $companyId = DB::table('cash_register_items')->select('productIdReg')->where('howMany', '=', -1)->get()->toArray();
        $change = $cashGiven - CashRegisterItemController::getSumPrice();
        $cashRegisterItems = CashRegisterItem::all()->where('howMany', '!=', -1);
        if (count($cashRegisterItems) == 0) {
            return \redirect()->back()->with('error', "Sikeretelen művelet! Üres volt a kosár így nem történt semmi!");
        }
        $randomSerialNumber = 0;
        while ($randomSerialNumber == 0) {
            $random = rand(1111111, 9999999);
            if (Receipt::where('receiptSerialNumber', '=', $random)->count() == 0) {
                $randomSerialNumber = $random;
            }
        }
        $receiptAdd = [
            'receiptSerialNumber' => $randomSerialNumber,
            'isInvoice' => $companyId == null ? 0 : $companyId[0]->productIdReg,
            'date' => date('Y.m.d h:i:s'),
            'change' => $paymentType == 'B' ? 0 : $change,
            'sumPrice' => CashRegisterItemController::getSumPrice(),
            'employeeId' => Auth::user()->employeeId,
            'paymentType' => $paymentType
        ];
        $receipt = Receipt::create($receiptAdd);
        foreach ($cashRegisterItems as $item) {
            $helperAdd = [
                'productId' => $item->productIdReg,
                'receiptId' => $receipt->receiptId,
                'quantity' => $item->howMany,
                'atTimePrice' => Product::find($item->productIdReg)->bPrice
            ];
            recToProd::create($helperAdd);
        }
        $dividers = [10000, 5000, 2000, 1000, 500, 200, 100, 50, 20, 10, 5];
        $cashAmounts = [];
        $usedDividers = [];
        foreach ($dividers as $divider) {
            if ($divider >= $change) {
                continue;
            }
            $data = (int) ($change / $divider);
            $cashAmounts[$divider] = $data;
            $change -= $data*$divider;
            if (!in_array($divider, $usedDividers)) {
                $usedDividers[] = $divider;
            }
        }
        CashRegisterItem::truncate();
        $stringBody = 'Sikeres vásárlás!<br><p>';
        $arrayKeys = array_keys($cashAmounts);
        for ($i = 0; $i < count($arrayKeys); $i++) {
            $stringBody .= $arrayKeys[$i].' Ft x '.$cashAmounts[$arrayKeys[$i]].'<br>';
        }
        $stringBody .= '</p>';
        if ($cashAmounts == null) {
            $stringBody = 'Pontos összeget kaptál, így nem kell visszajárót adnod!';
        }
        return Redirect::back()->with('success', $stringBody);
    }

    public function showReceipt(Request $request) {
        $variables = [];
        foreach (Variable::all() as $item) {
            $variables[$item['variableShortName']] = $item['variableValue'];
        }
        if ($request->all() == []) {
            return view('cashRegister/receiptList', [
                'receipts' => Receipt::all(),
                'receiptNumbers' => Receipt::pluck('receiptId'),
                'variables' => $variables
            ]);
        } else {
            if ($request['shownReceiptId'] != null) {
                $receiptAllData = DB::table('rec_to_prods')
                    ->join('receipts', 'rec_to_prods.receiptId', 'receipts.receiptId')
                    ->join('products', 'rec_to_prods.productId', 'products.productId')
                    ->select('receipts.*', 'products.*', 'rec_to_prods.quantity', 'rec_to_prods.atTimePrice')
                    ->where('rec_to_prods.receiptId', '=', $request['shownReceiptId'])
                    ->orderBy('receipts.created_at')
                    ->get()
                    ->toArray();
            }
            $receipts = DB::table('receipts')
                ->when($request['paymentType'] != null, function (Builder $query) use ($request) {
                    $query->where('paymentType', '=' ,$request['paymentType']);
                })
                ->when($request['receiptType'] != null, function (Builder $query) use ($request) {
                    if ($request['receiptType'] == 'NY') {
                        $query->where('isInvoice', '=', 0);
                    } else {
                        $query->where('isInvoice', '!=', 0);
                    }
                })
                ->when($request['startDate'] != null, function (Builder $query) use ($request) {
                    $query->where('created_at', '>=', $request['startDate']);
                })
                ->when($request['endDate'] != null, function (Builder $query) use ($request) {
                    $query->where('created_at', '<=', $request['endDate']);
                })->orderBy('receipts.created_at', 'DESC')->get()
                ->toArray();
            $viewArray = [
                'receipts' => $receipts,
                'variables' => $variables,
                'startDate' => $request['startDate'] != null ? $request['startDate'] : '',
                'endDate' => $request['endDate'] != null ? $request['endDate'] : '',
                'receiptType' => $request['receiptType'] != null ? $request['receiptType'] : '',
                'paymentType' => $request['paymentType'] != null ? $request['paymentType'] : ''
            ];
            if (isset($receiptAllData)) {
                $viewArray['receiptData'] = $receiptAllData;
            }
            return view('cashRegister/receiptList', $viewArray);
        }
    }
}
