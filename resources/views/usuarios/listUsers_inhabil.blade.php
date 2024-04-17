@extends('layout.layout_inhabil')

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
        <h1 class="text-center mb-4">Lista de usuarios</h1>
        <div>
            <a class="btn btn-primary" href="./users/create">Nuevo</a>
        </div><br>
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
            <div role="alert">
                <table class="table table-striped">
                    <thead>
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
                                    <a class="btn btn-warning" href="./user/edit/{{ $item['id'] }}">Editar</a>
                                    <a class="btn btn-danger" href="./user/delete/{{ $item['id'] }}">Eliminar</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script>
@endsection
