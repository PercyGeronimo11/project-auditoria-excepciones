@extends('layout.layout')

@section('title', 'Formulario de Conexión')

@section('content')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://kit.fontawesome.com/7920972db5.js" crossorigin="anonymous"></script>

    <div class="container mt-5">
        <h1 class="mb-4">LISTA DE INTEGRIDAD DE TABLAS</h1>

        <a href="{{ route('integridadtablas.create') }}" method="GET">
            <div class="btn btn-primary">
                Nuevo
            </div>
        </a>
        @if (session('warning'))
            <div class="alert alert-danger">
                {{ session('warning') }}
            </div>
        @endif
        @if (session('success'))
            <div class="alert alert-danger">
                {{ session('success') }}
            </div>
        @endif
        <table class="table table-striped">
            <thead class="thead-dark">
                <tr>
                    <th scope="col">N°</th>
                    <th scope="col">Tabla</th>
                    <th scope="col">Clave Foránea</th>
                    <th scope="col">Tabla Referenciada</th>
                    <th scope="col">Clave Primaria</th>
                    <th scope="col">Fecha</th>
                    <th scope="col">Opciones</th>
                </tr>
            </thead>
            <tbody>
                @if (count($integridades) > 0)
                    @php $index=1; @endphp
                    @foreach ($integridades as $integridad)
                        <tr>
                            <th scope="row">{{ $index }}</th>
                            <td>{{ $integridad->table }}</td>
                            <td>{{ $integridad->column_foreignkey }}</td>
                            <td>{{ $integridad->table_refer }}</td>
                            <td>{{ $integridad->column_primarykey }}</td>
                            <td>{{ date('d-m-Y', strtotime($integridad['created_at'])) }}</td>
                            <td>
                                <a href="{{ route('integridadtablas.analysis', $integridad->id) }}" class="btn btn-warning">
                                    Analizar
                                </a>
                                <a href="{{ route('integridadtablas.delete', $integridad->id) }}" class="btn btn-danger">
                                    Borrar
                                </a>
                            </td>
                        </tr>
                        @php $index++; @endphp
                    @endforeach
                @else
                    <tr>
                        <td colspan="7">No hay información</td>
                    </tr>
                @endif
            </tbody>
        </table>

    </div>

    <script>
        setTimeout(function() {
            $('#alert').fadeOut('slow');
        }, 5000); // 5000 milisegundos = 5 segundos
    </script>

@endsection
