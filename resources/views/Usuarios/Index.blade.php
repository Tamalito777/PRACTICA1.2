<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel Principal - Usuarios</title>
    @vite(['resources/css/app.css'])
</head>
<body>
    @php
        $usuarioActual = \Illuminate\Support\Facades\Auth::user();
        $esAdmin = $usuarioActual->rol === 'administrador';
        $prefix = $esAdmin ? 'admin.usuarios.' : 'empleado.usuarios.';
    @endphp

    <div class="contenedor">
        @if($esAdmin)
            <h1>Panel de Administrador - Gestión de Usuarios</h1>
        @else
            <h1>Panel de Empleado - Gestión de Usuarios</h1>
        @endif

        <div class="barra-superior">
            <span>Usuario: <strong>{{ $usuarioActual->name }}</strong> ({{ $usuarioActual->rol }})</span>
            <a href="{{ route($prefix . 'create') }}" class="btn btn-primario">Registrar Nuevo Usuario</a>
            <a href="{{ route('cerrarsesion') }}" class="btn">Cerrar Sesión</a>
        </div>

        @if(session('mensaje'))
            <div class="msg-ok">{{ session('mensaje') }}</div>
        @endif

        @if($errors->any())
            <div class="msg-err">{{ $errors->first() }}</div>
        @endif

        <h2>Usuarios Registrados</h2>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Correo</th>
                    <th>Rol</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($usuarios as $usuario)
                    <tr>
                        <td>{{ $usuario->id }}</td>
                        <td>{{ $usuario->name }}</td>
                        <td>{{ $usuario->email }}</td>
                        <td>{{ $usuario->rol }}</td>
                        <td class="acciones">
                            <a href="{{ route($prefix . 'show', $usuario->id) }}" class="btn">Ver</a>
                            <a href="{{ route($prefix . 'edit', $usuario->id) }}" class="btn btn-advertencia">Editar</a>

                            @if($esAdmin)
                                <a href="#modal-soft-{{ $usuario->id }}" class="btn btn-advertencia">Eliminación Lógica</a>
                                <a href="#modal-force-{{ $usuario->id }}" class="btn btn-peligro">Eliminación Permanente</a>
                            @endif
                        </td>
                    </tr>

                    @if($esAdmin)
                        <x-modalconfirmacion
                            id="modal-soft-{{ $usuario->id }}"
                            titulo="Confirmar Eliminación Lógica (Soft Delete)"
                            :mensaje="'¿Deseas deshabilitar al usuario ' . $usuario->name . '? Podrás recuperarlo después desde la papelera.'"
                            :action="route('admin.usuarios.destroy', $usuario->id)"
                            method="DELETE"
                            textoBoton="Deshabilitar"
                            claseBoton="btn-advertencia"
                        />

                        <x-modalconfirmacion
                            id="modal-force-{{ $usuario->id }}"
                            titulo="Confirmar Eliminación Permanente"
                            :mensaje="'¿Deseas eliminar definitivamente a ' . $usuario->name . '? Esta acción no se puede deshacer.'"
                            :action="route('admin.usuarios.forceDestroy', $usuario->id)"
                            method="DELETE"
                            textoBoton="Eliminar Definitivamente"
                            claseBoton="btn-peligro"
                        />
                    @endif
                @endforeach
            </tbody>
        </table>

        @if($esAdmin)
            <h2>Usuarios Eliminados Lógicamente (Papelera)</h2>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Correo</th>
                        <th>Rol</th>
                        <th>Eliminado en</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($eliminados as $eliminado)
                        <tr>
                            <td>{{ $eliminado->id }}</td>
                            <td>{{ $eliminado->name }}</td>
                            <td>{{ $eliminado->email }}</td>
                            <td>{{ $eliminado->rol }}</td>
                            <td>{{ $eliminado->deleted_at }}</td>
                            <td class="acciones">
                                <a href="#modal-restore-{{ $eliminado->id }}" class="btn btn-exito">Recuperar</a>
                                <a href="#modal-force-trash-{{ $eliminado->id }}" class="btn btn-peligro">Eliminar Permanente</a>
                            </td>
                        </tr>

                        <x-modalconfirmacion
                            id="modal-restore-{{ $eliminado->id }}"
                            titulo="Recuperar Usuario"
                            :mensaje="'¿Deseas restaurar el acceso del usuario ' . $eliminado->name . '?'"
                            :action="route('admin.usuarios.restore', $eliminado->id)"
                            method="PATCH"
                            textoBoton="Recuperar"
                            claseBoton="btn-exito"
                        />

                        <x-modalconfirmacion
                            id="modal-force-trash-{{ $eliminado->id }}"
                            titulo="Confirmar Eliminación Permanente"
                            :mensaje="'¿Deseas borrar permanentemente a ' . $eliminado->name . ' de la base de datos?'"
                            :action="route('admin.usuarios.forceDestroy', $eliminado->id)"
                            method="DELETE"
                            textoBoton="Eliminar Definitivamente"
                            claseBoton="btn-peligro"
                        />
                    @empty
                        <tr>
                            <td colspan="6">No hay usuarios en la papelera.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        @endif
    </div>
</body>
</html>