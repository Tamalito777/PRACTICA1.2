<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalle de Usuario</title>
    @vite(['resources/css/app.css'])
</head>
<body>
    @php
        $prefix = \Illuminate\Support\Facades\Auth::user()->rol === 'administrador' ? 'admin.usuarios.' : 'empleado.usuarios.';
    @endphp

    <div class="contenedor">
        <h1>Detalle del Usuario</h1>

        <div class="barra-superior">
            <a href="{{ route($prefix . 'index') }}" class="btn">Volver al listado</a>
            <a href="{{ route($prefix . 'edit', $usuario->id) }}" class="btn btn-advertencia">Editar Usuario</a>
        </div>

        <ul>
            <li><strong>ID:</strong> {{ $usuario->id }}</li>
            <li><strong>Nombre:</strong> {{ $usuario->name }}</li>
            <li><strong>Correo:</strong> {{ $usuario->email }}</li>
            <li><strong>Rol:</strong> {{ $usuario->rol }}</li>
            <li><strong>Creado:</strong> {{ $usuario->created_at }}</li>
        </ul>
    </div>
</body>
</html>