<?php

namespace App\Http\Controllers;

use App\Models\UserTimeLog;
use Illuminate\Support\Facades\Auth;

class UserTimeLogController extends Controller
{
    public function getTimeFormat() {
        return 'Y-m-d H:i';
    }
    public function openCashRegister($employeeId) {
        if ($this->doesHaveOpenCashRegister($employeeId)) {
            return redirect()->back()->with('error', 'Sikertelen kasszanyitás! Az alábbi felhasználóval ezen a napon már van nyitott kassza! Először zárd le az előzőt, majd utána próbáld újra!');
        }
        if(DailyCloseController::isDayClosed()) {
            return redirect()->back()->with('error', 'Sikeretlen művelet! A mai nap már le van zárva, így nem tudsz új kasszát nyitni!');
        }
        UserTimeLog::create([
            'startTime' => date($this->getTimeFormat()),
            'employeeId' => Auth::id()
        ]);

        return redirect()->back()->with('success', 'Sikeres kasszanyitás! Kellemes és eredményes munkát kívánok neked!');
    }
    public function closeCashRegister($employeeId) {
        if (!$this->doesHaveOpenCashRegister($employeeId)) {
            return redirect()->back()->with('error', 'Sikertelen kasszazárás! Nincsen olyan kassza amelynél a felhasználó szerepel!');
        }
        $employeeRow = UserTimeLog::where([
            ['employeeId', '=', $employeeId],
            ['endTime', '=', null]
        ])->orderBy('startTime', 'DESC')->first();

        $totalHours = date_diff(date_create(date($this->getTimeFormat())), date_create($employeeRow->startTime));
        $withOutBreak = $totalHours;
        if ($employeeRow->breakTime != null) {
            foreach ($employeeRow->breakTime as $singleBreak) {
                $dateDiff = date_diff(date_create($singleBreak[1]), date_create($singleBreak[0]));
                $withOutBreak = date_diff(date_create(date($withOutBreak->format('%h:%i'))), date_create(date($dateDiff->format('%h:%i'))));
            }
        }
        $breakTimeSum = date_diff(date_create(date($totalHours->format('%h:%i'))), date_create(date($withOutBreak->format('%h:%i'))));
        $employeeRow->update([
            'endTime' => date($this->getTimeFormat()),
            'hoursWorked' => $withOutBreak->format('%h:%i'),
            'breakSum' => $breakTimeSum->format('%h:%i'),
            'totalWorkedHours' => $totalHours->format('%h:%i')
        ]);

        return redirect()->back()->with('success', 'Sikeres kasszazárás! További szép napot kívánok!');
    }

    public static function doesHaveOpenCashRegister($employeeId) {
        $employeeIdRow = UserTimeLog::where([
            ['employeeId', '=', $employeeId],
            ['endTime', '=', null]
        ])->orderBy('startTime', 'DESC')->first();

        if ($employeeIdRow == null) {
            return false;
        }
        return true;
    }

    public static function isOnBreak($employeeId) {
        $openRow = UserTimeLog::where([
            ['employeeId', '=', $employeeId],
            ['endTime', '=', null]
        ])->orderBy('startTime', 'DESC')->first();
        if ($openRow == null) {
            return false;
        }
        $breakArray = $openRow->breakTime == null ? [] : $openRow->breakTime;

        foreach ($breakArray as $singleBreak) {
            if (count($singleBreak) != 2) {
                return true;
            }
        }

        return false;
    }

    public function getOpenRow($employeeId) {
        return UserTimeLog::where([
            ['employeeId', '=', $employeeId],
            ['endTime', '=', null]
        ])->orderBy('startTime', 'DESC')->first();
    }

    public function haveABreak($employeeId) {
        if (!$this->doesHaveOpenCashRegister($employeeId)) {
            return redirect()->back()->with('error', 'Sikertelen művelet! Nincsen olyan kassza amelynél a felhasználó szerepel! Először nyiss meg egy kasszát majd próbáld újra!');
        }

        $openRow = $this->getOpenRow($employeeId);
        $breakArray = $openRow->breakTime == null ? [] : $openRow->breakTime;

        foreach ($breakArray as $singleBreak) {
            if (count($singleBreak) != 2) {
                return redirect()->back()->with('error', 'Sikertelen művelet! Van egy befejeztlen szüneted! Először zárd le az előzőt, majd utána tudsz újjat nyitni!');
            }
        }
        $breakArray[] = [date('H:i')];
        $openRow->update(['breakTime' => $breakArray]);

        return redirect()->back()->with('success', 'Sikeres művelet! Kellemes szünetet kívánok!');
    }

    public function closeBreak($employeeId) {
        if (!$this->doesHaveOpenCashRegister($employeeId)) {
            return redirect()->back()->with('error', 'Sikertelen művelet! Nincsen olyan kassza amelynél a felhasználó szerepel! Először nyiss meg egy kasszát majd próbáld újra!');
        }

        $openRow = $this->getOpenRow($employeeId);
        $breakArray = $openRow->breakTime;
        if ($breakArray == null or $breakArray == []) {
            return redirect()->back()->with('error', 'Sikertelen művelet! Még nincsen megkezdett szüneted!');
        }
        if (count(end($breakArray)) == 2) {
            return redirect()->back()->with('error', 'Sikertelen művelet! Még nincsen megkezdett szüneted!');
        }
        $breakArray[count($breakArray)-1][] = date('H:i');
        $openRow->update(['breakTime' => $breakArray]);

        return redirect()->back()->with('success', 'Sikeres művelet! További hasznos munkát kívánok!');
    }
}
