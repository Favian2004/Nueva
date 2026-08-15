<?php

namespace App\Http\Controllers;

use App\Models\Reporte;
use App\Models\Usuario;
use Illuminate\Http\Request;

class AdminReporteController extends Controller
{
    public function index()
    {
        $reportes = Reporte::with(['usuarioReporta', 'usuarioReportado'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.reportes', [
            'reportes' => $reportes,
        ]);
    }

    public function cambiarEstado(Request $request, $id)
    {
        $request->validate([
            'estado' => 'required|in:pendiente,revisado,resuelto,descartado',
        ]);

        $reporte = Reporte::findOrFail($id);
        $reporte->estado = $request->input('estado');
        $reporte->save();

        return response()->json(['ok' => true]);
    }

    public function suspenderUsuario($id)
    {
        $reporte = Reporte::findOrFail($id);

        if (!$reporte->usuario_reportado_id) {
            return response()->json(['ok' => false, 'error' => 'Este reporte no está asociado a un usuario.'], 422);
        }

        $usuario = Usuario::findOrFail($reporte->usuario_reportado_id);
        $usuario->estado = 'suspendido';
        $usuario->motivo_suspension = 'Suspendido por reporte: ' . $reporte->motivo;
        $usuario->save();

        $reporte->estado = 'resuelto';
        $reporte->save();

        return response()->json(['ok' => true, 'usuario_nombre' => $usuario->nombre]);
    }
}
