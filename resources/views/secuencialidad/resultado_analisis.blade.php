@extends('layout.layout')

@section('title', 'Resultados secuencialidad')

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
        <h1 class="text-center mb-4">Resultado del Análisis de Secuencialidad</h1>
        @if (is_string($resultado))
            <div class="alert alert-success" role="alert">
                {{ $resultado }}
            </div>
        @else
            @if (isset($resultado[0]['error']))
                <h5 class="alert alert-danger text-center">{{ $resultado[0]['error'] }}</h5>
            @else
                <div class="alert alert-danger" role="alert">
                    <h2>Excepciones de Secuencialidad Detectadas:</h2>
                    <br>
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th scope="col">ID</th>
                                <th scope="col">Tabla</th>
                                <th scope="col">Campo</th>
                                <th scope="col">Valor Anterior</th>
                                <th scope="col">Valor Actual</th>
                                <th scope="col">Excepción</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($resultado as $excepcion)
                                <tr>
                                    <td>{{ $excepcion['id'] }}</td>
                                    <td>{{ $excepcion['tabla'] }}</td>
                                    <td>{{ $excepcion['campo'] }}</td>
                                    <td>{{ $excepcion['anterior'] }}</td>
                                    <td>{{ $excepcion['actual'] }}</td>
                                    <td>{{ $excepcion['mensaje'] }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        @endif
        <a href="{{route('secuencialidad.index')}}" class="btn btn-primary">Finalizar</a>
    </div>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.6.3.min.js"></script> 
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
@endsection
