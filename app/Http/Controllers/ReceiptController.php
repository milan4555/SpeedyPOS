<?php

namespace App\Http\Controllers;

use App\Models\cashRegisterItem;
use App\Models\Product;
use App\Models\Receipt;
use App\Models\recToProd;
use App\Models\Variable;
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
        $receiptAdd = [
            'isInvoice' => $companyId == null ? 0 : $companyId[0]->productIdReg,
            'date' => date('Y.m.d h:i:s'),
            'change' => $paymentType == 'B' ? 0 : $change,
            'sumPrice' => CashRegisterItemController::getSumPrice(),
            'employeeId' => Auth::user()->employeeId,
            'paymentType' => $paymentType
        ];
        $receipt = Receipt::create($receiptAdd);
        foreach (CashRegisterItem::all()->where('howMany', '!=', -1) as $item) {
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

        return Redirect::back()->with('change', $cashAmounts);
    }

    public function showReceipt($receiptId) {
        $variables = [];
        foreach (Variable::all() as $item) {
            $variables[$item['variableShortName']] = $item['variableValue'];
        }
        if ($receiptId == 0) {
            return view('cashRegister/receiptList', [
                'receipts' => Receipt::all(),
                'receiptNumbers' => Receipt::pluck('receiptId'),
                'variables' => $variables
            ]);
        } else {
            $receiptAllData = DB::table('rec_to_prods')
                ->join('receipts', 'rec_to_prods.receiptId', 'receipts.receiptId')
                ->join('products', 'rec_to_prods.productId', 'products.productId')
                ->select('*')
                ->where('rec_to_prods.receiptId', '=', $receiptId)
                ->get()
                ->toArray();
            return view('cashRegister/receiptList', [
                'receipts' => Receipt::all(),
                'receiptNumbers' => Receipt::pluck('receiptId'),
                'receiptData' => $receiptAllData,
                'variables' => $variables
            ]);
        }
    }
}
