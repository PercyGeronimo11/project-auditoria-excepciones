@extends('layout.layout')

@section('title', 'Formulario de Conexión')

@section('content')


<style>
    body {
        background-color: #EAEBF0;
    }
</style>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            @if(session('error'))
            <div class="sufee-alert alert with-close alert-danger alert-dismissible fade show">
                <span class="badge badge-pill badge-danger">Success</span>
                {{ session('error') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>         
        @endif
            <div class="card">
                <div class="card-header">
                    <strong class="card-title">Formulario de Conexión</strong>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('connect.database') }}">
                        @csrf
                        <div class="mb-3">
                            <label for="driver" class="form-label">Driver:</label>
                            <select name="driver" id="driver" class="form-control-sm form-control" required>
                                <option value="mysql">MySQL</option>
                                <option value="sqlsrv">SQL Server</option>
                            </select>
                        </div>        
                        <div class="mb-3">
                            <label class=" form-control-label">Host:</label>
                            <div class="input-group">
                                <div class="input-group-addon"><i class="fa fa-rss-square"></i></div>
                                <input type="text" name="host" id="host" class="form-control" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class=" form-control-label">Database</label>
                            <div class="input-group">
                                <div class="input-group-addon"><i class="fa fa-archive"></i></div>
                                <input type="text" name="database" id="database" class="form-control" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class=" form-control-label">Username:</label>
                            <div class="input-group">
                                <div class="input-group-addon"><i class="fa fa-user"></i></div>
                                <input type="text" name="username" id="username" class="form-control" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class=" form-control-label">Password:</label>
                            <div class="input-group">
                                <div class="input-group-addon"><i class="fa  fa-asterisk"></i></div>
                                <input type="password" name="password" id="password" class="form-control">
                            </div>
                        </div>
                        <button id="payment-button" type="submit" class="btn btn-lg btn-info btn-block">
                            <i class="fa  fa-link"></i>&nbsp;
                            <span id="payment-button-amount">Conectar</span>
                            <span id="payment-button-sending" style="display:none;">Sending…</span>
                        </button>
                    </form>
                </div>
                </div>
            </div> <!-- .card -->
        </div><!--/.col-->
        <div class="row justify-content-center">
            <div class="col-lg-8"> <!-- Ajusta el ancho del div -->
                <div class="card">
                    <div class="card-header">
                        <strong class="card-title">Lista de bases de datos</strong>
                    </div>
                    <div class="table-stats order-table ov-h">
                        <table class="table ">
                            <thead>
                                <tr>
                                    <th scope="col">ID</th>
                                    <th scope="col">Tipo</th>
                                    <th scope="col">Nombre</th>
                                    <th scope="col">Host</th>
                                    <th scope="col">Usuario</th>
                                    <th scope="col">Acción</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $id = 1; @endphp 
                                @foreach($databases as $database)
                                <tr>
                                    <td>{{ $id++ }}</td>
                                    <td>{{ $database->tipo }}</td>
                                    <td>{{ $database->nombre_db }}</td>
                                    <td>{{ $database->host }}</td>
                                    <td>{{ $database->usuario }}</td>
                                    <td class="text-center">
                                        <a id="logoutLink" class="nav-link" onclick="actualizarFila('{{ $database->id }}', '{{ $database->nombre_db }}', '{{ $database->host }}', '{{ $database->usuario }}')"><i class="fa  fa-sign-in"></i></a>

                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
       
</div>
@endsection


<script>
    // Esta función se ejecutará cuando se presione el botón "Actualizar" en una fila
    function actualizarFila(id, database, host, usuario, password) {
        // Establecer los valores en el formulario
        document.getElementById('database').value = database;
        document.getElementById('host').value = host;
        document.getElementById('username').value = usuario;
    }
</script>
