@extends('layout.layout')

@section('title', 'Lista de Tablas')

@section('content')
    <div class="container mt-5">
        <h1 class="mb-4">Lista de Tablas con Claves Primarias y Foráneas</h1>
        <div class="row">
            @foreach ($tableNames as $tableName)
                <div class="col-md-4 mb-4">
                    <div class="card">
                        <div class="card-header">{{ $tableName }}</div>
                        <div class="card-body">
                            <h5 class="card-title">Claves Primarias</h5>
                            <ul class="list-group">
                                @foreach ($colPrimaryKeys[$tableName] as $primaryKey)
                                    <li class="list-group-item">{{ $primaryKey }}</li>
                                @endforeach
                            </ul>
                            <h5 class="card-title mt-3">Claves Foráneas</h5>
                            <ul class="list-group">
                                @foreach ($colForeignKeys[$tableName] as $foreignKey)
                                    <li class="list-group-item">{{ $foreignKey }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection
