<?php

namespace App\Http\Controllers;

use App\Models\Comentario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UsuarioComentarioController extends Controller
{
    public function index($tipo, $id)
    {
        $this->validarTipo($tipo);

        $comentarios = Comentario::with('usuario.localidad')
            ->where('tipo_objeto', $tipo)
            ->where('objeto_id', $id)
            ->latest()
            ->get()
            ->map(function ($c) {
                $u = $c->usuario;
                return [
                    'id' => $c->id,
                    'contenido' => $c->contenido,
                    'usuario_id' => $c->usuario_id,
                    'nombre' => $u->nombre ?? 'Usuario eliminado',
                    'foto' => $u->foto_perfil ?? null,
                    'verificado' => $u && $u->verificacion_estado === 'aprobado',
                    'miembro_desde' => $u ? $u->created_at->translatedFormat('F Y') : null,
                    'localidad' => $u->localidad->nombre ?? null,
                    'descripcion' => $u->descripcion ?? null,
                    'servicios_count' => $u ? \App\Models\Servicio::where('usuario_id', $u->id)->count() : 0,
                    'fecha' => $c->created_at->diffForHumans(),
                    'esMio' => $c->usuario_id === Auth::id(),
                ];
            });

        return response()->json(['ok' => true, 'comentarios' => $comentarios]);
    }

    public function store(Request $request, $tipo, $id)
    {
        $this->validarTipo($tipo);

        $request->validate([
            'contenido' => 'required|string|max:500',
        ]);

        $comentario = Comentario::create([
            'usuario_id' => Auth::id(),
            'tipo_objeto' => $tipo,
            'objeto_id' => $id,
            'contenido' => $request->input('contenido'),
        ]);

        return response()->json([
            'ok' => true,
            'comentario' => [
                'id' => $comentario->id,
                'contenido' => $comentario->contenido,
                'nombre' => Auth::user()->nombre,
                'foto' => Auth::user()->foto_perfil,
                'fecha' => 'justo ahora',
                'esMio' => true,
            ],
        ]);
    }

    public function destroy($id)
    {
        $comentario = Comentario::where('usuario_id', Auth::id())->findOrFail($id);
        $comentario->delete();

        return response()->json(['ok' => true]);
    }

    public function update(Request $request, $id)
    {
        $comentario = Comentario::where('usuario_id', Auth::id())->findOrFail($id);

        $request->validate([
            'contenido' => 'required|string|max:500',
        ]);

        $comentario->contenido = $request->input('contenido');
        $comentario->save();

        return response()->json(['ok' => true, 'contenido' => $comentario->contenido]);
    }

    private function validarTipo($tipo)
    {
        if (!in_array($tipo, ['servicio', 'vacante'])) {
            abort(404);
        }
    }
}
