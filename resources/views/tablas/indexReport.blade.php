@extends('layout.layout')

@section('title', 'Formulario de Conexión')

@section('content')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://kit.fontawesome.com/7920972db5.js" crossorigin="anonymous"></script>

    <div class="container mt-5">
        <h1 class="mb-4">INTEGRIDAD DE TABLAS: EXCEPCIONES ENCONTRADAS</h1>

        <div class="card">
            <table>
                <thead>
                    <th>N</th>
                    <th>Tabla</th>
                    <th>Clave Foranea</th>
                    <th>Tabla Referenciada</th>
                    <th>Excepcion</th>
                </thead>
                <tbody>
                    <td>
                        {{ $numExcepciones }}</td>
                        <td>ventas detalle</td>
                        <td>ventas_id</td>
                        <td> Ventas</td>
                        <td>Error 1</td>
                </tbody>

            </table>

        </div>
    </div>

@endsection
