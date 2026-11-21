<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('Login');
});

Route::get('/login', function () {
    return view('Login');
})->name('login');

Route::post('/iniciarsesion', [AuthController::class, 'login'])
    ->name('iniciarsesion');

Route::get('/auth/verificar-codigo', [AuthController::class, 'showVerifyForm'])
    ->name('auth.verify.form');

Route::post('/auth/verificar-codigo', [AuthController::class, 'verifyCode'])
    ->name('auth.verify.submit');

Route::middleware('auth')->group(function () {

    Route::get('/cerrarsesion', [AuthController::class, 'cerrarSesion'])
        ->name('cerrarsesion');

    Route::middleware(['role:administrador'])->group(function () {
        Route::get('/admin/dashboard', [UserController::class, 'index'])->name('admin.dashboard');

        Route::get('/admin/usuarios', [UserController::class, 'index'])->name('admin.usuarios.index');
        Route::get('/admin/usuarios/crear', [UserController::class, 'create'])->name('admin.usuarios.create');
        Route::post('/admin/usuarios', [UserController::class, 'store'])->name('admin.usuarios.store');
        Route::get('/admin/usuarios/{id}', [UserController::class, 'show'])->name('admin.usuarios.show');
        Route::get('/admin/usuarios/{id}/editar', [UserController::class, 'edit'])->name('admin.usuarios.edit');
        Route::put('/admin/usuarios/{id}', [UserController::class, 'update'])->name('admin.usuarios.update');

        Route::delete('/admin/usuarios/{id}', [UserController::class, 'destroy'])->name('admin.usuarios.destroy');
        Route::patch('/admin/usuarios/{id}/restaurar', [UserController::class, 'restore'])->name('admin.usuarios.restore');
        Route::delete('/admin/usuarios/{id}/eliminar-permanente', [UserController::class, 'forceDestroy'])->name('admin.usuarios.forceDestroy');
    });

    Route::middleware(['role:empleado'])->group(function () {
        Route::get('/empleado/dashboard', [UserController::class, 'index'])->name('empleado.dashboard');

        Route::get('/empleado/usuarios', [UserController::class, 'index'])->name('empleado.usuarios.index');
        Route::get('/empleado/usuarios/crear', [UserController::class, 'create'])->name('empleado.usuarios.create');
        Route::post('/empleado/usuarios', [UserController::class, 'store'])->name('empleado.usuarios.store');
        Route::get('/empleado/usuarios/{id}', [UserController::class, 'show'])->name('empleado.usuarios.show');
        Route::get('/empleado/usuarios/{id}/editar', [UserController::class, 'edit'])->name('empleado.usuarios.edit');
        Route::put('/empleado/usuarios/{id}', [UserController::class, 'update'])->name('empleado.usuarios.update');
    });

});