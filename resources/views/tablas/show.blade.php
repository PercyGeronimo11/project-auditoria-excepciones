@extends('layout.layout')

@section('title', 'Formulario de Conexión')

@section('content')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://kit.fontawesome.com/7920972db5.js" crossorigin="anonymous"></script>

    <div class="container mt-5">
        <h2 class="mb-4"> <b> INTEGRIDAD DE TABLAS: EXCEPCIONES ENCONTRADAS </b></h2>
        <div class="d-flex">
            <a href="{{ route('integridadtablas.cancelar') }}">
                <button type="button" class="btn btn-warning">
                    Atrás
                </button>
            </a>
            <form action="{{ route('integridadtablas.exportpdf',$integridad->id) }}" method="POST">
                @csrf
                <input type="hidden" name="listExceptions" value="{{ json_encode($listExceptions) }}">
                <input type="hidden" name="numExcepciones" value="{{ $numExcepciones }}">
                <input type="hidden" name="nameTable" value="{{ $nameTable }}">
                <input type="hidden" name="nameTableRef" value="{{ $nameTableRef }}">
                <button type="submit" class="btn btn-danger">Exportar PDF</button>
            </form>
        </div>

        <br>
        <div class="card">
            <div class="card-header"  style="background-color: rgb(0, 247, 255); color: black;" >
                <div class="text-center">
                    <h2>RESULTADOS DEL ANALISIS</h2>
                </div>
            </div>
            @if (count($listExceptions) > 0)
            <div class="card-body"  style="background-color: red; color: white;">
                <div>
                    <h2>Se encontro {{ $numExcepciones }} excepciones</h2>
                </div>
            </div>
            <table class="table table-striped table-danger">
                <thead style="background-color: red; color: white;">
                    <tr>
                        <th scope="col" class="text-center">N°</th>
                        <th scope="col" class="text-center">Tabla Evaluada</th>
                        <th scope="col" class="text-center">Registro</th>
                        <th scope="col" class="text-center">Clave Foranea [{{$integridad->column_foreignkey}}]</th>
                        <th scope="col" class="text-center">Tabla Referenciada</th>
                        <th scope="col" class="text-center">Resultado</th>
                    </tr>
                </thead>
                <tbody>
                        @php $index = 1; @endphp
                        @foreach($listExceptions as $item)
                        <tr>
                            <th scope="row">{{ $index }}</th>
                            <td class="text-center">{{ $integridad->table }}</td>
                            <td class="text-center">{{ $item['keyPrimaryTable'] }}</td>
                            <td class="text-center">{{ $item['keyForeignTable'] }}</td>
                            <td class="text-center" >{{ $integridad->table_refer }}</td>
                            <td>{{ $item['message']  }}</td>
                        </tr>
                        @php $index++; @endphp
                        @endforeach
                </tbody>
            </table>
            @else
            <div class="card-body"style="background-color: aquamarine">
                <h3> No Se encontraron excepciones</h3>
            </div>
        @endif
        </div>

    </div>

@endsection
