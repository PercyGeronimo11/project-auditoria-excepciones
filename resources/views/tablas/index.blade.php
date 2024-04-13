@extends('layout.layout')

@section('title', 'Formulario de Conexión')

@section('content')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://kit.fontawesome.com/7920972db5.js" crossorigin="anonymous"></script>

    <div class="container mt-5">
        <h1 class="mb-4">LISTA DE INTEGRIDAD DE TABLAS</h1>
        
        <a href="{{route('integridadtablas.create')}}">
            <div class="btn btn-warning">
                Nuevo
            </div>
        </a>
        
    </div>

    <script>
        
@endsection
