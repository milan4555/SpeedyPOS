<div class="modal fade" id="inventoryFullReset" tabindex="-1" role="dialog" aria-labelledby="inventoryFullResetLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="inventoryFullResetLabel">Leltározás újrakezdés</h4>
                <button type="button" class="btn" data-dismiss="modal" aria-label="Close" style="margin-left: auto">
                    <span aria-hidden="true">X</span>
                </button>
            </div>
            <div class="modal-body">
                <b>Figyelem! Leltározás újrakezdésére készülsz!</b><br>
                Ha biztos vagy a dolgodban, akkor nyomj a "Folytatás" gombra, egyéb esetben a "Mégse" gombra vagy kattints bárhova, hogy eltűnjön az üzenet.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn button-red" data-dismiss="modal">Mégse</button>
                <a href="/storage/inventoryFullReset/{{$selectedStorageId}}" class="btn button-blue">Folytatás</a>
            </div>
        </div>
    </div>
</div>
