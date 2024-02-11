<?php

namespace App\Http\Controllers;

use App\Models\Variable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

class VariableController extends Controller
{
    public static function getVariableValue($variableName) {
        return Variable::all()->where('variableName', '=', $variableName)->get('variableName');
    }

    public function getAllVariables() {
        return view('settings.variablesPage', [
           'variables' => Variable::all()
        ]);
    }

    public function updateVariables(Request $request) {
        $arrayKeys = array_keys($request->all());
        foreach ($arrayKeys as $arrayKey) {
            if ($arrayKey == '_token') {
                continue;
            }
            DB::table('variables')->where('variableShortName', '=', $arrayKey)->update( ['variableValue' => $request[$arrayKey]]);
        }

        return Redirect::back();
    }
}
