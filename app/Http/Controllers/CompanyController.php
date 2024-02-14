<?php

namespace App\Http\Controllers;

use App\Models\CashRegisterItem;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

class CompanyController extends Controller
{
    public function showAllCompany(Request $request) {
        if ($request->all() == null or ($request['columnSearch'] == '' and $request['columnOrderBy'] == '')) {
            $companies = DB::table('companies')
                ->select('*')
                ->orderBy('companyId')
                ->get()
                ->toArray();
        } else if ($request['columnOrderBy'] != ''){
            $companies = DB::table('companies')
                ->select('*')
                ->orderBy($request['columnOrderBy'] == '' ? 'companyId' : $request['columnOrderBy'])
                ->get()
                ->toArray();
        } else {
            $companies = DB::table('companies')
                ->select('*')
                ->where($request['columnSearch'], 'ilike', '%'.$request['search'].'%')
                ->orderBy($request['columnOrderBy'] == '' ? 'companyId' : $request['columnOrderBy'])
                ->get()
                ->toArray();
        }
        return view('cashRegister/companyList', [
            'companies' => $companies
        ]);
    }

    public function deleteCompany($companyId) {
        DB::table('companies')
            ->where('companyId', '=', $companyId)
            ->delete();

        return Redirect::back();
    }

    public function editCompany(Request $request) {
        $validatedData = $request->validate([
            'companyId' => ['required'],
            'companyName' => ['required'],
            'taxNumber' => ['required'],
            'owner' => ['required'],
            'phoneNumber' => ['required'],
            'postcode' => ['required'],
            'city' => ['required'],
            'street' => ['required'],
            'streetNumber' => ['required'],
            'isSupplier' => ['required']
        ]);
        $company = Company::find($validatedData['companyId']);
        $updatedData = [
            'companyName' => $validatedData['companyName'],
            'taxNumber' => $validatedData['taxNumber'],
            'owner' => $validatedData['owner'],
            'phoneNumber' => $validatedData['phoneNumber'],
            'postcode' => $validatedData['postcode'],
            'city' => $validatedData['city'],
            'street' => $validatedData['street'],
            'streetNumber' => $validatedData['streetNumber'],
            'isSupplier' => $validatedData['isSupplier']
        ];
        $company->update($updatedData);

        Return Redirect::back();
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     *
     * Hozzáadja a kosár tartalmához az kapott cég id-jét, ezzel jelezve, hogy számla lesz,
     * ha 0 értéket kap, akkor kitörli és csak sima blokk lesz
     */
    public function addToCurrentCart(Request $request) {
        DB::table('cash_register_items')
            ->where('howMany', '=', -1)
            ->delete();
        if ($request['companyId'] == 0) {
            return Redirect::back();
        }
        CashRegisterItem::create([
            'productIdReg' => $request['companyId'],
            'cashRegisterNumber' => 1,
            'howMany' => -1
        ]);
        return Redirect::back();
    }
    public function newCompany(Request $request) {
        if ($request->all() == null) {
            return view('cashRegister/companyNew');
        }
        $validated = $request->validate([
            'companyName' => 'required',
            'postcode' => 'required',
        ]);
        $newCompany = [
            'companyName' => $validated['companyName'],
            'postcode' => $validated['postcode'],
            'city' => $request['city'],
            'street' => $request['street'],
            'streetNumber' => $request['streetNumber'],
            'isSupplier' => $request['isSupplier'],
            'taxNumber' => $request['taxNumber'],
            'owner' => $request['owner'],
            'phoneNumber' => $request['phoneNumber']
        ];

        Company::create($newCompany);

        return Redirect::back();
    }
}
