<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AdminProfileController extends Controller
{
    public function index()
    {
        $admin = Usuario::with('localidad.municipio')->findOrFail(Auth::id());

        return view('admin.profile', [
            'admin' => $admin,
        ]);
    }

    public function update(Request $request)
    {
        $admin = Usuario::findOrFail(Auth::id());

        $request->validate([
            'nombre' => 'required|string|max:150',
            'email' => 'required|email|max:150|unique:usuarios,email,' . $admin->id,
            'telefono' => 'nullable|string|max:20',
            'whatsapp' => 'nullable|string|max:20',
            'descripcion' => 'nullable|string',
            'foto' => 'nullable|image|max:4096',
        ]);

        $admin->nombre = $request->input('nombre');
        $admin->email = $request->input('email');
        $admin->telefono = $request->input('telefono');
        $admin->whatsapp = $request->input('whatsapp');
        $admin->descripcion = $request->input('descripcion');

        if ($request->hasFile('foto')) {
            $admin->foto_perfil = '/storage/' . $request->file('foto')->store('perfiles', 'public');
        }

        $admin->save();

        return response()->json(['ok' => true, 'admin' => $admin]);
    }

    public function updatePassword(Request $request)
    {
        $admin = Usuario::findOrFail(Auth::id());
        $tieneContrasenaYa = (bool) $admin->password;

        $reglas = [
            'password_nueva' => 'required|string|min:8|confirmed',
        ];
        if ($tieneContrasenaYa) {
            $reglas['password_actual'] = 'required|string';
        }

        $request->validate($reglas);

        if ($tieneContrasenaYa && !Hash::check($request->input('password_actual'), $admin->password)) {
            return response()->json(['ok' => false, 'message' => 'Tu contraseña actual no es correcta.'], 422);
        }

        $admin->password = Hash::make($request->input('password_nueva'));
        $admin->save();

        return response()->json(['ok' => true]);
    }
}
