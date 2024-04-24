@if(session()->has('change'))
    <div id="changeModal" class="modal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Visszajáró segédlet</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    @php
                        $arrayKeys = array_keys(session('change'));
                    @endphp
                    @for($i=0;$i<count($arrayKeys);$i++)
                        <p>{{$arrayKeys[$i]}} Ft x {{session('change')[$arrayKeys[$i]]}}</p>
                    @endfor
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Bezárás</button>
                </div>
            </div>
        </div>
    </div>
    <script>
        $(function () {
            $('#changeModal').modal({
                show: true
            });
        });
    </script>
@endif
