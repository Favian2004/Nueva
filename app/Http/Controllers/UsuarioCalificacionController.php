<?php

namespace App\Http\Controllers;

use App\Models\Calificacion;
use App\Models\Contratacion;
use App\Models\Servicio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UsuarioCalificacionController extends Controller
{
    /**
     * Solo puede calificar quien tenga una solicitud aceptada (o ya finalizada)
     * de este servicio. Así evitamos que cualquiera califique sin haberlo usado.
     */
    private function puedeCalificar($servicioId)
    {
        if (!Auth::check()) {
            return false;
        }

        return Contratacion::where('servicio_id', $servicioId)
            ->where('contratante_id', Auth::id())
            ->whereIn('estado', ['aceptado', 'finalizado'])
            ->exists();
    }

    public function index($servicioId)
    {
        $calificaciones = Calificacion::with('usuario')
            ->where('servicio_id', $servicioId)
            ->latest()
            ->get();

        $total = $calificaciones->count();
        $promedio = $total ? round($calificaciones->avg('estrellas'), 1) : 0;

        $distribucion = [];
        for ($i = 5; $i >= 1; $i--) {
            $cantidad = $calificaciones->where('estrellas', $i)->count();
            $distribucion[$i] = [
                'cantidad' => $cantidad,
                'porcentaje' => $total ? round(($cantidad / $total) * 100) : 0,
            ];
        }

        $miCalificacion = Auth::check()
            ? $calificaciones->firstWhere('usuario_id', Auth::id())
            : null;

        return response()->json([
            'ok' => true,
            'promedio' => $promedio,
            'total' => $total,
            'distribucion' => $distribucion,
            'puedeCalificar' => $this->puedeCalificar($servicioId),
            'miCalificacion' => $miCalificacion ? [
                'estrellas' => $miCalificacion->estrellas,
                'comentario' => $miCalificacion->comentario,
            ] : null,
            'resenas' => $calificaciones->map(fn($c) => [
                'id' => $c->id,
                'usuario_id' => $c->usuario_id,
                'nombre' => $c->usuario->nombre ?? 'Usuario eliminado',
                'foto' => $c->usuario->foto_perfil ?? null,
                'estrellas' => $c->estrellas,
                'comentario' => $c->comentario,
                'fecha' => $c->created_at->diffForHumans(),
                'esMia' => $c->usuario_id === Auth::id(),
            ]),
        ]);
    }

    public function store(Request $request, $servicioId)
    {
        $servicio = Servicio::findOrFail($servicioId);

        if ($servicio->usuario_id === Auth::id()) {
            return response()->json(['ok' => false, 'error' => 'No puedes calificar tu propio servicio.'], 422);
        }

        if (!$this->puedeCalificar($servicioId)) {
            return response()->json(['ok' => false, 'error' => 'Solo puedes calificar servicios que hayas solicitado y que el trabajador haya aceptado.'], 422);
        }

        $request->validate([
            'estrellas' => 'required|integer|min:1|max:5',
            'comentario' => 'nullable|string|max:500',
        ]);

        Calificacion::updateOrCreate(
            ['servicio_id' => $servicioId, 'usuario_id' => Auth::id()],
            ['estrellas' => $request->input('estrellas'), 'comentario' => $request->input('comentario')]
        );

        return response()->json(['ok' => true]);
    }

    public function destroy($id)
    {
        $calificacion = Calificacion::where('usuario_id', Auth::id())->findOrFail($id);
        $calificacion->delete();

        return response()->json(['ok' => true]);
    }
}
