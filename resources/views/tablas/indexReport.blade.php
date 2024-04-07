@extends('layout.layout')

@section('title', 'Formulario de Conexión')

@section('content')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://kit.fontawesome.com/7920972db5.js" crossorigin="anonymous"></script>

    <div class="container mt-5">
        <h2 class="mb-4"> <b>  INTEGRIDAD DE TABLAS: EXCEPCIONES ENCONTRADAS </b></h2>

        <div class="card">
            <div class="card-header">
                <h2>Se encontro {{ $numExcepciones }} excepciones</h2>
            </div>

            <table>
                <thead>
                    <th>N°</th>
                    <th>Clave Foranea</th>
                    <th>Tabla Referenciada</th>
                    <th>Excepcion</th>
                </thead>
                <tbody>
                    @foreach ($listExceptions as $exceptionKey => $exceptionValue)
                        <td> 1</td>
                        <td>{{ $exceptionKey }}</td>
                        <td> Ventas</td>
                        <td>{{ $exceptionValue }}</td>
                    @endforeach
                </tbody>

            </table>


        </div>
    </div>

@endsection
