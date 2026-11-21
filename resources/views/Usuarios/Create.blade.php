<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrar Usuario</title>
    @vite(['resources/css/app.css'])
</head>
<body>
    @php
        $prefix = \Illuminate\Support\Facades\Auth::user()->rol === 'administrador' ? 'admin.usuarios.' : 'empleado.usuarios.';
    @endphp

    <div class="contenedor">
        <h1>Registrar Nuevo Usuario</h1>

        <div class="barra-superior">
            <a href="{{ route($prefix . 'index') }}" class="btn">Volver al listado</a>
        </div>

        @if($errors->any())
            <div class="msg-err">
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form id="form-crear-usuario" action="{{ route($prefix . 'store') }}" method="POST">
            @csrf

            <div class="form-grupo">
                <label for="name">Nombre:</label>
                <input type="text" id="name" name="name" value="{{ old('name') }}" required>
            </div>

            <div class="form-grupo">
                <label for="email">Correo Electrónico:</label>
                <input type="email" id="email" name="email" value="{{ old('email') }}" required>
            </div>

            <div class="form-grupo">
                <label for="password">Contraseña:</label>
                <input type="password" id="password" name="password" required>
            </div>

            <div class="form-grupo">
                <label for="rol">Rol:</label>
                <select id="rol" name="rol" required>
                    <option value="empleado" {{ old('rol') === 'empleado' ? 'selected' : '' }}>empleado</option>
                    <option value="administrador" {{ old('rol') === 'administrador' ? 'selected' : '' }}>administrador</option>
                </select>
            </div>

            <a href="#modal-guardar-usuario" class="btn btn-primario">Guardar Usuario</a>
        </form>
    </div>

    <div id="modal-guardar-usuario" class="modal-overlay">
        <div class="modal-caja">
            <h3>Confirmar Registro</h3>
            <p>¿Deseas registrar este nuevo usuario con los datos ingresados?</p>
            <div class="modal-acciones">
                <a href="#" class="btn">Cancelar</a>
                <button type="submit" form="form-crear-usuario" class="btn btn-primario">Confirmar y Guardar</button>
            </div>
        </div>
    </div>
</body>
</html>