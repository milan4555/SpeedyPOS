@extends('storage.documents.documentSelector')
@section('PDFcontent')
    @if(count($pdfPaths) == 0)
        <h4 class="text-center">Nincsen megjeleníthető dokumentum!</h4>
    @else
        <div class="container-fluid">
            <div class="row">
                @foreach($pdfPaths as $pdfPath)
                    <div class="col-auto">
                        <div class="card border border-2 border-dark">
                            <div class="card-body">
                                <h5 class="card-title">{{$cardName}}</h5>
                                <h6 class="card-subtitle mb-2 text-muted">Típus: PDF</h6>
                                <p class="card-text"><b>Név:</b> {{$pdfPath->fileName}}<br><b>Létrehozás dátuma:</b> {{$pdfPath->created_at}}</p>
                                <div class="d-flex justify-content-center">
                                    <a href="{{asset($pdfPath->fileType.'/'.$pdfPath->fileName)}}" class="btn button-red btn-sm">PDF megnyitása</a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif
@endsection
