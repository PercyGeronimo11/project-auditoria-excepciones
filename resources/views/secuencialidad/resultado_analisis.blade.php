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
                <ul>
                    @foreach ($resultado as $excepcion)
                        <li>{{ $excepcion }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
    </div>
@endsection
