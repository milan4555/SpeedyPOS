<div class="modal fade" id="deleteCompany{{$company->companyId}}" tabindex="-1" role="dialog" aria-labelledby="deleteCompanyLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header  d-flex justify-content-between">
                <h5 class="modal-title" id="deleteCompanyLabel">Cég törlése a listából!</h5>
                <p></p>
                <button type="button" class="btn justify-content-end" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">X</span>
                </button>
            </div>
            <div class="modal-body">
                Biztosan törölni akarod a kiválaszott céget a listából?
                <table class="table table-sm table-bordered mt-2">
                    <tr>
                        <th>Azonosító</th>
                        <th>Név</th>
                        <th>Adószám</th>
                    </tr>
                    <tr>
                        <th>{{$company->companyId}}</th>
                        <td>{{$company->companyName}}</td>
                        <td>{{$company->taxNumber}}</td>
                    </tr>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Mégse</button>
                <a href="/cashRegister/companyList/delete/{{$company->companyId}}" class="btn btn-primary">Folytatás</a>
            </div>
        </div>
    </div>
</div>
