@extends('layout.layout')

@section('title', 'Formulario de Conexión')

@section('content')
<div class="container mt-5">
    <div class="row justify-content-end">
        <div class="col-md-4 text-right">
            <a href="{{ route('show.tables') }}" class="btn btn-secondary">
                <i class="fa fa-arrow-left mr-2"></i> Volver
            </a>
            <a href="{{ route('disconnect.database') }}" class="btn btn-danger">
                <i class="fa fa-power-off mr-2"></i> Desconectar
            </a>
        </div>
    </div>
    <div class="my-4">
        <h1 style="font-size: 24px; font-weight: bold; color: #333333;">Tabla: {{ $tableName }}</h1>
    </div>
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="input-group">
                <select class="custom-select" id="searchColumn">
                    <option value="">Seleccionar columna</option>
                    @foreach($columns as $index => $column)
                    <option value="{{ $index }}">{{ $column->COLUMN_NAME }}</option>
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
                <div class="table-responsive">
                    <table class="table table-bordered table-striped" style="background-color: #f8f9fa; font-size: 12px;"> <!-- Agregué el tamaño de la fuente y algunos estilos adicionales -->
                        <thead style="background-color: #FFFF99;">

                <tr>
                    @foreach($columns as $column)
                    <th>{{ $column->COLUMN_NAME }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @foreach($tableData as $row)
                <tr style="background-color: white;"> 
                        @foreach($columns as $column)
                    <td>{{ $row->{$column->COLUMN_NAME} }}</td>
                    @endforeach
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    @if(count($tableData) === 0)
    <div class="alert alert-info mt-3" role="alert">
        No hay datos en la tabla {{ $tableName }}
    </div>
    @endif
</div>
</div>
</div>
</div>

<script>
    function searchRows() {
        var searchText = document.getElementById('searchInput').value.toLowerCase();
        var searchColumn = document.getElementById('searchColumn').value;
        var table = document.getElementsByTagName('table')[0];
        var rows = table.getElementsByTagName('tr');

        for (var i = 1; i < rows.length; i++) {
            var cell = rows[i].getElementsByTagName('td')[searchColumn];
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
