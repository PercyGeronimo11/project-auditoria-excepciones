@extends('layout.layout')

@section('title', 'Secuencialidad')

@section('content')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://kit.fontawesome.com/7920972db5.js" crossorigin="anonymous"></script>
<style>
    .table {
        margin: auto; /* Centra horizontalmente la tabla */
    }

    th, td {
        text-align: center; /* Centra el contenido de las celdas */
    }
</style>
<div class="container mt-5">



    <div class="container mt-3">
        <div class="row justify-content-between">
            <div class="col-md-6 mb-4">
                <div class="input-group">
                    <input  type="text" class="form-control me-2" id="searchInput" placeholder="Buscar...">
                    <div class="input-group-append">
                        <button type="button"  id="searchButton" class="btn btn-secondary" onclick="buscar()">
                            <i class="fa fa-search"></i> Buscar
                        </button>
                    </div>
                </div>
            </div>
            <div class="col-md-6 mb-4">
                <a href="./excepcion/create" method="GET">
                    <button class="btn btn-success">
                        <i class="fa  fa-plus-square"></i>
                    </button>
                </a>
            </div>
        </div>
    </div>





    <div class="card mt-4">
        <div class="card-header bg-light">
            <h4 class="text-muted">Lista de excepciones de tablas</h4>
        </div>
        <div class="card-body">

            @if (count($sequence_results)==0)
                <div class="alert alert-success" role="alert">
                    No hay resultados
                </div>
            @else
                <div role="alert">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead class="thead-pastel-yellow">
                            <tr>
                                <th scope="col">N°</th>
                                <th scope="col">Gestor</th>
                                <th scope="col">Base de datos</th>
                                <th scope="col">Tabla</th>
                                <th scope="col">Campo</th>
                                <th scope="col">Fecha y hora</th>
                                <th scope="col">Opciones</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($sequence_results as $item)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $item['bdManager'] }}</td>
                                    <td>{{ $item['dbName'] }}</td>
                                    <td>{{ $item['tableName'] }}</td>
                                    <td>{{ $item['field'] }}</td>
                                    <td>{{ $item['created_at'] }}</td>
                                    <td>
                                        <div style="display: flex; justify-content: flex-end; width: 100%;">
                                            <a class="nav-link" href="./excepcion/delete/{{ $item['id'] }}"><i class="fa  fa-minus-square"></i></a>
                                            <a class="nav-link" href="./excepcion/secuencialidad/pdf/{{ $item['id'] }}"><i class="fa fa-file-pdf"></i></a>
                                            <a class="nav-link" href="./excepcion/secuencialidad/use/{{ $item['id'] }}"><i class="fa fa-edit"></i></a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const searchInput = document.getElementById('searchInput');
        const searchButton = document.getElementById('searchButton');
        
        searchButton.addEventListener('click', function() {
            filterTable();
        });

        function filterTable() {
            const searchTerm = searchInput.value.toLowerCase();
            const tableRows = document.querySelectorAll('.table tbody tr');

            tableRows.forEach(row => {
                const rowText = row.textContent.toLowerCase();
                if (rowText.includes(searchTerm)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        }
    });
</script>

<style>
    .thead-pastel-yellow {
        background-color: #fff7bd;
    }
</style>
@endsection
