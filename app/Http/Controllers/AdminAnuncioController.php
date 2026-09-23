<?php

namespace App\Http\Controllers;

use App\Models\Anuncio;
use App\Models\AnuncioImagen;
use App\Models\Municipio;
use App\Models\SolicitudAnuncio;
use Illuminate\Http\Request;

class AdminSolicitudAnuncioController extends Controller
{
    public function index(Request $request)
    {
        $estado = $request->input('estado');

        $solicitudes = SolicitudAnuncio::with('pagos')
            ->when($estado, fn($q) => $q->where('estado', $estado))
            ->orderByRaw("FIELD(estado, 'pagado', 'pendiente_pago', 'pago_rechazado', 'aprobado', 'rechazado', 'pendiente')")
            ->latest()
            ->get();

        $municipios = Municipio::orderBy('nombre')->get();

        $contadorPagadas = SolicitudAnuncio::where('estado', 'pagado')->count();

        return view('admin.solicitudes-anuncio', [
            'solicitudes' => $solicitudes,
            'municipios' => $municipios,
            'contadorPagadas' => $contadorPagadas,
            'estado' => $estado,
        ]);
    }

    // El paso final: convierte una solicitud PAGADA en un Anuncio real y visible.
    public function activar(Request $request, $id)
    {
        $solicitud = SolicitudAnuncio::where('estado', 'pagado')->findOrFail($id);

        $request->validate([
            'municipio_id' => 'required|exists:municipios,id',
            'posicion' => 'required|in:izquierda,derecha',
        ]);

        $anuncio = Anuncio::create([
            'municipio_id' => $request->input('municipio_id'),
            'posicion' => $request->input('posicion'),
            'orden' => (Anuncio::where('posicion', $request->input('posicion'))->max('orden') ?? 0) + 1,
            'estado' => 'activo',
            'link_externo' => $solicitud->link_externo,
            'eslogan' => $solicitud->eslogan,
        ]);

        // Si el negocio subió su propia imagen, la usamos como la primera del anuncio.
        if ($solicitud->imagen_negocio) {
            AnuncioImagen::create([
                'anuncio_id' => $anuncio->id,
                'imagen' => $solicitud->imagen_negocio,
                'orden' => 1,
            ]);
        }

        $solicitud->estado = 'aprobado';
        $solicitud->save();

        return response()->json(['ok' => true, 'anuncio_id' => $anuncio->id]);
    }

    public function rechazar(Request $request, $id)
    {
        $solicitud = SolicitudAnuncio::findOrFail($id);

        $solicitud->estado = 'rechazado';
        $solicitud->notas_admin = $request->input('motivo');
        $solicitud->save();

        return response()->json(['ok' => true]);
    }
}
