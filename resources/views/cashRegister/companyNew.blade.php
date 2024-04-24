@extends('cashRegister/cashRegisterTemplate')

@section('mainSpace')
    <div class="p-2">
    <h3>Új cég adatai:</h3>
        <hr>
        <form method="post" action="/cashRegister/companyList/newCompany">
            @csrf
            <input type="hidden" name="companyId" value="}">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-3">
                        Cég neve:
                        <input class="form-control border-dark" type="text" name="companyName" value="{{old('companyName')}}" autocomplete="off" >
                    </div>
                    <div class="col-md-3">
                        Adószáma:
                        <input class="form-control border-dark" type="text" name="taxNumber" value="{{old('taxNumber')}}" autocomplete="off" >
                    </div>
                    <div class="col-md-3">
                        Tulajdonos:
                        <input class="form-control border-dark" type="text" name="owner" value="{{old('owner')}}" autocomplete="off">
                    </div>
                    <div class="col-md-3">
                        Telefonszám:
                        <input class="form-control border-dark" type="number" name="phoneNumber" value="{{old('phoneNumber')}}" autocomplete="off">
                    </div>
                    <div class="col-md-3">
                        Irányítószám
                        <input class="form-control border-dark" type="number" name="postcode" value="{{old('postcode')}}" autocomplete="off" >
                    </div>
                    <div class="col-md-3">
                        Város:
                        <input class="form-control border-dark" type="text" name="city" value="{{old('city')}}" autocomplete="off" >
                    </div>
                    <div class="col-md-3">
                        Utca:
                        <input class="form-control border-dark" type="text" name="street" value="{{old('street')}}" autocomplete="off" >
                    </div>
                    <div class="col-md-3">
                        Házszám:
                        <input class="form-control border-dark" type="number" name="streetNumber" value="{{old('streetNumber')}}" autocomplete="off" >
                    </div>
                    <div class="col-md-3">
                        Beszállító-e?
                        <select class="form-control border-dark" name="isSupplier" required>
                            <option value="">...</option>
                            <option value="True" {{old('isSupplier') == 'True' ? 'selected' : ''}}>Igen</option>
                            <option value="False" {{old('isSupplier') == 'False' ? 'selected' : ''}}>Nem</option>
                        </select>
                    </div>
                </div>
                <div class="d-flex justify-content-center">
                    <input type="submit" class="btn btn-primary mx-2" value="Felvétel">
                    <a href="/cashRegister/companyList" class="btn btn-danger">Mégsem</a>
                </div>
            </div>
        </form>
    </div>
@endsection

@section('buttons')
    <div class="container-fluid">
        <form class=" mt-2">
            @csrf
            <div class="row">
                <div class="col-md-6">
                    Keresés szöveg alapján:
                    <select class="form-control" name="columnSearch" disabled>
                        <option value="">Válassz szűrési lehetőséget!</option>
                        <option value="companyId">Azonosító</option>
                        <option value="companyName">Cég név</option>
                        <option value="taxNumber">Adószám</option>
                        <option value="owner">Tulajdonos</option>
                        <option value="phoneNumber">Telefonszám</option>
                    </select>
                    <input class="form-control mt-2" type="text" placeholder="Pl.: Kiss Pista" name="search" disabled>
                </div>
                <div class="col-md-6">
                    Rendezés oszlop szerint:
                    <select class="form-control" name="columnOrderBy" disabled>
                        <option value="">Válassz rendezési lehetőséget!</option>
                        <option value="companyId">Azonosító</option>
                        <option value="companyName">Cég név</option>
                        <option value="taxNumber">Adószám</option>
                    </select>
                </div>
            </div>
            <div class="d-flex justify-content-center">
                <input class="form-control btn btn-primary mt-2 w-50" type="submit" value="Szűrés" disabled>
            </div>
        </form>
    </div>
@endsection
