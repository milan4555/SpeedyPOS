@extends('settings.settingsTemplate')
@section('settingsBody')
    <div class="table-responsive" style="height: 400px">
        <table class="table">
            <tbody>
                @foreach($users as $user)
                    <tr>
                        <td>{{$user->firstName}} {{$user->lastName}} <br><b>({{$user->username}})</b></td>
                        <td>{{$user->position}}</td>
                        <td>+36{{$user->phoneNumber}}</td>
                        <td>
                            <div class="d-flex justify-content-end">
                                <a class="btn button-blue btn-sm" href="/settings/setDefaultPassword/{{$user->employeeId}}">Jelszó visszaállítás</a>
                                <button type="button" class="btn button-red btn-sm" data-bs-toggle="modal" data-bs-target="#userDeleteModal">
                                    Eltávolítás
                                </button>
                                @include('settings.modals._userDelete')
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
