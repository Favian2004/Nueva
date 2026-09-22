<?php

namespace App\Http\Controllers;

use App\Models\Testimonio;
use Illuminate\Http\Request;

class AdminTestimonioController extends Controller
{
    public function index()
    {
        $testimonios = Testimonio::with('usuario')
            ->orderByRaw("FIELD(estado, 'pendiente', 'aprobado', 'rechazado')")
            ->latest()
            ->get();

        return view('admin.testimonios', [
            'testimonios' => $testimonios,
        ]);
    }

    public function cambiarEstado(Request $request, $id)
    {
        $request->validate([
            'estado' => 'required|in:pendiente,aprobado,rechazado',
        ]);

        $testimonio = Testimonio::findOrFail($id);
        $testimonio->estado = $request->input('estado');
        $testimonio->save();

        return response()->json(['ok' => true]);
    }

    public function destroy($id)
    {
        Testimonio::findOrFail($id)->delete();

        return response()->json(['ok' => true]);
    }
}
