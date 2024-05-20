@extends('settings.settingsTemplate')
@section('settingsBody')
    <div class="row">
        <div class="col-md-6" style="border-right: solid 3px black">
            <h6>Bejelentkezett dolgozó adatai</h6>
            <p>
                <b>Név:</b> {{$authInfo->firstName}} {{$authInfo->lastName}}<br>
                <b>Felhasználónév:</b> {{$authInfo->username}}<br>
                <b>Telefonszám:</b> +36{{$authInfo->phoneNumber}}<br>
                <b>Feladatkör:</b> {{$authInfo->position}}
            </p>
        </div>
        <div class="col-md-6">
            <h6>Jelszó változtatás</h6>
            <form method="post" action="/settings/setNewPassword">
                @csrf
                <input type="password" class="form-control border-dark" id="oldPassword" name="oldPassword" placeholder="Régi jelszó..." >
                <input type="password" class="form-control border-dark my-2" id="newPassword" name="newPassword" placeholder="Új jelszó..." >
                <input type="password" class="form-control border-dark" id="reNewPassword" name="reNewPassword" placeholder="Megerősítés..." >
                <div class="d-flex justify-content-center">
                    <input id="submitPasswordButton" type="submit" class="btn button-blue btn-sm mt-2" value="Megváltoztat">
                </div>
            </form>
        </div>
    </div>
    <script>
        const newPassword = document.getElementById('newPassword');
        const reNewPassword = document.getElementById('reNewPassword');
        const submitPasswordButton = document.getElementById('submitPasswordButton');
        newPassword.addEventListener('change', function() {
           if (newPassword.value == reNewPassword.value) {
               submitPasswordButton.disabled = false;
           } else {
               submitPasswordButton.disabled = true;
           }
        });
        reNewPassword.addEventListener('change', function() {
            if (newPassword.value == reNewPassword.value) {
                submitPasswordButton.disabled = false;
            } else {
                submitPasswordButton.disabled = true;
            }
        });
    </script>
@endsection
