<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    @vite(['resources/css/app1.css'])
    <title>Iniciar Sesión</title>
</head>
<body>
    <div class="auth-wrapper">
        <div class="card">
            <h2>Iniciar Sesión</h2>

            @if(session('mensaje'))
                <div class="alert-success">
                    {{ session('mensaje') }}
                </div>
            @endif

            @if($errors->any())
                <div class="alert-danger">
                    {{ $errors->first() }}
                </div>
            @endif

            <form action="{{ route('iniciarsesion') }}" method="POST">
                @csrf

                <div class="form-group">
                    <label for="email">Correo Electrónico:</label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}" required>
                </div>

                <div class="form-group">
                    <label for="password">Contraseña:</label>
                    <input type="password" id="password" name="password" required>
                </div>

                <button type="submit">Entrar</button>
            </form>
        </div>
    </div>
</body>
</html>