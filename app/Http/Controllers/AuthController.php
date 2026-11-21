<?php

namespace App\Http\Controllers;

use App\Http\Requests\AuthLoginRequest;
use App\Repositories\AuthRepository;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    protected $authRepository;

    public function __construct(AuthRepository $authRepository)
    {
        $this->authRepository = $authRepository;
    }

    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(AuthLoginRequest $request)
    {
        try {
            $resultado = $this->authRepository->Login($request->validated());

            if ($resultado['status'] === 'two_factor') {
                return redirect()->route('auth.verify.form')->with('mensaje', 'Se ha enviado un código de verificación a tu correo.');
            }

            return redirect($resultado['redirect'])->with('mensaje', 'Bienvenido');

        } catch (Exception $e) {
            return back()->withErrors(['email' => $e->getMessage()])->withInput();
        }
    }

    public function showVerifyForm()
    {
        if (!session('auth_admin_id')) {
            return redirect()->route('login')->withErrors(['email' => 'Acceso no autorizado a esta ruta.']);
        }
        return view('auth.VerificarCodigo');
    }

    public function verifyCode(Request $request)
    {
        $request->validate(['verification_code' => 'required|numeric|digits:6']);

        try {
            $this->authRepository->verificarCodigoAdmin($request->verification_code);
            return redirect('/admin/dashboard')->with('mensaje', 'Autenticación completada con éxito.');
        } catch (Exception $e) {
            return back()->withErrors(['verification_code' => $e->getMessage()]);
        }
    }

    public function cerrarSesion(Request $request)
    {
        Session::flush();
        Auth::logout();
        return redirect('/login')->with('mensaje', 'Sesión cerrada correctamente.');
    }
}