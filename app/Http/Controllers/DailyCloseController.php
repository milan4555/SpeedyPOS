<?php

namespace App\Http\Controllers;

use App\Models\DailyClose;
use App\Models\Receipt;
use App\Models\UserTimeLog;

class DailyCloseController extends Controller
{
    public function closeDay() {
        $openRows = UserTimeLog::where('endTime', '=', null)
            ->whereYear('startTime', date('Y'))
            ->whereMonth('startTime', date('m'))
            ->whereDay('startTime', date('d'))
            ->count();

        if ($openRows > 0) {
            return redirect()->back()->with('error', 'Sikertelen művelet! Először zárj le minden kasszát, majd próbáld meg újra!');
        }
        if (self::isDayClosed()) {
            return redirect()->back()->with('error', 'Sikertelen művelet! A mai nap már le van zárva, ideje hazamenni!');
        }
        $cardSum = Receipt::where('paymentType', '=', 'B')
            ->whereYear('date', date('Y'))
            ->whereMonth('date', date('m'))
            ->whereDay('date', date('d'))
            ->sum('sumPrice');
        $cashSum = Receipt::where('paymentType', '=', 'C')
            ->whereYear('date', date('Y'))
            ->whereMonth('date', date('m'))
            ->whereDay('date', date('d'))
            ->sum('sumPrice');
        $totalNumberOfCustomers = Receipt::whereYear('date', date('Y'))
            ->whereMonth('date', date('m'))
            ->whereDay('date', date('d'))
            ->count();
        DailyClose::create([
            'closeDay' => date('Y.m.d'),
            'cardSum' => $cardSum,
            'cashSum' => $cashSum,
            'profit' => $cardSum+$cashSum,
            'totalNumberOfCustomers' => $totalNumberOfCustomers
        ]);

        return redirect()->back()->with('success', 'Sikeres napi zárás!');
    }

    public static function isDayClosed() {
        $closedDay = DailyClose::whereYear('closeDay', date('Y'))
            ->whereMonth('closeDay', date('m'))
            ->whereDay('closeDay', date('d'))
            ->first();
        if ($closedDay == null) {
            return false;
        }
        return true;
    }
}
