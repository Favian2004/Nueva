<?php

namespace App\Http\Controllers;

use App\Models\Testimonio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UsuarioTestimonioController extends Controller
{
    /**
     * Guarda o actualiza el testimonio del usuario logueado (uno solo por
     * persona). Cada vez que lo edita, vuelve a quedar "pendiente" para que
     * un administrador lo revise antes de que se vea público.
     */
    public function store(Request $request)
    {
        $request->validate([
            'texto' => 'required|string|min:10|max:500',
            'estrellas' => 'nullable|integer|min:1|max:5',
        ]);

        $testimonio = Testimonio::firstOrNew(['usuario_id' => Auth::id()]);
        $testimonio->usuario_id = Auth::id();
        $testimonio->texto = $request->input('texto');
        $testimonio->estrellas = $request->input('estrellas');
        $testimonio->estado = 'pendiente';
        $testimonio->save();

        return response()->json(['ok' => true]);
    }

    public function destroy()
    {
        Testimonio::where('usuario_id', Auth::id())->delete();

        return response()->json(['ok' => true]);
    }
}
