<div class="modal fade" id="newStorageModal" tabindex="-1" role="dialog" aria-labelledby="newStorageModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-secondary text-white">
                <h5 class="modal-title" id="newStorageModalLabel">Új raktárhelység felvétele</h5>
                <button type="button" class="btn btn-lg" data-dismiss="modal" style="margin-left: auto">
                    <span aria-hidden="true">X</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="/storage/storageUnits/add" method="post">
                    @csrf
                    <label for="storageName">Raktár megnevezés:</label>
                    <input type="text" name="storageName" class="form-control border border-3 border-dark" id="storageName" aria-describedby="storageNameHelp" placeholder="Raktárhelység #1">
                    <small id="storageNameHelp" class="form-text text-muted">Nem kötelező, de ekkor a fent látható név fog szerepelni!</small><br>
                    Paraméterek:
                    <div class="row">
                        <div class="col-md-4">
                            <label for="storageNumberOfRow">Sorok száma:</label>
                            <input type="number" name="storageNumberOfRow" class="form-control border border-3 border-dark" id="storageNumberOfRow" min="1" required>
                        </div>
                        <div class="col-md-4">
                            <label for="storageWidth">Fakkok száma:</label>
                            <input type="number" name="storageWidth" class="form-control border border-3 border-dark" id="storageWidth" min="1" required>
                        </div>
                        <div class="col-md-4">
                            <label for="storageHeight">Polcok száma:</label>
                            <input type="number" name="storageHeight" class="form-control border border-3 border-dark" id="storageHeight" min="1" required>
                        </div>
                    </div>
                    <div class="d-flex justify-content-end pt-2">
                        <button type="button" class="btn button-red" data-dismiss="modal">Mégsem</button>
                        <input type="submit" class="btn button-blue mx-2" value="Felvétel">
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
