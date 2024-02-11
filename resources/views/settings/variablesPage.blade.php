@extends('settings.settingsTemplate')
@section('settingsBody')
    <form method="post" action="/saveSettings">
        @csrf
        @foreach($variables as $variable)
            {{$variable->variableName}}:
            <input class="form-control" type="text" name="{{$variable->variableShortName}}" value="{{$variable->variableValue}}">
        @endforeach
        <div class="pt-2">
            <a href="/settings" class="btn btn-danger">Visszaállítás</a>
            <input class="btn btn-primary" type="submit" value="Mentés">
        </div>
    </form>
@endsection
