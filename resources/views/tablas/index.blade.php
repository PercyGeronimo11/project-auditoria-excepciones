@extends('layout.layout')

@section('title', 'Formulario de Conexión')

@section('content')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://kit.fontawesome.com/7920972db5.js" crossorigin="anonymous"></script>

    <div class="container mt-5">
        <h1 class="mb-4">LISTA DE INTEGRIDAD DE TABLAS</h1>

        <a href="{{ route('integridadtablas.create') }}">
            <div class="btn btn-warning">
                Nuevo
            </div>
        </a>

        <table>
            <thead>
                <tr>
                    <td>N°</td>
                    <td>Tabla</td>
                    <td>Clave Foranea</td>
                    <td>Tabla Referenciada</td>
                    <td>Clave Primaria</td>
                    <td>Opciones</td>
                </tr>
            </thead>
            <tbody>
                @if ($integridades->count() > 0)
                    @php $index=0; @endphp
                    @foreach ($integridades as $integridad)
                        <tr>
                            <td>{{ $index }}</td>
                            <td>{{ $integridad->table }}</td>
                            <td>{{ $integridad->column_foreignkey }}</td>
                            <td>{{ $integridad->table_refer }}</td>
                            <td>{{ $integridad->column_primarykey }}</td>
                            <td>{{ $integridad->fecha }}</td>
                        </tr>
                        @php $index++; @endphp
                    @endforeach
                @else
                <div>No hay Información</div>
                @endif
            </tbody>
        </table>
    </div>

    <script>
    @endsection
