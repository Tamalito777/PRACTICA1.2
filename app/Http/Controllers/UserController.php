<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Repositories\UserRepository;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    protected $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function index()
    {
        $usuarios = $this->userRepository->getAllActivos();
        $eliminados = Auth::user()->rol === 'administrador'
            ? $this->userRepository->getEliminados()
            : collect();

        return view('Usuarios.Index', compact('usuarios', 'eliminados'));
    }

    public function create()
    {
        return view('Usuarios.Create');
    }

    public function store(StoreUserRequest $request)
    {
        try {
            $this->userRepository->create($request->validated());
             return redirect()->route($this->rutaIndexPorRol())->with('mensaje', 'Usuario creado');
        } catch (Exception $e) {
            return back()->withErrors(['email' => $e->getMessage()])->withInput();
        }
    }

    public function show(int $id)
    {
        try {
            $usuario = $this->userRepository->findById($id);
            return view('Usuarios.Show', compact('usuario'));
        } catch (Exception $e) {
            return redirect()->route('usuarios.index')->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function edit(int $id)
    {
        try {
            $usuario = $this->userRepository->findById($id);
            return view('Usuarios.Edit', compact('usuario'));
        } catch (Exception $e) {
            return redirect()->route('usuarios.index')->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function update(UpdateUserRequest $request, int $id)
    {
        try {
            $this->userRepository->update($id, $request->validated());
            return redirect()->route($this->rutaIndexPorRol())->with('mensaje', 'Usuario actualizado');
        } catch (Exception $e) {
            return back()->withErrors(['email' => $e->getMessage()])->withInput();
        }
    }

    public function destroy(int $id)
    {
        try {
            $this->userRepository->softDelete($id);
            return redirect()->route($this->rutaIndexPorRol())->with('mensaje', 'Usuario deshabilitado');
        } catch (Exception $e) {
            return redirect()->route('usuarios.index')->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function restore(int $id)
    {
        try {
            $this->userRepository->restore($id);
             return redirect()->route($this->rutaIndexPorRol())->with('mensaje', 'Usuario recuperado');
        } catch (Exception $e) {
            return redirect()->route('usuarios.index')->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function forceDestroy(int $id)
    {
        try {
            $this->userRepository->forceDelete($id);
            return redirect()->route($this->rutaIndexPorRol())->with('mensaje', 'Usuario eliminado permanentemente');
        } catch (Exception $e) {
            return redirect()->route('usuarios.index')->withErrors(['error' => $e->getMessage()]);
        }
    }

    private function rutaIndexPorRol(): string
{
    return \Illuminate\Support\Facades\Auth::user()->rol === 'administrador'
        ? 'admin.usuarios.index'
        : 'empleado.usuarios.index';
}
}
