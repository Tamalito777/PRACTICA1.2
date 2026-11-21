<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Usuario</title>
    @vite(['resources/css/app.css'])
</head>
<body>
    @php
        $prefix = \Illuminate\Support\Facades\Auth::user()->rol === 'administrador' ? 'admin.usuarios.' : 'empleado.usuarios.';
    @endphp

    <div class="contenedor">
        <h1>Editar Usuario #{{ $usuario->id }}</h1>

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

        <form id="form-editar-usuario" action="{{ route($prefix . 'update', $usuario->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="form-grupo">
                <label for="name">Nombre:</label>
                <input type="text" id="name" name="name" value="{{ old('name', $usuario->name) }}" required>
            </div>

            <div class="form-grupo">
                <label for="email">Correo Electrónico:</label>
                <input type="email" id="email" name="email" value="{{ old('email', $usuario->email) }}" required>
            </div>

            <div class="form-grupo">
                <label for="password">Nueva Contraseña (opcional):</label>
                <input type="password" id="password" name="password">
            </div>

            <div class="form-grupo">
                <label for="rol">Rol:</label>
                <select id="rol" name="rol" required>
                    <option value="empleado" {{ old('rol', $usuario->rol) === 'empleado' ? 'selected' : '' }}>empleado</option>
                    <option value="administrador" {{ old('rol', $usuario->rol) === 'administrador' ? 'selected' : '' }}>administrador</option>
                </select>
            </div>

            <a href="#modal-actualizar-usuario" class="btn btn-primario">Actualizar Usuario</a>
        </form>
    </div>

    <div id="modal-actualizar-usuario" class="modal-overlay">
        <div class="modal-caja">
            <h3>Confirmar Actualización</h3>
            <p>¿Deseas guardar los cambios realizados en este usuario?</p>
            <div class="modal-acciones">
                <a href="#" class="btn">Cancelar</a>
                <button type="submit" form="form-editar-usuario" class="btn btn-primario">Confirmar Actualización</button>
            </div>
        </div>
    </div>
</body>
</html>