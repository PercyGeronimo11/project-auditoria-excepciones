@extends('layout.layout')

@section('title', 'Estructura de Tabla - ' . $tableName)

@section('content')
<div class="container mt-5">


    <div class="card" style="background-color: #f8f9fa;">
        <div class="card-body">
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
            <div style="background-color: #ffffff; padding: 10px; margin-bottom: 20px;">
                <h2 class="text-center mb-4" style="font-size: 32px; color: #6c757d;">Estructura de la tabla: {{ $tableName }}</h2>
            </div>

            <div class="row">
                <div class="col-md-6 mb-4">
                    <input type="text" id="searchInput" class="form-control" placeholder="Buscar tabla...">
                </div>
                <div class="col-md-6 mb-4">
                    <button class="btn btn-secondary" type="button" onclick="searchField()">
                        <i class="fa fa-search"></i> Buscar
                    </button>
                </div>
            </div>

            <ul class="list-group">
                @foreach($columns as $column)
                    <li class="list-group-item" id="{{ $driver == 'sqlsrv' ? $column->COLUMN_NAME : $column->Field }}">
                        <div style="font-size: 18px; color: #000; font-weight: bold;">{{ $driver == 'sqlsrv' ? $column->COLUMN_NAME : $column->Field }}</div>
                        <div style="font-size: 16px;">
                            @if(in_array($driver == 'sqlsrv' ? $column->COLUMN_NAME : $column->Field, $primaryKey))
                                <span class="badge badge-success">Primary Key</span>
                            @endif
                            @foreach ($foreignKeys as $foreignKey)
                                @if ($foreignKey->COLUMN_NAME == ($driver == 'sqlsrv' ? $column->COLUMN_NAME : $column->Field))
                                    @if ($driver == 'mysql')
                                        <span class="badge badge-secondary">Foreign Key: {{ $foreignKey->REFERENCED_TABLE_NAME }}</span>
                                    @else
                                        <span class="badge badge-secondary">Foreign Key: {{ $foreignKey->TABLE_NAME }}</span>
                                    @endif
                                @endif
                            @endforeach
                            <span class="badge badge-info">{{ $columnTypes[$driver == 'sqlsrv' ? $column->COLUMN_NAME : $column->Field] }}</span>
                        </div>
                    </li>
                @endforeach
            </ul>

            <!-- Mensaje para cuando no se encuentra el campo -->
            <div id="notFoundMessage" class="alert alert-warning" style="display: none;">
                No existe el campo <span id="searchedField"></span>
            </div>
         
        </div>
    </div>
</div>

<script>
    // Función para buscar campos
    function searchField() {
        var input, filter, ul, li, a, i, txtValue;
        input = document.getElementById('searchInput');
        filter = input.value.toUpperCase();
        ul = document.querySelector('.list-group');
        li = ul.getElementsByTagName('li');
        var found = false;

        for (i = 0; i < li.length; i++) {
            a = li[i].getElementsByTagName('div')[0];
            txtValue = a.textContent || a.innerText;
            if (txtValue.toUpperCase().indexOf(filter) > -1) {
                li[i].style.display = '';
                found = true;
            } else {
                li[i].style.display = 'none';
            }
        }

        // Mostrar el mensaje si no se encontró el campo
        if (!found) {
            var notFoundMessage = document.getElementById('notFoundMessage');
            notFoundMessage.style.display = 'block';
            document.getElementById('searchedField').innerText = "'" + input.value + "'";
        } else {
            document.getElementById('notFoundMessage').style.display = 'none';
        }
    }

    window.onload = function() {
        var ul, li, i;
        ul = document.querySelector('.list-group');
        li = ul.getElementsByTagName('li');
        for (i = 0; i < li.length; i++) {
            li[i].style.display = '';
        }
    };
</script>

@endsection
