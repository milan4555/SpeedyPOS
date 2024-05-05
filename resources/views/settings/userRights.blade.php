@extends('settings.settingsTemplate')
@section('settingsBody')
    <input class="form-control mb-2 border-dark" type="text" id="employeeSearchInput" onkeyup="liveSearchEmployee('employeeTable', 'employeeSearchInput', 0)" placeholder="Keresés név alapján.." title="Type in a name">
    <div class="table-responsive" style="height: 400px">
        <table id="employeeTable" class="table">
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
    <script type="text/javascript" src="{{asset('js/liveSearchTable.js')}}"></script>
@endsection
