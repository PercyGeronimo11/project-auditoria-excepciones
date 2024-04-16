@extends('layout.layout')

@section('title', 'Secuencialidad')

@section('content')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<style>
    .table {
        margin: auto; /* Centra horizontalmente la tabla */
    }

    th, td {
        text-align: center; /* Centra el contenido de las celdas */
    }
</style>
    <div class="container mt-5">
        <h1 class="text-center mb-4">Análisis de Secuencialidad</h1>
        <div>
            <a class="btn btn-primary" href="./excepcion/create">Nuevo</a>
        </div><br>
        
        @if (count($sequence_results)==0)
            <div class="alert alert-success" role="alert">
                No hay resultados
            </div>
        @else
            <div role="alert">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th scope="col">N°</th>
                            <th scope="col">Gestor</th>
                            <th scope="col">Base de datos</th>
                            <th scope="col">Tabla</th>
                            <th scope="col">Campo</th>
                            <th scope="col">Fecha y hora</th>
                            <th scope="col">Opciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($sequence_results as $item)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $item['bdManager'] }}</td>
                                <td>{{ $item['dbName'] }}</td>
                                <td>{{ $item['tableName'] }}</td>
                                <td>{{ $item['field'] }}</td>
                                <td>{{ $item['created_at'] }}</td>
                                <td>
                                    <a class="btn btn-danger" href="./excepcion/delete/{{ $item['id'] }}">Eliminar</a>
                                    <a class="btn btn-warning" href="./excepcion/secuencialidad/pdf/{{ $item['id'] }}">PDF</a>
                                    <a class="btn btn-success" href="./excepcion/secuencialidad/use/{{ $item['id'] }}">Analizar</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.6.3.min.js"></script> 
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
@endsection
