<?php

namespace App\Http\Controllers;

use App\Models\cashRegisterItem;
use App\Models\Receipt;
use App\Models\recToProd;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

class ReceiptController extends Controller
{
    public function makeReceipt($paymentType) {
        $receiptAdd = [
            'isInvoice' => false,
            'date' => date('Y.m.d h:i:s'),
            'sumPrice' => CashRegisterItemController::getSumPrice(),
            'employeeId' => Auth::user()->employeeId,
            'paymentType' => $paymentType
        ];
        $receipt = Receipt::create($receiptAdd);
        foreach (CashRegisterItem::all() as $item) {
            $helperAdd = [
                'productId' => $item->productIdReg,
                'receiptId' => $receipt->receiptId,
                'quantity' => $item->howMany,
            ];
            recToProd::create($helperAdd);
        }
        CashRegisterItem::truncate();

        return Redirect::to('/cashRegister');
    }
}
