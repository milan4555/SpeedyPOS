<?php

namespace App\Http\Controllers;

use App\Models\CashRegisterItem;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;

class CompanyController extends Controller
{
    public function showAllCompany(Request $request) {
        $query = Company::query();
        $query->select('*')->orderBy($request['columnOrderBy'] == '' ? 'companyId' : $request['columnOrderBy']);
        $query->when($request['columnSearch'] != null, function ($query) use ($request) {
           $query->where($request['columnSearch'], 'ilike', '%'.$request['search'].'%');
        });
        $companies = $query->get();
        return view('cashRegister/companyList', [
            'companies' => $companies,
            'columnSearch' => $request['columnSearch'],
            'columnOrderBy' => $request['columnOrderBy'],
            'search' => $request['search']
        ]);
    }

    public function deleteCompany($companyId) {
        DB::table('companies')
            ->where('companyId', '=', $companyId)
            ->delete();

        return Redirect::back()->with('success', 'Sikeresen kitörölted a céget a listából!');
    }

    public function editCompany(Request $request) {
        $validator = Validator::make($request->all(),
            [
                'companyId' => ['required'],
                'companyName' => ['required'],
                'taxNumber' => ['required'],
                'postcode' => ['required'],
                'city' => ['required'],
                'street' => ['required'],
                'streetNumber' => ['required'],
                'isSupplier' => ['required']
            ]
        );
        if ($validator->fails()) {
            return redirect()->back()->with('error', 'Sikertelen művelet! Hiányzó adatok voltak a módosítás során!')->with('updatedCompany', $request['companyId']);
        }
        $company = Company::find($request['companyId']);
        $updatedData = [
            'companyName' => $request['companyName'],
            'taxNumber' => $request['taxNumber'],
            'owner' => $request['owner'],
            'phoneNumber' => $request['phoneNumber'],
            'postcode' => $request['postcode'],
            'city' => $request['city'],
            'street' => $request['street'],
            'streetNumber' => $request['streetNumber'],
            'isSupplier' => $request['isSupplier']
        ];
        $company->update($updatedData);

        Return Redirect::to('/cashRegister/companyList#row'.$company->companyId)->with('success', 'Sikeresen megváltoztattad a paramétereit a cégjegyzéknek!')->with('updatedCompany', $request['companyId']);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     *
     * Hozzáadja a kosár tartalmához az kapott cég id-jét, ezzel jelezve, hogy számla lesz,
     * ha 0 értéket kap, akkor kitörli és csak sima blokk lesz
     */
    public function addToCurrentCart($companyId) {
        if (!UserTimeLogController::doesHaveOpenCashRegister(Auth::id())) {
            return \redirect()->back()->with('error', 'Sikertelen művelet! A cég hozzáadásához elősször nyisd meg a kasszát!');
        }
        DB::table('cash_register_items')
            ->where('howMany', '=', -1)
            ->delete();
        if ($companyId == 0) {
            return Redirect::back();
        }
        CashRegisterItem::create([
            'productIdReg' => $companyId,
            'cashRegisterNumber' => 1,
            'howMany' => -1,
            'currentPrice' => 0
        ]);
        return redirect()->to('/cashRegister');
    }
    public function newCompany(Request $request) {
        if ($request->all() == null) {
            return view('cashRegister/companyNew');
        }
        $validator = Validator::make($request->all(), [
            'companyName' => 'required',
            'postcode' => 'required',
            'city' => 'required',
            'street' => 'required',
            'streetNumber' => 'required',
            'isSupplier' => 'required',
            'taxNumber' => 'required',
        ]);
        if ($validator->fails()) {
            return \redirect()->back()->with('error', 'Sikertelen művelet! Hiányzó adatok vannak!')->withInput();
        }
        $newCompany = [
            'companyName' => $request['companyName'],
            'postcode' => $request['postcode'],
            'city' => $request['city'],
            'street' => $request['street'],
            'streetNumber' => $request['streetNumber'],
            'isSupplier' => $request['isSupplier'],
            'taxNumber' => $request['taxNumber'],
            'owner' => $request['owner'],
            'phoneNumber' => $request['phoneNumber']
        ];

        $row = Company::create($newCompany);

        return Redirect::to('/cashRegister/companyList#row'.$row->companyId)->with('success', 'Sikeresen felvetted a céget a listába!')->with('updatedCompany', $row->companyId);
    }
}
