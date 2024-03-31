<div class="modal fade" id="inventoryFinish" tabindex="-1" role="dialog" aria-labelledby="inventoryFinishLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="inventoryFinishLabel">Leltározás befejezése</h4>
                <button type="button" class="btn" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">X</span>
                </button>
            </div>
            <div class="modal-body">
                <b>Figyelem! Leltározás befejezésére készülsz!</b><br>
                Ha már biztosan minden tárgyat beolvastál, akkor nyomj a "Folytatás" gombra, egyéb esetben a "Mégse" gombra vagy kattints bárhova, hogy eltűnjön az üzenet.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Mégse</button>
                <a href="/storage/inventoryMakePDF/{{$selectedStorageId}}" class="btn btn-primary">Folytatás</a>
            </div>
        </div>
    </div>
</div>
