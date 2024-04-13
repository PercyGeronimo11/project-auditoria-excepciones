@extends('layout.layout')

@section('title', 'Formulario de Conexión')

@section('content')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://kit.fontawesome.com/7920972db5.js" crossorigin="anonymous"></script>

    <div class="container mt-5">
        <h2 class="mb-4"> <b> INTEGRIDAD DE TABLAS: EXCEPCIONES ENCONTRADAS </b></h2>
        <div class="card">
            <div class="card-header">
                <h2>Se encontro {{ $numExcepciones }} excepciones</h2>
            </div>

            <table class="table table-striped table-bordered table-hover">
                <thead class="table-danger">
                    <tr> 
                    <th>N°</th>
                    <th>Tabla</th>
                    <th>Clave Foranea</th>
                    <th>Tabla Referenciada</th>
                    <th>Excepcion</th>
                </tr> 
                </thead>
                <tbody>
                    @php $index = 1; @endphp
                    @foreach ($listExceptions as $exceptionKey => $exceptionValue)
                    <tr> 
                        <td> {{$index}}</td>
                        <td>{{ $tableNameSelect }}</td>
                        <td>{{ $exceptionKey }}</td>
                        <td>{{ $tableRefNameSelect}}</td>
                        <td>{{ $exceptionValue }}</td>
                    </tr> 
                    @php $index++; @endphp
                    @endforeach
                </tbody>
            </table>
        </div>
        <a href="{{route('integridadtablas.cancelar')}}">
            <button type="button" class="btn btn-warning">Atras</button>
        </a>
    </div>

@endsection
