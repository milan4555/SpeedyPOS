@extends('settings.settingsTemplate')
@section('settingsBody')
    <form method="post" action="/saveSettings">
        @csrf
        <div class="container-fluid">
            <div class="row">
                @foreach($variables as $variable)
                    <div class="col-md-6 mt-2">
                        {{$variable->variableName}}:
                        <input class="form-control border-dark" type="text" name="{{$variable->variableShortName}}" value="{{$variable->variableValue}}">
                    </div>
                @endforeach
            </div>
        </div>
        <div class="d-flex justify-content-start mt-2">
            <a href="/settings" class="btn button-red" style="margin: 2px">Visszaállítás</a>
            <input class="btn button-blue" type="submit" value="Mentés" style="margin: 2px">
        </div>
    </form>
@endsection
