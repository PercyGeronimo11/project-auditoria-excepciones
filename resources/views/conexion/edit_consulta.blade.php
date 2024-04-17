@extends('layout.layout')

@section('title', 'Editar Consulta')

@section('content')
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header">
                        <strong class="card-title">Editar Consulta</strong>
                    </div>
                    <div class="card-body">    
                        
                            @if ($errors->any())
                            <div class="sufee-alert alert with-close alert-danger alert-dismissible fade show">
                                <span class="badge badge-pill badge-danger">Success</span>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div> 
                    @endif
                    
                        <form action="{{ route('consultas.update', ['id' => $consulta->id]) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="form-group">
                                <label for="nombre">Nombre:</label>
                                <input type="text" class="form-control" id="nombre" name="nombre" value="{{ $consulta->nombre }}" required>
                            </div>
                            <div class="form-group">
                                <label for="consulta">Consulta:</label>
                                <textarea class="form-control" id="consulta" name="consulta" rows="5">{{ $consulta->consulta }}</textarea>
                            </div>
                            <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
