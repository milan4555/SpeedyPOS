@extends('cashRegister/cashRegisterTemplate')

@section('mainSpace')
    <table class="table table-striped">
        <tr>
            <th scope="col">Azonosító</th>
            <th scope="col">Cég neve</th>
            <th scope="col">Adószám</th>
            <th scope="col">Tulajdonos</th>
            <th scope="col">Telefonszám</th>
            <th scope="col"></th>
        </tr>
        @foreach($companies as $company)
            <tr>
                <th>{{$company->companyId}}</th>
                <td>{{$company->companyName}}</td>
                <td>{{$company->taxNumber}}</td>
                <td>{{$company->owner}}</td>
                <td>+36{{$company->phoneNumber}}</td>
                <td><a class="btn btn-primary btn-sm" data-bs-toggle="collapse" href="#collapseProductCodes{{$company->companyId}}" role="button" aria-expanded="false" aria-controls="collapseExample">Információk</a></td>
            </tr>
            <tr class="collapse" id="collapseProductCodes{{$company->companyId}}">
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
                                    <input class="form-control" type="text" name="phoneNumber" value="{{$company->phoneNumber}}" autocomplete="off">
                                </div>
                                <div class="col-md-3">
                                    Irányítószám
                                    <input class="form-control" type="text" name="postcode" value="{{$company->postcode}}" autocomplete="off">
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
                                    <input class="form-control" type="text" name="streetNumber" value="{{$company->streetNumber}}" autocomplete="off">
                                </div>
                                <div class="col-md-3">
                                    Beszállító-e?
                                    <select class="form-control" name="isSupplier">
                                        <option value="True" {{$company->isSupplier ? 'selected' : ''}}>Igen</option>
                                        <option value="True" {{$company->isSupplier ? '' : 'selected'}}>Nem</option>
                                    </select>
                                </div>
                            </div>
                            <div class="d-flex justify-content-end">
                                <a href="/cashRegister/companyList/delete/{{$company->companyId}}" class="btn btn-danger mx-2">Törlés</a>
                                <input type="submit" class="btn btn-primary" value="Módosítás">
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
                <div class="col-md-6">
                    Keresés szöveg alapján:
                    <select class="form-control" name="columnSearch">
                        <option value="">Válassz szűrési lehetőséget!</option>
                        <option value="companyId">Azonosító</option>
                        <option value="companyName">Cég név</option>
                        <option value="taxNumber">Adószám</option>
                        <option value="owner">Tulajdonos</option>
                        <option value="phoneNumber">Telefonszám</option>
                    </select>
                    <input class="form-control mt-2" type="text" placeholder="Pl.: Kiss Pista" name="search">
                </div>
                <div class="col-md-6">
                    Rendezés oszlop szerint:
                    <select class="form-control" name="columnOrderBy">
                        <option value="">Válassz rendezési lehetőséget!</option>
                        <option value="companyId">Azonosító</option>
                        <option value="companyName">Cég név</option>
                        <option value="taxNumber">Adószám</option>
                    </select>
                </div>
            </div>
            <div class="d-flex justify-content-center">
                <input class="form-control btn btn-primary mt-2 w-50" type="submit" value="Szűrés">
            </div>
        </form>
    </div>
@endsection
