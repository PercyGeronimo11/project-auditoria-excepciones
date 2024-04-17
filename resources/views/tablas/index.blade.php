@extends('layout.layout')

@section('title', 'Formulario de Conexión')

@section('content')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://kit.fontawesome.com/7920972db5.js" crossorigin="anonymous"></script>

    <!-- Agrega este código en el lugar donde desees que aparezca el input y el botón de búsqueda -->
    <div class="container mt-3">
        <div class="row justify-content-between">
            <div class="col-md-6 mb-4">
                <div class="input-group">
                    <input type="text" class="form-control" id="inputBusqueda" onkeyup="buscar()" placeholder="Buscar por tabla referenciada...">
                    <div class="input-group-append">
                        <button type="button" class="btn btn-secondary" onclick="buscar()">
                            <i class="fa fa-search"></i> Buscar
                        </button>
                    </div>
                </div>
            </div>
            <div class="col-md-6 mb-4">
                <a href="{{ route('integridadtablas.create') }}" method="GET">
                    <button class="btn btn-success">
                        <i class="fa  fa-plus-square"></i>
                    </button>
                </a>
            </div>
        </div>
    </div>

    <div class="container mt-5">
        <div class="card mt-4">
            <div class="card-header bg-light">
                <h4 class="text-muted">Lista de excepciones de tablas</h4>
            </div>
            <div class="card-body">
                @if (session('warning'))
                <div class="sufee-alert alert with-close alert-danger alert-dismissible fade show">
                    <span class="badge badge-pill badge-danger">Success</span>
                       {{ session('warning') }}
                       <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>  
                @endif
                @if (session('success'))
                <div class="sufee-alert alert with-close alert-primary alert-dismissible fade show">
                    <span class="badge badge-pill badge-primary">Success</span>
                        {{ session('success') }}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>  
                @endif
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="thead-pastel-yellow">
                            <tr>
                                <th scope="col">N°</th>
                                <th scope="col">Tabla</th>
                                <th scope="col">Clave Foránea</th>
                                <th scope="col">Tabla Referenciada</th>
                                <th scope="col">Clave Primaria</th>
                                <th scope="col">Fecha</th>
                                <th scope="col">Opciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if (count($integridades) > 0)
                                @php $index=1; @endphp
                                @foreach ($integridades as $integridad)
                                    <tr>
                                        <th scope="row">{{ $index }}</th>
                                        <td>{{ $integridad->table }}</td>
                                        <td>{{ $integridad->column_foreignkey }}</td>
                                        <td>{{ $integridad->table_refer }}</td>
                                        <td>{{ $integridad->column_primarykey }}</td>
                                        <td>{{ date('d-m-Y', strtotime($integridad['created_at'])) }}</td>
                                        <td>
                                            <div style="display: flex; justify-content: flex-end; width: 100%;">
                                                <a href="{{ route('integridadtablas.analysis', $integridad->id) }}" class="nav-link"><i class="fa fa-check-square-o"></i></a>
                                                <a href="#" onclick="return confirm('¿Estás seguro de eliminar este registro?') ? window.location.href='{{ route('integridadtablas.delete', $integridad->id) }}' : false" class="nav-link"><i class="fa fa-minus-square"></i></a>
                                            </div>
                                        </td>
                                    </tr>
                                    @php $index++; @endphp
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="7">No hay información</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script>
        setTimeout(function() {
            $('.alert').fadeOut('slow');
        }, 5000); // 5000 milisegundos = 5 segundos
    </script>

    <style>
        .thead-pastel-yellow {
            background-color: #fff7bd;
        }
    </style>

    <!-- Agrega este código al final del archivo blade para incluir el script -->
    <script>
        // Función para buscar en la tabla
        function buscar() {
            // Obtiene el valor ingresado en el input de búsqueda
            var inputValor = document.getElementById('inputBusqueda').value.toUpperCase();
            // Obtiene las filas de la tabla
            var filas = document.getElementsByTagName('tr');
            
            // Itera sobre las filas para ocultar las que no coinciden con la búsqueda
            for (var i = 0; i < filas.length; i++) {
                var celdaTablaReferenciada = filas[i].getElementsByTagName('td')[3]; // Cambiado a 3 para reflejar la columna de la tabla referenciada
                if (celdaTablaReferenciada) {
                    var textoCelda = celdaTablaReferenciada.textContent || celdaTablaReferenciada.innerText;
                    if (textoCelda.toUpperCase().indexOf(inputValor) > -1) {
                        filas[i].style.display = '';
                    } else {
                        filas[i].style.display = 'none';
                    }
                }
            }
        }
    </script>

@endsection
