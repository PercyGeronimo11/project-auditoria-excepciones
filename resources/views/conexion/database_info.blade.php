@extends('layout.layout')

@section('title', 'Formulario de Conexión')

@section('content')
<div class="container mt-5">
    <div class="row">
        @foreach($tablesData as $tableName => $table)
        <div class="col-md-4 mb-4">
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
                    <button type="submit" class="btn btn-primary">Ver</button>
                </form>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection
