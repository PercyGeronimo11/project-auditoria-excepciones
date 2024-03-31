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
        <h1>Iniciar Sesión</h1>
        <img src="{{asset('img/login_logo.png')}}" alt="Logo_login" class="logo_img">
    </hgroup>
    <form method="POST" action="/login">
        @csrf
        <div class="group">
            <input type="email" name="email" required><span class="highlight"></span><span class="bar"></span>
            <label>Correo</label>
        </div>
        <div class="group">
            <input type="password" name="password" required><span class="highlight"></span><span class="bar"></span>
            <label>Contraseña</label>
        </div>
        <div>
            <span>No estas registrado? <a href="/register">Registrate</a></span>
        </div><br>
        <button type="submit" class="button buttonBlue">Iniciar sesión
            <div class="ripples buttonRipples"><span class="ripplesCircle"></span></div>
        </button>
    </form>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="{{ asset('js/login.js') }}"></script>
</body>
</html>