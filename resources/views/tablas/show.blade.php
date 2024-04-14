@extends('layout.layout')

@section('title', 'Formulario de Conexión')

@section('content')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://kit.fontawesome.com/7920972db5.js" crossorigin="anonymous"></script>

    <div class="container mt-5">
        <h2 class="mb-4"> <b> INTEGRIDAD DE TABLAS: EXCEPCIONES ENCONTRADAS </b></h2>
        <a href="{{ route('integridadtablas.cancelar') }}">
            <button type="button" class="btn btn-warning">
                <=< /button>
        </a>
        <form action="" method="POST">
            @csrf
            <input type="hidden" name="listExceptions" value="{{ json_encode($listExceptions) }}">
            <button type="submit" class="btn btn-primary">Guardar Excepciones</button>
        </form>

        <div class="card">
            <div class="card-header">
                <h2>Se encontro {{ $numExcepciones }} excepciones</h2>
            </div>

            <table class="table table-striped table-danger">
                <thead style="background-color: red; color: white;">
                    <tr>
                        <th scope="col">N°</th>
                        <th scope="col">Tabla</th>
                        <th scope="col">Clave Foranea</th>
                        <th scope="col">Tabla Referenciada</th>
                        <th scope="col">Excepcion</th>
                    </tr>
                </thead>
                <tbody>
                    @if (count($listExceptions) > 0)
                        @php $index = 1; @endphp
                        @foreach ($listExceptions as $exceptionKey => $exceptionValue)
                            <tr>
                                <th scope="row">{{ $index }}</th>
                                <td>{{ $tableNameSelect }}</td>
                                <td>{{ $exceptionKey }}</td>
                                <td>{{ $tableRefNameSelect }}</td>
                                <td>{{ $exceptionValue }}</td>
                            </tr>
                            @php $index++; @endphp
                        @endforeach
                    @else
                        <tr>
                            <td colspan="7"> No Se encontraron excepciones</td>
                        </tr>

                    @endif
                </tbody>
            </table>

        </div>

    </div>

@endsection
