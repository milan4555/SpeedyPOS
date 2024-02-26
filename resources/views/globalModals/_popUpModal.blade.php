<div id="popUpModal" class="modal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                @if(session()->has('success'))
                    <img src="{{asset('iconsAndLogos/success_icon.png')}}" width="10%" alt="success">
                @elseif(session()->has('error'))
                    <img src="{{asset('iconsAndLogos/error_icon.png')}}" width="10%" alt="error">
                @endif
                <h5 class="modal-title text-start fw-bold">
                    @if(session()->has('success'))
                        Sikeres munkafolyamat!
                    @elseif(session()->has('error'))
                        Hiba történt a munkafolyamatban!
                    @endif
                </h5>
                <button type="button" class="btn btn-lg" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">X</span>
                </button>
            </div>
            <div class="modal-body">
                @if(session()->has('success'))
                    {{session('success')}}
                @elseif(session()->has('error'))
                    {{session('error')}}
                @endif
            </div>
        </div>
    </div>
</div>
