@extends('layout.layout')

@section('title', 'Formulario de Conexión')

@section('content')
<div class="container mt-5">
    <div class="row mt-4">
        <div class="col-md-12 text-right">
            <a href="{{ route('show.tables') }}" class="btn btn-secondary ml-2">
                <i class="fa fa-arrow-left"></i>
            </a>
            <a href="{{ route('disconnect.database') }}" class="btn btn-danger">
                <i class="fa fa-power-off"></i>
            </a>
        </div>
    </div>
    <h1 class="mb-4">Tabla: {{ $tableName }}</h1>

    @if(count($columns) > 0)
    <div class="table-responsive">
        <table class="table table-striped">
            <thead>
                <tr>
                    @foreach($columns as $column)
                    <th>{{ $column["name"]}}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @foreach($tableData as $row)
                <tr>
                    @foreach($columns as $column)
                    <td>{{ $row->{$column["name"]} }}</td>
                    @endforeach
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @else
    <div class="alert alert-info" role="alert">
        No hay datos en la tabla {{ $tableName }}
    </div>
    @endif
</div>
@endsection
