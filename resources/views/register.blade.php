<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Register</title>
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
    <style>
        .group {
            position: relative;
            margin-bottom: 45px;
        }

        #emailName,
        select {
            font-size: 18px;
            padding: 10px;
            display: inline-block;
            width: calc(50% - 5px);
            border: none;
            border-bottom: 1px solid #757575;
        }

        #emailName {
            width: calc(50% - 5px);
        }

        select {
            width: 50%;
        }
    </style>
</head>
<body>
    <hgroup>
        <h1>REGISTRAR USUARIO</h1>
        <img src="{{asset('img/register_logo.png')}}" alt="Logo_login" class="logo_img">
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
            <input type="text" name="email" value="{{ old('email') }}" id="emailName">
            <select name="email_domain">
                <option value="@hotmail.com" selected>@hotmail.com</option>
                <option value="@gmail.com">@gmail.com</option>
                <!-- Agrega más opciones según tus necesidades -->
            </select>
            <span class="highlight"></span>
            <span class="bar"></span>
            <label class="{{ old('email') ? 'used' : '' }}">Correo</label>
            @error('email')
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
        <div>
            <span>Estas registrado? <a href="/">Inicia sesión</a></span>
        </div><br>
        <button type="submit" class="button buttonBlue">REGISTRAR
            <div class="ripples buttonRipples"><span class="ripplesCircle"></span></div>
        </button>
    </form>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="{{ asset('js/login.js') }}"></script>
</body>
</html>
