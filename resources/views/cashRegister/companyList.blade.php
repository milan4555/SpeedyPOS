@extends('cashRegister/cashRegisterTemplate')

@section('mainSpace')
    <table class="table table-hover">
        <tr class="table-dark">
            <th scope="col">Azonosító</th>
            <th scope="col">Cég neve</th>
            <th scope="col">Adószám</th>
            <th scope="col">Tulajdonos</th>
            <th scope="col">Telefonszám</th>
            <th scope="col">
                <a href="/cashRegister/companyList/newCompany" class="btn button-blue btn-sm">Új felvétele</a>
            </th>
        </tr>
        @foreach($companies as $company)
            <tr id="row{{$company->companyId}}">
                <th onclick="window.location.href = '/cashRegister/changeCompany/{{$company->companyId}}'">{{$company->companyId}}</th>
                <td onclick="window.location.href = '/cashRegister/changeCompany/{{$company->companyId}}'">{{$company->companyName}}</td>
                <td onclick="window.location.href = '/cashRegister/changeCompany/{{$company->companyId}}'">{{$company->taxNumber}}</td>
                <td onclick="window.location.href = '/cashRegister/changeCompany/{{$company->companyId}}'">{{$company->owner != '' ? $company->owner : 'Nincs megadva'}}</td>
                <td onclick="window.location.href = '/cashRegister/changeCompany/{{$company->companyId}}'">{{$company->phoneNumber != '' ? '+36'.$company->phoneNumber : 'Nincs megadva'}}</td>
                <td>
                    <button type="button" class="btn button-blue btn-sm" data-bs-toggle="collapse" href="#collapseProductCodes{{$company->companyId}}" role="button" aria-expanded="false" aria-controls="collapseExample">Információk</button>
                    <button class="btn button-orange btn-sm" onclick="window.location.href = '/cashRegister/changeCompany/{{$company->companyId}}'">Hozzáadás</button>
                </td>
            </tr>
            <tr class="collapse {{(session()->has('updatedCompany') and session('updatedCompany') == $company->companyId) ? 'show' : ''}}" id="collapseProductCodes{{$company->companyId}}">
                <td colspan="6">
                    <form method="post" action="/cashRegister/companyList/edit">
                        @csrf
                        <input type="hidden" name="companyId" value="{{$company->companyId}}">
                        <div class="container-fluid">
                            <div class="row">
                                <div class="col-md-3">
                                    Cég neve:
                                    <input class="form-control" type="text" name="companyName" value="{{$company->companyName}}" autocomplete="off">
                                </div>
                                <div class="col-md-3">
                                    Adószáma:
                                    <input class="form-control" type="text" name="taxNumber" value="{{$company->taxNumber}}" autocomplete="off">
                                </div>
                                <div class="col-md-3">
                                    Tulajdonos:
                                    <input class="form-control" type="text" name="owner" value="{{$company->owner}}" autocomplete="off">
                                </div>
                                <div class="col-md-3">
                                    Telefonszám:
                                    <input class="form-control" type="number" name="phoneNumber" value="{{$company->phoneNumber}}" autocomplete="off">
                                </div>
                                <div class="col-md-3">
                                    Irányítószám
                                    <input class="form-control" type="number" name="postcode" value="{{$company->postcode}}" autocomplete="off">
                                </div>
                                <div class="col-md-3">
                                    Város:
                                    <input class="form-control" type="text" name="city" value="{{$company->city}}" autocomplete="off">
                                </div>
                                <div class="col-md-3">
                                    Utca:
                                    <input class="form-control" type="text" name="street" value="{{$company->street}}" autocomplete="off">
                                </div>
                                <div class="col-md-3">
                                    Házszám:
                                    <input class="form-control" type="number" name="streetNumber" value="{{$company->streetNumber}}" autocomplete="off">
                                </div>
                                <div class="col-md-3">
                                    Beszállító-e?
                                    <select class="form-control" name="isSupplier">
                                        <option value="True" {{$company->isSupplier ? 'selected' : ''}}>Igen</option>
                                        <option value="False" {{$company->isSupplier ? '' : 'selected'}}>Nem</option>
                                    </select>
                                </div>
                            </div>
                            <div class="d-flex justify-content-end">
                                <button type="button" class="btn button-red mx-2" data-bs-toggle="modal" data-bs-target="#deleteCompany{{$company->companyId}}">
                                    Törlés
                                </button>
                                @include('cashRegister.modals._companyDeleteModal')
                                <input type="submit" class="btn button-blue" value="Módosítás" style="margin: 0">
                            </div>
                        </div>
                    </form>
                </td>
            </tr>
        @endforeach
    </table>
@endsection

@section('buttons')
    <div class="container-fluid">
        <form class=" mt-2">
            @csrf
            <div class="row">
                <div class="col-md-12">
                    Keresés szöveg alapján:
                    <select class="form-control border-dark" name="columnSearch">
                        <option value="">Válassz szűrési lehetőséget!</option>
                        <option value="companyId" {{(isset($columnSearch) and $columnSearch == 'companyId') ? 'selected' : ''}}>Azonosító</option>
                        <option value="companyName" {{(isset($columnSearch) and $columnSearch == 'companyName') ? 'selected' : ''}}>Cég név</option>
                        <option value="taxNumber" {{(isset($columnSearch) and $columnSearch == 'taxNumber') ? 'selected' : ''}}>Adószám</option>
                        <option value="owner" {{(isset($columnSearch) and $columnSearch == 'owner') ? 'selected' : ''}}>Tulajdonos</option>
                        <option value="phoneNumber" {{(isset($columnSearch) and $columnSearch == 'phoneNumber') ? 'selected' : ''}}>Telefonszám</option>
                    </select>
                    <input class="form-control mt-2 border-dark" type="text" placeholder="Pl.: Kiss Pista" name="search" value="{{isset($search) ? $search : ''}}">
                </div>
                <div class="col-md-12 mt-2">
                    Rendezés oszlop szerint:
                    <select class="form-control border-dark" name="columnOrderBy">
                        <option value="">Válassz rendezési lehetőséget!</option>
                        <option value="companyId" {{(isset($columnOrderBy) and $columnOrderBy == 'companyId') ? 'selected' : ''}}>Azonosító</option>
                        <option value="companyName" {{(isset($columnOrderBy) and $columnOrderBy == 'companyName') ? 'selected' : ''}}>Cég név</option>
                        <option value="taxNumber" {{(isset($columnOrderBy) and $columnOrderBy == 'taxNumber') ? 'selected' : ''}}>Adószám</option>
                    </select>
                </div>
            </div>
            <div class="d-flex justify-content-center mt-2">
                <input class="form-control btn button-blue w-25 mx-1" type="submit" value="Szűrés">
                <a href="/cashRegister/companyList" class="btn button-red w-25 mx-1">Törlés</a>
            </div>
        </form>
    </div>
@endsection
