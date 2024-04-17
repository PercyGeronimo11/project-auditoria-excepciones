@extends('layout.layout')

@section('title', 'Listar Consultas')

@section('content')
    <div class="container mt-5">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    
                    <div class="card-header">
                        <strong class="card-title">Lista de bases de datos</strong>
                    </div>                

                    @if(session('success'))
                    <div class="sufee-alert alert with-close alert-primary alert-dismissible fade show">
                        <span class="badge badge-pill badge-primary">Success</span>
                    {{ session('success') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                @endif

                    <div class="table-stats order-table ov-h">
                        @if ($consultas->isEmpty())
                            <div class="alert alert-warning" role="alert">
                                No hay consultas registradas para esta base de datos.
                            </div>
                        @else
                            <div class="table-stats order-table ov-h">
                                <table class="table ">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Nombre</th>
                                            <th>Consulta</th>
                                            <th>Fecha</th>
                                            <th>Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($consultas as $consulta)
                                            <tr>
                                                <td>{{ $consulta->id }}</td>
                                                <td>{{ $consulta->nombre }}</td>
                                                <td>
                                                    <button type="button" class="btn btn-gris" data-toggle="modal" data-target="#consultaModal{{ $consulta->id }}">
                                                        <i class="fa fa-eye"></i>
                                                    </button>
                                                </td>
                                                <td>{{ $consulta->fecha }}</td>
                                                <td class="text-center">
                                                    <div style="display: flex; justify-content: flex-end; width: 100%;">
                                                        <a href="{{ route('consultas.resultados', ['id' => $consulta->id]) }}" class="nav-link"><i class="fa fa-sign-in"></i></a>
                                                        <a href="{{ route('consultas.edit', ['id' => $consulta->id]) }}" class="nav-link"><i class="fa fa-edit"></i></a>
                                                        <form id="deleteForm{{ $consulta->id }}" action="{{ route('consultas.destroy', $consulta->id) }}" method="POST" style="display: none;">
                                                            @csrf
                                                            @method('DELETE')
                                                        </form>
                                                        <a href="#" class="nav-link" onclick="event.preventDefault(); if(confirm('¿Estás seguro de que quieres eliminar esta consulta?')) { document.getElementById('deleteForm{{ $consulta->id }}').submit(); }">
                                                            <i class="fa fa-minus-circle"></i>
                                                        </a>                                                                                                          </div>
                                                </td>
                                            </tr>
                                            <!-- Modal -->
                                            <div class="modal fade" id="consultaModal{{ $consulta->id }}" tabindex="-1" role="dialog" aria-labelledby="consultaModalLabel{{ $consulta->id }}" aria-hidden="true">
                                                <div class="modal-dialog" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="consultaModalLabel{{ $consulta->id }}">Consulta</h5>
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body">
                                                            {{ $consulta->consulta }}
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
