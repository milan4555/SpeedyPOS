<div class="modal fade" id="productOutRestoreProgress" tabindex="-1" role="dialog" aria-labelledby="productOutRestoreProgressLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="productOutRestoreProgressLabel">Termék kiadás újrakezdés</h4>
                <button type="button" class="btn" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">X</span>
                </button>
            </div>
            <div class="modal-body">
                <b>Figyelem! Termék kiadás újrakezdésére készülsz!</b><br>
                Ha biztos vagy a dolgodban, akkor nyomj a "Folytatás" gombra, egyéb esetben a "Mégse" gombra vagy kattints bárhova, hogy eltűnjön az üzenet.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Mégse</button>
                <a href="/storage/productOut/restoreProgress/{{$orderNumber}}" class="btn btn-primary">Folytatás</a>
            </div>
        </div>
    </div>
</div>