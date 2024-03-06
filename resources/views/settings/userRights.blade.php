@extends('settings.settingsTemplate')
@section('settingsBody')
    <table class="table">
        <tbody>
            @foreach($users as $user)
                <tr>
                    <td>{{$user->firstName}} {{$user->lastName}} <b>({{$user->username}})</b></td>
                    <td>Pozíció: {{$user->position}}</td>
                    <td>+36{{$user->phoneNumber}}</td>
                    <td>
                        <div class="d-flex justify-content-end">
                            <button class="btn btn-primary btn-sm mx-2" data-bs-toggle="collapse" href="#userRights{{$user->rightsId}}" role="button" aria-expanded="false" aria-controls="collapseExample">Tulajdonság</button>
                            <a class="btn btn-danger btn-sm">Eltávolítás</a>
                        </div>
                    </td>
                </tr>
                <tr class="collapse" id="userRights{{$user->rightsId}}">
                    <td>Összes be: <input class="switch-event" data-optionname="isSuperior" data-rightsid="{{$user->rightsId}}" type="checkbox" data-toggle="switchbutton" data-size="sm" {{$user->isSuperior ? 'checked' : ''}}></td>
                    <td>Termék létrehozás: <input class="switch-event" data-optionname="canCreateProduct" data-rightsid="{{$user->rightsId}}" type="checkbox" data-toggle="switchbutton" data-size="sm" {{$user->canCreateProduct ? 'checked' : ''}}></td>
                    <td>Termék módosítás: <input class="switch-event" data-optionname="canUpdateProduct" data-rightsid="{{$user->rightsId}}" type="checkbox" data-toggle="switchbutton" data-size="sm" {{$user->canUpdateProduct ? 'checked' : ''}}></td>
                    <td>Termék törlés: <input class="switch-event" data-optionname="canDeleteProduct" data-rightsid="{{$user->rightsId}}" type="checkbox" data-toggle="switchbutton" data-size="sm" {{$user->canDeleteProduct ? 'checked' : ''}}></td>
                </tr>
                <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
                <script>
                    for (let i = 1; i <= 4; i++) {
                        document.getElementById('userRights'+i).addEventListener('change', function(event){
                            var elem = event.target;
                            $.ajax({
                                type: 'GET',
                                url: '/settings/userRights/' + elem.dataset.rightsid + '/' + elem.dataset.optionname,
                                data:{_token: '{{csrf_token()}}'},
                                success: function () {
                                    console.log('lol')
                                }
                            })
                        });
                    }
                </script>
            @endforeach
        </tbody>
    </table>
@endsection
