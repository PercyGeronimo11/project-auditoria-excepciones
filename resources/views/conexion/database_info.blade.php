@extends('layout.layout')

@section('title', 'Formulario de Conexión')

@section('content')
<div class="container mt-5">
    <div class="row">
        <div class="col-md-6 mb-4">
            <input type="text" id="searchInput" class="form-control" placeholder="Buscar tabla...">
        </div>
        <div class="col-md-6 mb-4">
            <button class="btn btn-secondary" type="button" onclick="filterTables()">
                <i class="fa fa-search"></i> Buscar
            </button>
        </div>
    </div>

    <!-- Div para mostrar el mensaje de tabla no encontrada -->
    <div id="notFoundMessage" class="row" style="display: none;">
        <div class="col-md-12">
            <div class="alert alert-warning" role="alert">
                No se existe la tabla '<span id="searchedTableName"></span>'.
            </div>
        </div>
    </div>

    <div class="row">
        @foreach($tablesData as $tableName => $table)
        <div class="col-md-4 mb-4 tableDiv">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">{{ $tableName }}</h5>
                    <form action="
                        @if($driver == 'mysql')
                            {{ route('show.tableMysql', ['tableName' => $tableName]) }}
                        @elseif($driver == 'sqlsrv')
                            {{ route('show.tableSQL', ['tableName' => $tableName]) }}
                        @endif
                        " method="GET">
                        <button type="submit" class="btn btn-primary">
                            <i class="fa fa-eye"></i> Ver
                        </button>
                        <a href="{{ route('table.structure', ['tableName' => $tableName]) }}" class="btn btn-info">
                            <i class="fa fa-table"></i> Estructura
                        </a>
                    </form>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
<script>
    function filterTables() {
        var input, filter, divs, cardTitles, i, txtValue;
        input = document.getElementById('searchInput');
        filter = input.value.toUpperCase();
        divs = document.getElementsByClassName('tableDiv');
        var notFoundMessage = document.getElementById('notFoundMessage');

        var found = false; // Variable para verificar si se encontró la tabla

        for (i = 0; i < divs.length; i++) {
            cardTitles = divs[i].getElementsByTagName("h5")[0];
            txtValue = cardTitles.textContent || cardTitles.innerText;
            if (txtValue.toUpperCase().indexOf(filter) > -1) {
                divs[i].style.display = "";
                found = true; // Se encontró la tabla
            } else {
                divs[i].style.display = "none";
            }
        }

        // Mostrar el mensaje si no se encontró la tabla
        if (!found) {
            notFoundMessage.style.display = "block";
            document.getElementById('searchedTableName').innerText = input.value;
        } else {
            notFoundMessage.style.display = "none";
        }
    }
</script>
@endsection
