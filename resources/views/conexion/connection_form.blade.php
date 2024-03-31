@extends('layout.layout')

@section('title', 'Formulario de Conexión')

@section('content')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Formulario de Conexión</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('connect.database') }}">
                        @csrf
                        <div class="mb-3">
                            <label for="driver" class="form-label">Driver:</label>
                            <select name="driver" id="driver" class="form-select" required>
                                <option value="mysql">MySQL</option>
                                <option value="sqlsrv">SQL Server</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="host" class="form-label">Host:</label>
                            <input type="text" name="host" id="host" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="database" class="form-label">Database:</label>
                            <input type="text" name="database" id="database" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="username" class="form-label">Username:</label>
                            <input type="text" name="username" id="username" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Password:</label>
                            <input type="password" name="password" id="password" class="form-control">
                        </div>
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">Connect</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
