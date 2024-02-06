@extends('layouts.menu')
@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header bg-dark text-white text-center"><h5>Beállítások</h5></div>
                    <div class="card-body">
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
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
