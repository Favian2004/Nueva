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

        if (!$solicitud->imagen_negocio) {
            return response()->json(['error' => 'Esta solicitud no tiene imagen.'], 422);
        }

        // Rotación: el espacio con menos anuncios pagados; si empatan, el de menor orden.
        $anuncio = Anuncio::where('posicion', 'derecha')
            ->where('estado', 'activo')
            ->withCount(['imagenes as pagadas_count' => function ($q) {
                $q->whereNotNull('solicitud_anuncio_id');
            }])
            ->orderBy('pagadas_count')
            ->orderBy('orden')
            ->first();

        if (!$anuncio) {
            return response()->json(['error' => 'No hay espacios activos en la columna derecha.'], 422);
        }

        AnuncioImagen::create([
            'anuncio_id' => $anuncio->id,
            'imagen' => $solicitud->imagen_negocio,
            'orden' => (AnuncioImagen::where('anuncio_id', $anuncio->id)->max('orden') ?? 0) + 1,
            'eslogan' => $solicitud->eslogan,
            'link_externo' => $solicitud->link_externo,
            'solicitud_anuncio_id' => $solicitud->id,
        ]);

        $solicitud->estado = 'aprobado';
        $solicitud->save();

        return response()->json(['ok' => true, 'espacio' => $anuncio->orden]);
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
