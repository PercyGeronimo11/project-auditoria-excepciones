@extends('layout.layout_inhabil')

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

           @if(session('success'))
           <div class="sufee-alert alert with-close alert-primary alert-dismissible fade show">
            <span class="badge badge-pill badge-primary">Success</span>
            {{ session('success') }}
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
                                @if($databases->isEmpty())
                                <tr>
                                    <td colspan="6" class="text-center">No hay bases de datos registradas.</td>
                                </tr>
                                @else
                                @php $id = 1; 
                                @endphp 
                                @foreach($databases as $database)
                                <tr>
                                    <td>{{ $id++ }}</td>
                                    <td>{{ $database->tipo }}</td>
                                    <td>{{ $database->nombre_db }}</td>
                                    <td>{{ $database->host }}</td>
                                    <td>{{ $database->usuario }}</td>
                                    <td class="text-center">
                                        <div style="display: flex; justify-content: flex-end; width: 100%;">
                                            <a id="logoutLink" class="nav-link" onclick="actualizarFila('{{ $database->id }}','{{ $database->tipo }}', '{{ $database->nombre_db }}', '{{ $database->host }}', '{{ $database->usuario }}')"><i class="fa fa-sign-in"></i></a>
                                            <a id="deleteLink" class="nav-link" onclick="eliminarRegistro('{{ $database->id }}', '{{ $database->nombre_db }}')"><i class="fa fa-minus-circle"></i></a>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                                @endif
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
    function actualizarFila(id,tipo, database, host, usuario, password) {
        // Establecer los valores en el formulario
        document.getElementById('driver').value = tipo;
        document.getElementById('database').value = database;
        document.getElementById('host').value = host;
        document.getElementById('username').value = usuario;
    }

        // Función para eliminar una fila con confirmación
        function eliminarRegistro(id, nombre) {
        if (confirm('¿Estás seguro de que deseas eliminar el registro "' + nombre + '"?')) {
            // Si el usuario confirma, enviar una solicitud al servidor para eliminar el registro
            window.location.href = '/eliminar-registro/' + id; // Reemplaza '/eliminar-registro/' con la ruta adecuada en tu aplicación
        }
    }
</script>
