<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verificación de Administrador</title>
    @vite(['resources/css/app1.css'])
</head>
<body>
    <div class="auth-wrapper">
        <div class="card">
            <h2>Verificación de Dos Pasos</h2>
            <p class="auth-subtitle">Hemos enviado un código de 6 dígitos a tu correo electrónico.</p>

            @if(session('mensaje'))
                <div class="alert-success">{{ session('mensaje') }}</div>
            @endif

            @if($errors->any())
                <div class="alert-danger">
                    {{ $errors->first() }}
                </div>
            @endif

            <form action="{{ route('auth.verify.submit') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="verification_code">Código de Verificación:</label>
                    <input type="text" id="verification_code" name="verification_code" maxlength="6" required autofocus>
                </div>
                <button type="submit">Verificar Código</button>
            </form>
        </div>
    </div>
</body>
</html>