<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use Illuminate\Http\Request;

class AdminUsuarioController extends Controller
{
    public function index()
    {
        $usuarios = Usuario::with(['localidad', 'documentosVerificacion'])->orderBy('nombre')->get();

        return view('admin.usuarios', [
            'usuarios' => $usuarios,
        ]);
    }

    public function suspender(Request $request, $id)
    {
        $usuario = Usuario::findOrFail($id);
        $usuario->estado = 'suspendido';
        $usuario->motivo_suspension = $request->input('motivo') ?: 'Sin motivo especificado';
        $usuario->save();

        return response()->json(['ok' => true]);
    }

    public function reactivar($id)
    {
        $usuario = Usuario::findOrFail($id);
        $usuario->estado = 'activo';
        $usuario->motivo_suspension = null;
        $usuario->save();

        return response()->json(['ok' => true]);
    }

    public function hacerAdmin($id)
    {
        $usuario = Usuario::findOrFail($id);
        $usuario->rol = 'admin';
        $usuario->save();

        return response()->json(['ok' => true]);
    }

    public function quitarAdmin($id)
    {
        if ((int) $id === (int) auth()->id()) {
            return response()->json(['ok' => false, 'error' => 'No puedes quitarte el rol de administrador a ti mismo.'], 422);
        }

        $usuario = Usuario::findOrFail($id);
        $usuario->rol = 'usuario';
        $usuario->save();

        return response()->json(['ok' => true]);
    }

    public function crearAdmin(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:150',
            'email' => 'required|email|max:150|unique:usuarios,email',
            'password' => 'required|string|min:8|confirmed',
        ]);

        Usuario::create([
            'nombre' => $request->input('nombre'),
            'email' => $request->input('email'),
            'password' => \Illuminate\Support\Facades\Hash::make($request->input('password')),
            'localidad_id' => 1,
            'rol' => 'admin',
            'estado' => 'activo',
            'verificacion_estado' => 'aprobado',
            'email_verified_at' => now(),
        ]);

        return response()->json(['ok' => true]);
    }

    public function resolverVerificacion(Request $request, $id)
    {
        $request->validate([
            'estado' => 'required|in:aprobado,rechazado',
        ]);

        $usuario = Usuario::with('documentosVerificacion')->findOrFail($id);
        $nuevoEstado = $request->input('estado');

        $usuario->verificacion_estado = $nuevoEstado;
        $usuario->save();

        // Refleja el mismo resultado en cada documento individual que subió.
        foreach ($usuario->documentosVerificacion as $doc) {
            $doc->estado = $nuevoEstado;
            $doc->save();
        }

        return response()->json(['ok' => true]);
    }
}
