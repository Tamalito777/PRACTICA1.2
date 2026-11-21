<?php

namespace App\Repositories;

use App\Models\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;

class AuthRepository{
    public function Login(array $credenciales){
        $user = User::where("email", "=", $credenciales['email'])->first();
        
        if (!$user) {
            throw new Exception("Usuario no encontrado");
        }

        if (!Auth::validate($credenciales)) {
            throw new Exception("Credenciales incorrectas");
        }

        if ($user->rol === 'empleado') {
            Auth::login($user);
            return ['status' => 'success', 'redirect' => '/empleado/dashboard'];
        }

        if ($user->rol === 'administrador') {
            $codigo = rand(100000, 999999);
            
            $user->verification_code = $codigo;
            $user->verification_code_expires_at = Carbon::now()->addMinutes(5); 
            $user->save();

            Mail::raw("Tu código de verificación para el sistema es: $codigo. Este código expira en 5 minutos.", function ($message) use ($user) {
                $message->to($user->email)
                        ->subject('Código de Verificación - Administrador');
            });

            session(['auth_admin_id' => $user->id]);

            return ['status' => 'two_factor', 'redirect' => route('auth.verify.form')];
        }

        throw new Exception("Rol no autorizado");
    }

    public function verificarCodigoAdmin($codigo)
    {
        $userId = session('auth_admin_id');
        if (!$userId) {
            throw new Exception("Sesión de verificación inválida o expirada.");
        }

        $user = User::find($userId);

        if (!$user || $user->verification_code !== $codigo) {
            throw new Exception("El código de verificación es incorrecto.");
        }

        if (Carbon::now()->gt($user->verification_code_expires_at)) {
            throw new Exception("El código de verificación ha expirado."); 
        }

        $user->verification_code = null;
        $user->verification_code_expires_at = null;
        $user->save();

        Auth::login($user);
        session()->forget('auth_admin_id');

        return true;
    }

}