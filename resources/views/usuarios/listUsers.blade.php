@extends('layout.layout')

@section('title', 'Usuarios')

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
                    <input  type="text" class="form-control" id="searchInput" placeholder="Buscar usuario por nombre">
                    <div class="input-group-append">
                            <button class="btn btn-secondary" type="button" onclick="searchUsers()">
                            <i class="fa fa-search"></i> Buscar
                        </button>
                    </div>
                </div>
            </div>
            <div class="col-md-6 mb-4">
                <a href="./users/create" method="GET">
                    <button class="btn btn-success">
                        <i class="fa  fa-plus-square"></i>
                    </button>
                </a>
            </div>
        </div>
    </div>






    <div class="card mt-4">
        <div class="card-header bg-light">
            <h4 class="text-muted">Lista de Usuarios</h4>
        </div>
        <div class="card-body">
            @if(Session::has('success1'))
                <div class="alert alert-success">
                    {{ Session::get('success1') }}
                </div>
            @endif
            @if (count($users)==0)
                <div class="alert alert-success" role="alert">
                    No hay usuarios
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-bordered table-hover" id="userTable">
                        <thead class="thead-pastel-yellow">
                        <tr>
                            <th scope="col">N°</th>
                            <th scope="col">Nombre</th>
                            <th scope="col">Nombre de usuario</th>
                            <th scope="col">Fecha de creación</th>
                            <th scope="col">Opciones</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($users as $item)
                            <tr>
                                <td>{{ $item-> id }}</td>
                                <td>{{ $item-> name }}</td>
                                <td>{{ $item->userName }}</td>
                                <td>{{ $item->created_at }}</td>
                                <td>
                                    <div style="display: flex; justify-content: flex-end; width: 100%;">

                                    <a class="nav-link" href="./user/delete/{{ $item['id'] }}"><i class="fa  fa-minus-square"></i></a>
                                    <a class="nav-link" href="./user/edit/{{ $item['id'] }}"><i class="fa fa-edit"></i></a>
                                </div>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</div>
<script>
    function searchUsers() {
        var input, filter, table, tr, td, i, txtValue;
        input = document.getElementById("searchInput");
        filter = input.value.toUpperCase();
        table = document.getElementById("userTable");
        tr = table.getElementsByTagName("tr");
        for (i = 0; i < tr.length; i++) {
            td = tr[i].getElementsByTagName("td")[1]; // Cambia el número si necesitas buscar en otra columna
            if (td) {
                txtValue = td.textContent || td.innerText;
                if (txtValue.toUpperCase().indexOf(filter) > -1) {
                    tr[i].style.display = "";
                } else {
                    tr[i].style.display = "none";
                }
            }
        }
    }
</script>
<style>
    .thead-pastel-yellow {
        background-color: #fff7bd;
    }
</style>
@endsection
