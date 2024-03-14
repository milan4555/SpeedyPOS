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
        <div class="pt-2">
            <a href="/settings" class="btn btn-danger">Visszaállítás</a>
            <input class="btn btn-primary" type="submit" value="Mentés">
        </div>
    </form>
@endsection
