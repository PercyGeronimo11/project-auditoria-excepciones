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

    <div class="my-4">
        <h1 style="font-size: 24px; font-weight: bold; color: #333333;">Tabla: {{ $tableName }}</h1>
    </div>

    <div class="row mb-4">
        <div class="col-md-6">
            <div class="input-group">
                <select class="form-control" id="searchColumn">
                    <option value="">Seleccionar columna</option>
                    @foreach($columns as $index => $column)
                    <option value="{{ $index }}">{{ $column->Field }}</option>
                    @endforeach
                </select>
                <input type="text" class="form-control" id="searchInput" placeholder="Buscar">
                <div class="input-group-append">
                    <button class="btn btn-secondary" type="button" onclick="searchRows()">
                        <i class="fa fa-search"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <strong class="card-title">Data Table</strong>
                </div>
                <div class="card-body">
                    @if(count($tableData) > 0)
                    <table id="bootstrap-data-table" class="table table-striped table-bordered">
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
                    @else
                    <div class="alert alert-info" role="alert">
                        No hay datos en la tabla {{ $tableName }}
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function searchRows() {
        var searchText = document.getElementById('searchInput').value.toLowerCase();
        var searchColumn = document.getElementById('searchColumn').value;
        var table = document.getElementById('bootstrap-data-table');
        var rows = table.getElementsByTagName('tr');

        for (var i = 1; i < rows.length; i++) { // Comenzar desde 1 para omitir la fila de encabezado
            var cell = rows[i].getElementsByTagName('td')[searchColumn]; // Obtener la celda en la columna seleccionada
            if (cell) {
                var rowData = cell.textContent.toLowerCase();
                if (rowData.includes(searchText)) {
                    rows[i].style.display = '';
                } else {
                    rows[i].style.display = 'none';
                }
            }
        }
    }
</script>
@endsection
