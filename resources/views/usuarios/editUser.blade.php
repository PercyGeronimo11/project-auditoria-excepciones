@extends('layout.layout')

@section('title', 'Editar Usuario')

@section('content')
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
<body>
    <hgroup>
        <h1>EDITAR USUARIO</h1>
    </hgroup>
    <form method="POST" action="/user/update/{{ $user->id }}">
        @csrf
        @method('PUT')

        <div class="group">
            <input type="text" name="name" value="{{ $user->name }}">
            <span class="highlight"></span>
            <span class="bar"></span>
            <label class="{{ $user->name ? 'used' : '' }}">Nombre</label>
            @error('name')
                <span class="error-message">{{ $message }}</span>
            @enderror
        </div>
        <div class="group">
            <input type="text" name="userName" value="{{ $user->userName }}">
            <span class="highlight"></span>
            <span class="bar"></span>
            <label class="{{ $user->userName ? 'used' : '' }}">Nombre de usuario</label>
            @error('userName')
                <span class="error-message">{{ $message }}</span>
            @enderror
        </div>
        <div class="group">
            <input type="password" name="password" value=""> 
            <span class="highlight"></span>
            <span class="bar"></span>
            <label class="{{ $user->password ? 'used' : '' }}">Contraseña</label>
            @error('password')
                <span class="error-message">{{ $message }}</span>
            @enderror
        </div>
        
        <button type="submit" class="button buttonBlue">GUARDAR CAMBIOS
            <div class="ripples buttonRipples"><span class="ripplesCircle"></span></div>
        </button>
    </form>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="{{ asset('js/login.js') }}"></script>
</body>
</html>
@endsection
