<?php

namespace App\Http\Controllers;

use App\Models\Reporte;
use App\Models\Servicio;
use App\Models\Vacante;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UsuarioReporteController extends Controller
{
    public function store(Request $request, $tipo, $id)
    {
        if (!in_array($tipo, ['servicio', 'vacante'])) {
            abort(404);
        }

        $request->validate([
            'motivo' => 'required|string|max:150',
            'descripcion' => 'nullable|string|max:1000',
        ]);

        if ($tipo === 'servicio') {
            $objeto = Servicio::findOrFail($id);
            $usuarioReportadoId = $objeto->usuario_id;
        } else {
            $objeto = Vacante::findOrFail($id);
            $usuarioReportadoId = $objeto->empleador_id;
        }

        if ($usuarioReportadoId === Auth::id()) {
            return response()->json(['ok' => false, 'error' => 'No puedes reportar tu propia publicación.'], 422);
        }

        $yaReportado = Reporte::where('usuario_reporta_id', Auth::id())
            ->where('tipo_objeto', $tipo)
            ->where('objeto_id', $id)
            ->exists();

        if ($yaReportado) {
            return response()->json(['ok' => false, 'error' => 'Ya reportaste esta publicación anteriormente.'], 422);
        }

        Reporte::create([
            'usuario_reporta_id' => Auth::id(),
            'usuario_reportado_id' => $usuarioReportadoId,
            'tipo_objeto' => $tipo,
            'objeto_id' => $id,
            'motivo' => $request->input('motivo'),
            'descripcion' => $request->input('descripcion'),
            'estado' => 'pendiente',
        ]);

        return response()->json(['ok' => true]);
    }
}
