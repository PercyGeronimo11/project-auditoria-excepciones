@extends('layout.layout')

@section('title', 'Formulario de Conexión')

@section('content')

<div class="container mt-5">
    <h1 class="mb-4">Tabla: {{ $tableName }}</h1>

    @if(count($tableData) > 0)
    <div class="table-responsive">
        <table class="table table-striped">
            <thead>
                <tr>
                    @foreach($columns as $column)
                    <th>{{ $column->Field }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @foreach($tableData as $row)
                <tr>
                    @foreach($columns as $column)
                    <td>{{ $row->{$column->Field} }}</td>
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
