@extends('layout.layout')

@section('title', 'Crear Usuario')

@section('content')
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
<body>
    <hgroup>
        <h1>REGISTRAR USUARIO</h1>
    </hgroup>
    <form method="POST" action="/register/user">
        @csrf
        <div class="group">
            <input type="text" name="name" value="{{ old('name') }}">
            <span class="highlight"></span>
            <span class="bar"></span>
            <label class="{{ old('name') ? 'used' : '' }}">Nombre</label>
            @error('name')
                <span class="error-message">{{ $message }}</span>
            @enderror
        </div>
        <div class="group">
            <input type="text" name="userName" value="{{ old('userName') }}">
            <span class="highlight"></span>
            <span class="bar"></span>
            <label class="{{ old('userName') ? 'used' : '' }}">Nombre de usuario</label>
            @error('userName')
                <span class="error-message">{{ $message }}</span>
            @enderror
        </div>
        <div class="group">
            <input type="password" name="password" value="{{ old('password') }}">
            <span class="highlight"></span>
            <span class="bar"></span>
            <label class="{{ old('password') ? 'used' : '' }}">Contraseña</label>
            @error('password')
                <span class="error-message">{{ $message }}</span>
            @enderror
        </div>
        {{-- <div>
            <span>Estas registrado? <a href="/">Inicia sesión</a></span>
        </div><br> --}}
        <button type="submit" class="button buttonBlue">REGISTRAR
            <div class="ripples buttonRipples"><span class="ripplesCircle"></span></div>
        </button>
    </form>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="{{ asset('js/login.js') }}"></script>
</body>
</html>
@endsection