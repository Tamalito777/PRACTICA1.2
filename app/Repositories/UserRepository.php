<?php

namespace App\Repositories;

use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Hash;

class UserRepository
{
    public function getAllActivos()
    {
        return User::orderBy('id', 'asc')->get();
    }

    public function getEliminados()
    {
        return User::onlyTrashed()->orderBy('deleted_at', 'desc')->get();
    }

    public function findById(int $id): User
    {
        $user = User::find($id);
        if (!$user) {
            throw new Exception("Usuario no encontrado.");
        }
        return $user;
    }

    public function create(array $datos): User
    {
        return User::create([
            'name'     => $datos['name'],
            'email'    => $datos['email'],
            'password' => Hash::make($datos['password']),
            'rol'      => $datos['rol'],
        ]);
    }

    public function update(int $id, array $datos): User
    {
        $user = $this->findById($id);

        $user->name  = $datos['name'];
        $user->email = $datos['email'];
        $user->rol   = $datos['rol'];

        if (!empty($datos['password'])) {
            $user->password = Hash::make($datos['password']);
        }

        $user->save();
        return $user;
    }

    public function softDelete(int $id): bool
    {
        $user = $this->findById($id);
        return (bool) $user->delete();
    }

    public function restore(int $id): bool
    {
        $user = User::onlyTrashed()->where('id', $id)->first();
        if (!$user) {
            throw new Exception("Usuario eliminado no encontrado.");
        }
        return (bool) $user->restore();
    }

    public function forceDelete(int $id): bool
    {
        $user = User::withTrashed()->where('id', $id)->first();
        if (!$user) {
            throw new Exception("Usuario no encontrado.");
        }
        return (bool) $user->forceDelete();
    }
}