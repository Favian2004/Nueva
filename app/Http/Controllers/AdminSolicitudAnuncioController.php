<?php

namespace App\Http\Controllers;

use App\Mail\AnuncioActivado;
use App\Models\Anuncio;
use App\Models\AnuncioImagen;
use App\Models\Municipio;
use App\Models\PagoAnuncio;
use App\Models\SolicitudAnuncio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

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
            'link_ubicacion' => $solicitud->link_ubicacion,
            'solicitud_anuncio_id' => $solicitud->id,
        ]);

        // Las fechas se calculan AQUÍ (al activar), no al momento del pago —
        // así el negocio siempre recibe sus días completos, sin importar
        // cuánto tarde la revisión.
        $fechaInicio = now()->toDateString();
        $fechaVencimiento = match ($solicitud->plan) {
            'anual' => now()->addYear()->toDateString(),
            'basico' => now()->addDays(15)->toDateString(),
            default => now()->addMonth()->toDateString(),
        };

        $pago = PagoAnuncio::where('solicitud_anuncio_id', $solicitud->id)
            ->where('estado', 'aprobado')
            ->latest()
            ->first();

        if ($pago) {
            $pago->fecha_inicio_anuncio = $fechaInicio;
            $pago->fecha_vencimiento_anuncio = $fechaVencimiento;
            $pago->save();
        }

        $solicitud->estado = 'aprobado';
        $solicitud->save();

        // Avisa por correo al negocio, si dejó uno.
        \Illuminate\Support\Facades\Log::info('Activar: a punto de intentar correo', [
            'solicitud_id' => $solicitud->id,
            'email' => $solicitud->email,
        ]);

        if ($solicitud->email) {
            try {
                Mail::to($solicitud->email)->send(new AnuncioActivado($solicitud, $fechaInicio, $fechaVencimiento));
                \Illuminate\Support\Facades\Log::info('Activar: correo enviado sin excepción', ['solicitud_id' => $solicitud->id]);
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::error('No se pudo enviar el correo de anuncio activado: ' . $e->getMessage(), [
                    'solicitud_id' => $solicitud->id,
                    'linea' => $e->getLine(),
                    'archivo' => $e->getFile(),
                ]);
            }
        } else {
            \Illuminate\Support\Facades\Log::info('Activar: la solicitud no tiene email, no se manda correo', ['solicitud_id' => $solicitud->id]);
        }

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
