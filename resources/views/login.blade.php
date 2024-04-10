<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Login</title>
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
</head>
<body>
    <hgroup>
        <h1>BIENVENIDO</h1>
        <img src="{{asset('img/login_logo.png')}}" alt="Logo_login" class="logo_img">
    </hgroup>
    <form method="POST" action="/login">
        @csrf
        @if(Session::has('success1'))
            <div class="alert alert-success">
                {{ Session::get('success1') }} <br>
                {{ Session::get('success2') }}
            </div>
        @endif
        @if(Session::has('correo'))
            <div class="alert alert-danger">
                {{ Session::get('correo') }}
            </div>
        @endif

        @if(Session::has('contraseña'))
            <div class="alert alert-danger">
                {{ Session::get('contraseña') }}
            </div>
        @endif
        <div class="group">
            <input type="email" name="email" value="{{ old('email') }}">
            <span class="highlight"></span>
            <span class="bar"></span>
            <label class="{{ old('email') ? 'used' : '' }}">Correo</label>
            @error('email')
                <span class="error-message">{{ $message }}</span>
            @enderror
        </div>
        <div class="group">
            <input type="password" name="password">
            <span class="highlight"></span>
            <span class="bar"></span>
            <label class="{{ old('password') ? 'used' : '' }}">Contraseña</label>
            @error('password')
                <span class="error-message">{{ $message }}</span>
            @enderror
        </div>
        <div>
            <span>No estás registrado? <a href="/register">Registrarse</a></span>
        </div><br>
        <button type="submit" class="button buttonBlue">INICIAR SESIÓN
            <div class="ripples buttonRipples"><span class="ripplesCircle"></span></div>
        </button>
    </form>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="{{ asset('js/login.js') }}"></script>
</body>
</html>