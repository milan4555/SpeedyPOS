<div class="modal fade" id="userDeleteModal" tabindex="-1" role="dialog" aria-labelledby="userDeleteModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header d-flex justify-content-between">
                <h5 class="modal-title" id="userDeleteModalLabel">Felhasználó törlés!</h5>
                <p></p>
                <button type="button" class="btn justify-content-end" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">X</span>
                </button>
            </div>
            <div class="modal-body">
                Figyelem! Felhasználó törlésére készülsz, ezután a felhasználó érvénytelen lesz! Ha folytatni szeretnéd, akkor nyomj a "Folytatás" gombra.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Mégse</button>
                <a href="/settings/userDelete/{{$user->employeeId}}" class="btn btn-primary">Folytatás</a>
            </div>
        </div>
    </div>
</div>
