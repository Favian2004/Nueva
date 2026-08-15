<?php

namespace App\Http\Controllers;

use App\Models\Postulacion;
use App\Models\Vacante;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UsuarioVacanteController extends Controller
{
    public function create()
    {
        $usuario = Auth::user();

        return view('usuario.publicar-vacante', [
            'usuario' => $usuario,
            'vacante' => null,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'titulo' => 'required|string|max:255',
            'publicante' => 'required|string|max:150',
            'ubicacion' => 'required|string|max:255',
            'trabajadores_requeridos' => 'required|integer|min:1',
            'tipo_pago' => 'required|in:Pago al día,Pago por destajo,Pago semanal,Pago quincenal,Pago mensual',
            'salario' => 'nullable|string|max:100',
            'experiencia' => 'required|in:Sin experiencia,6 meses mínimo,1 año mínimo,2 años mínimo,3 años mínimo,5 años mínimo',
            'contrato' => 'required|in:Temporal,Temporada,Por obra,Fijo,Eventual',
            'descripcion' => 'required|string',
            'fecha_trabajo' => 'required|string|max:150',
            'duracion' => 'nullable|string|max:100',
            'fecha_limite' => 'nullable|date',
            'telefono' => 'required|string|max:20',
            'whatsapp' => 'nullable|string|max:20',
            'imagen' => 'nullable|image|max:4096',
            'beneficios' => 'nullable|array',
            'beneficios.*' => 'string|max:100',
        ]);

        $rutaImagen = null;
        if ($request->hasFile('imagen')) {
            $rutaImagen = '/storage/' . $request->file('imagen')->store('vacantes', 'public');
        }

        Vacante::create([
            'empleador_id' => Auth::id(),
            'titulo' => $request->input('titulo'),
            'publicante' => $request->input('publicante'),
            'ubicacion' => $request->input('ubicacion'),
            'trabajadores_requeridos' => $request->input('trabajadores_requeridos'),
            'tipo_pago' => $request->input('tipo_pago'),
            'salario' => $request->input('salario') ?: 'A convenir',
            'experiencia' => $request->input('experiencia'),
            'contrato' => $request->input('contrato'),
            'beneficios' => $request->input('beneficios', []),
            'descripcion' => $request->input('descripcion'),
            'fecha_trabajo' => $request->input('fecha_trabajo'),
            'duracion' => $request->input('duracion'),
            'fecha_limite' => $request->input('fecha_limite'),
            'telefono' => $request->input('telefono'),
            'whatsapp' => $request->input('whatsapp'),
            'imagen' => $rutaImagen,
            'estado' => 'activa',
        ]);

        return response()->json(['ok' => true]);
    }

    public function edit($id)
    {
        $usuario = Auth::user();
        $vacante = Vacante::where('empleador_id', Auth::id())->findOrFail($id);

        return view('usuario.publicar-vacante', [
            'usuario' => $usuario,
            'vacante' => $vacante,
        ]);
    }

    public function update(Request $request, $id)
    {
        $vacante = Vacante::where('empleador_id', Auth::id())->findOrFail($id);

        $request->validate([
            'titulo' => 'required|string|max:255',
            'publicante' => 'required|string|max:150',
            'ubicacion' => 'required|string|max:255',
            'trabajadores_requeridos' => 'required|integer|min:1',
            'tipo_pago' => 'required|in:Pago al día,Pago por destajo,Pago semanal,Pago quincenal,Pago mensual',
            'salario' => 'nullable|string|max:100',
            'experiencia' => 'required|in:Sin experiencia,6 meses mínimo,1 año mínimo,2 años mínimo,3 años mínimo,5 años mínimo',
            'contrato' => 'required|in:Temporal,Temporada,Por obra,Fijo,Eventual',
            'descripcion' => 'required|string',
            'fecha_trabajo' => 'required|string|max:150',
            'duracion' => 'nullable|string|max:100',
            'fecha_limite' => 'nullable|date',
            'telefono' => 'required|string|max:20',
            'whatsapp' => 'nullable|string|max:20',
            'imagen' => 'nullable|image|max:4096',
            'beneficios' => 'nullable|array',
            'beneficios.*' => 'string|max:100',
        ]);

        $rutaImagen = $vacante->imagen;
        if ($request->hasFile('imagen')) {
            $rutaImagen = '/storage/' . $request->file('imagen')->store('vacantes', 'public');
        }

        $vacante->update([
            'titulo' => $request->input('titulo'),
            'publicante' => $request->input('publicante'),
            'ubicacion' => $request->input('ubicacion'),
            'trabajadores_requeridos' => $request->input('trabajadores_requeridos'),
            'tipo_pago' => $request->input('tipo_pago'),
            'salario' => $request->input('salario') ?: 'A convenir',
            'experiencia' => $request->input('experiencia'),
            'contrato' => $request->input('contrato'),
            'beneficios' => $request->input('beneficios', []),
            'descripcion' => $request->input('descripcion'),
            'fecha_trabajo' => $request->input('fecha_trabajo'),
            'duracion' => $request->input('duracion'),
            'fecha_limite' => $request->input('fecha_limite'),
            'telefono' => $request->input('telefono'),
            'whatsapp' => $request->input('whatsapp'),
            'imagen' => $rutaImagen,
        ]);

        return response()->json(['ok' => true]);
    }

    public function misVacantes(Request $request)
    {
        $estado = $request->input('estado');
        $contrato = $request->input('contrato');
        $q = $request->input('q');

        $vacantes = Vacante::withCount('postulaciones')
            ->where('empleador_id', Auth::id())
            ->when($estado, fn($query) => $query->where('estado', $estado))
            ->when($contrato, fn($query) => $query->where('contrato', $contrato))
            ->when($q, fn($query) => $query->where('titulo', 'like', "%{$q}%"))
            ->latest()
            ->get();

        $vacantesActivas = Vacante::where('empleador_id', Auth::id())->where('estado', 'activa')->count();

        $totalPostulantes = Postulacion::whereHas('vacante', function ($qy) {
            $qy->where('empleador_id', Auth::id());
        })->count();

        $contratados = Postulacion::whereHas('vacante', function ($qy) {
            $qy->where('empleador_id', Auth::id());
        })->where('estado', 'contratado')->count();

        return view('usuario.mis-vacantes', [
            'vacantes' => $vacantes,
            'vacantesActivas' => $vacantesActivas,
            'totalPostulantes' => $totalPostulantes,
            'contratados' => $contratados,
            'estado' => $estado,
            'contrato' => $contrato,
            'q' => $q,
        ]);
    }

    public function cerrar($id)
    {
        $vacante = Vacante::where('empleador_id', Auth::id())->findOrFail($id);
        $vacante->estado = 'cerrada';
        $vacante->save();

        return response()->json(['ok' => true]);
    }

    public function reactivar($id)
    {
        $vacante = Vacante::where('empleador_id', Auth::id())->findOrFail($id);
        $vacante->estado = 'activa';
        $vacante->save();

        return response()->json(['ok' => true]);
    }

    public function destroy($id)
    {
        $vacante = Vacante::where('empleador_id', Auth::id())->findOrFail($id);
        $vacante->delete();

        return response()->json(['ok' => true]);
    }
}
