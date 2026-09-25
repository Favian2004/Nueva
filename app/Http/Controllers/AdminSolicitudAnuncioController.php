<?php

namespace App\Http\Controllers;

use App\Mail\AnuncioActivado;
use App\Models\Anuncio;
use App\Models\AnuncioImagen;
use App\Models\Municipio;
use App\Models\PagoAnuncio;
use App\Models\SolicitudAnuncio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class AdminSolicitudAnuncioController extends Controller
{
    const PRECIOS = [
        'basico' => 29.00,
        'mensual' => 49.00,
        'anual' => 490.00,
    ];

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

        $resultado = $this->activarSolicitudInterna($solicitud);

        if (!$resultado['ok']) {
            return response()->json(['error' => $resultado['error']], 422);
        }

        return response()->json(['ok' => true, 'espacio' => $resultado['anuncio']->orden]);
    }

    // Crea y activa un anuncio DIRECTO desde el panel de admin, sin pasar
    // por Mercado Pago — para negocios que pagaron de otra forma, o
    // anuncios internos de la plataforma.
    public function crearDirecto(Request $request)
    {
        $request->validate([
            'nombre_negocio' => 'required|string|max:150',
            'nombre_encargado' => 'required|string|max:150',
            'descripcion' => 'required|string|max:2000',
            'direccion' => 'required|string|max:255',
            'telefono' => 'required|string|max:20',
            'whatsapp' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:150',
            'link_externo' => 'nullable|url|max:255',
            'link_ubicacion' => 'nullable|url|max:500',
            'eslogan' => 'required_if:plan,mensual,anual|nullable|string|max:150',
            'plan' => 'required|in:basico,mensual,anual',
            'imagen_negocio' => 'required|image|max:4096',
        ]);

        $data = $request->only(['nombre_negocio', 'nombre_encargado', 'descripcion', 'direccion', 'telefono', 'whatsapp', 'email', 'link_externo', 'link_ubicacion', 'eslogan', 'plan']);
        $data['imagen_negocio'] = '/storage/' . $request->file('imagen_negocio')->store('solicitudes-anuncio', 'public');
        $data['estado'] = 'pagado'; // Directo a "pagado", como si Mercado Pago ya hubiera confirmado.

        $solicitud = SolicitudAnuncio::create($data);

        PagoAnuncio::create([
            'solicitud_anuncio_id' => $solicitud->id,
            'plan' => $solicitud->plan,
            'monto' => self::PRECIOS[$solicitud->plan],
            'moneda' => 'MXN',
            'estado' => 'aprobado',
            'mp_payment_id' => 'ADMIN-DIRECTO',
            'fecha_pago' => now(),
        ]);

        $resultado = $this->activarSolicitudInterna($solicitud);

        if (!$resultado['ok']) {
            return back()->withErrors(['crear_directo' => $resultado['error']])->withInput();
        }

        return back()->with('exito', '¡Anuncio de "' . $solicitud->nombre_negocio . '" creado y publicado directamente!');
    }

    public function rechazar(Request $request, $id)
    {
        $solicitud = SolicitudAnuncio::findOrFail($id);

        $solicitud->estado = 'rechazado';
        $solicitud->notas_admin = $request->input('motivo');
        $solicitud->save();

        return response()->json(['ok' => true]);
    }

    // Lógica compartida: convierte una solicitud (ya marcada "pagado") en
    // un Anuncio real visible en el sitio. La usan tanto activar() como
    // crearDirecto().
    private function activarSolicitudInterna(SolicitudAnuncio $solicitud)
    {
        if (!$solicitud->imagen_negocio) {
            return ['ok' => false, 'error' => 'Esta solicitud no tiene imagen.'];
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
            return ['ok' => false, 'error' => 'No hay espacios activos en la columna derecha.'];
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
        Log::info('Activar: a punto de intentar correo', [
            'solicitud_id' => $solicitud->id,
            'email' => $solicitud->email,
        ]);

        if ($solicitud->email) {
            try {
                Mail::to($solicitud->email)->send(new AnuncioActivado($solicitud, $fechaInicio, $fechaVencimiento));
                Log::info('Activar: correo enviado sin excepción', ['solicitud_id' => $solicitud->id]);
            } catch (\Exception $e) {
                Log::error('No se pudo enviar el correo de anuncio activado: ' . $e->getMessage(), [
                    'solicitud_id' => $solicitud->id,
                    'linea' => $e->getLine(),
                    'archivo' => $e->getFile(),
                ]);
            }
        } else {
            Log::info('Activar: la solicitud no tiene email, no se manda correo', ['solicitud_id' => $solicitud->id]);
        }

        return ['ok' => true, 'anuncio' => $anuncio];
    }
}
