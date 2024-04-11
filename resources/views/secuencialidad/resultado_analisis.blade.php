@extends('layout.layout')

@section('title', 'Resultados secuencialidad')

@section('content')
    <div class="container mt-5">
        <h1 class="text-center mb-4">Resultado del Análisis de Secuencialidad</h1>
        @if (is_string($resultado))
            <div class="alert alert-success" role="alert">
                {{ $resultado }}
            </div>
        @else
            <div class="alert alert-danger" role="alert">
                <h2>Excepciones de Secuencialidad Detectadas:</h2>
                <br>
                <table class="table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Tabla</th>
                            <th>Mensaje</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($resultado as $excepcion)
                            @if (isset($excepcion['error']))
                                <tr>
                                    <td colspan="3">{{ $excepcion['error'] }}</td>
                                </tr>
                            @else
                                <tr>
                                    <td>{{ $excepcion['id'] }}</td>
                                    <td>{{ $excepcion['tabla'] }}</td>
                                    <td>{{ $excepcion['mensaje'] }}</td>
                                </tr>
                            @endif
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
@endsection
