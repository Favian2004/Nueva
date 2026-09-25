<?php

namespace App\Http\Controllers;

use App\Models\PagoAnuncio;
use App\Models\SolicitudAnuncio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use MercadoPago\Client\Payment\PaymentClient;
use MercadoPago\Client\Preference\PreferenceClient;
use MercadoPago\Exceptions\MPApiException;
use MercadoPago\MercadoPagoConfig;

class SolicitudAnuncioController extends Controller
{
    // Precios reales de tus planes (mismos que ya se muestran en /anunciar).
    const PRECIOS = [
        'basico' => 29.00,
        'mensual' => 49.00,
        'anual' => 490.00,
    ];

    public function create()
    {
        return view('anunciar');
    }

    public function store(Request $request)
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
            'imagen_negocio' => 'required_if:plan,basico|nullable|image|max:4096',
        ]);

        $data = $request->only(['nombre_negocio', 'nombre_encargado', 'descripcion', 'direccion', 'telefono', 'whatsapp', 'email', 'link_externo', 'link_ubicacion', 'eslogan', 'plan']);

        if ($request->hasFile('imagen_negocio')) {
            $data['imagen_negocio'] = '/storage/' . $request->file('imagen_negocio')->store('solicitudes-anuncio', 'public');
        }

        $data['estado'] = 'pendiente_pago';

        $solicitud = SolicitudAnuncio::create($data);

        // En vez de mandarlo de regreso con un mensaje, lo mandamos directo
        // a pagar con Mercado Pago.
        return $this->irAPagar($solicitud);
    }

    // Crea la preferencia de pago en Mercado Pago y redirige ahí.
    protected function irAPagar(SolicitudAnuncio $solicitud)
    {
        MercadoPagoConfig::setAccessToken(config('services.mercadopago.access_token'));

        // Necesario para poder probar en localhost / 127.0.0.1 — en producción
        // (Hostinger) esta línea no hace daño, simplemente no aplica.
        if (app()->environment('local')) {
            MercadoPagoConfig::setRuntimeEnviroment(MercadoPagoConfig::LOCAL);
        }

        $monto = self::PRECIOS[$solicitud->plan];

        $client = new PreferenceClient();

        // Nombres bonitos para mostrar en Mercado Pago (con acentos correctos)
        $nombresPlan = ['basico' => 'Básico', 'mensual' => 'Mensual', 'anual' => 'Anual'];

        $datosPreferencia = [
            'items' => [
                [
                    'title' => 'Anuncio en ¡SINTECZATE! - Plan ' . $nombresPlan[$solicitud->plan],
                    'quantity' => 1,
                    'unit_price' => $monto,
                    'currency_id' => 'MXN',
                ],
            ],
            'back_urls' => [
                'success' => route('anunciar.pago.exito'),
                'failure' => route('anunciar.pago.fallo'),
                'pending' => route('anunciar.pago.pendiente'),
            ],
            'external_reference' => (string) $solicitud->id,
            // Solo aceptamos tarjeta (débito/crédito) — excluimos OXXO,
            // transferencia SPEI y otros métodos que dejan el pago en
            // estado "pendiente" por horas/días.
            'payment_methods' => [
                'excluded_payment_types' => [
                    ['id' => 'ticket'],         // OXXO y similares
                    ['id' => 'bank_transfer'],  // SPEI
                    ['id' => 'atm'],
                ],
            ],
        ];

        // Mercado Pago exige que back_urls.success y notification_url sean
        // URLs públicas y válidas (https, dominio real) para usar auto_return
        // y para poder llamar al webhook. En local (http://127.0.0.1:8000)
        // las rechaza, así que solo las activamos cuando ya son https
        // (Hostinger, o local con ngrok).
        $urlWebhook = route('anunciar.webhook');

        if (str_starts_with($urlWebhook, 'https://')) {
            $datosPreferencia['notification_url'] = $urlWebhook;
        }

        if (str_starts_with($datosPreferencia['back_urls']['success'], 'https://')) {
            $datosPreferencia['auto_return'] = 'approved';
        }

        try {
            $preference = $client->create($datosPreferencia);
        } catch (MPApiException $e) {
            Log::error('Error al crear preferencia en Mercado Pago', [
                'status' => $e->getApiResponse()?->getStatusCode(),
                'content' => $e->getApiResponse()?->getContent(),
            ]);

            return back()->withErrors(['mercadopago' => 'No se pudo conectar con Mercado Pago. Código: ' . $e->getApiResponse()?->getStatusCode() . ' — Revisa storage/logs/laravel.log para más detalle.']);
        }

        // Guarda el intento de pago, en estado "pendiente" hasta que el
        // webhook confirme qué pasó de verdad.
        PagoAnuncio::create([
            'solicitud_anuncio_id' => $solicitud->id,
            'plan' => $solicitud->plan,
            'monto' => $monto,
            'moneda' => 'MXN',
            'estado' => 'pendiente',
            'mp_preference_id' => $preference->id,
        ]);

        // Mercado Pago ya no distingue "sandbox_init_point" de forma
        // confiable con credenciales de prueba (usar sandbox_init_point con
        // credenciales de prueba es justo lo que provoca el error "Oh, no,
        // algo anduvo mal"). Siempre usamos init_point: con las credenciales
        // de una Cuenta de Prueba vendedora, ese init_point ya es un
        // checkout de prueba (los pagos son ficticios), y hay que loguearse
        // ahí con la Cuenta de Prueba compradora para pagar con tarjeta de
        // prueba.
        return redirect($preference->init_point);
    }

    // ===== Páginas a las que Mercado Pago regresa al usuario =====
    // OJO: estas páginas son solo informativas. NUNCA activan nada por sí
    // solas — la activación real solo pasa cuando llega el webhook.

    public function pagoExito(Request $request)
    {
        return view('anuncio-pago-resultado', [
            'tipo' => 'exito',
            'mensaje' => '¡Gracias! Tu pago está siendo confirmado. En cuanto se verifique, tu anuncio quedará listo para su revisión final.',
        ]);
    }

    public function pagoFallo(Request $request)
    {
        return view('anuncio-pago-resultado', [
            'tipo' => 'fallo',
            'mensaje' => 'Tu pago no se pudo completar. Puedes intentarlo de nuevo desde el formulario de "Anúnciate aquí".',
        ]);
    }

    public function pagoPendiente(Request $request)
    {
        return view('anuncio-pago-resultado', [
            'tipo' => 'pendiente',
            'mensaje' => 'Tu pago está pendiente de confirmación (por ejemplo, si pagaste con OXXO o transferencia). Te avisaremos por correo en cuanto se confirme.',
        ]);
    }

    // ===== Webhook: aquí es donde REALMENTE se confirma el pago =====
    public function webhook(Request $request)
    {
        Log::info('Webhook Mercado Pago recibido', $request->all());

        // Mercado Pago manda el aviso de 2 formas posibles: por query string
        // (?type=payment&data.id=123) o por el cuerpo JSON. Cubrimos ambas.
        $tipo = $request->input('type') ?? $request->input('topic');
        $paymentId = $request->input('data.id') ?? $request->query('id') ?? $request->input('id');

        if ($tipo !== 'payment' || !$paymentId) {
            return response()->json(['ok' => true]); // Ignoramos otros tipos de eventos
        }

        MercadoPagoConfig::setAccessToken(config('services.mercadopago.access_token'));

        try {
            $client = new PaymentClient();
            $payment = $client->get($paymentId);
        } catch (\Exception $e) {
            Log::error('Error al consultar el pago en Mercado Pago: ' . $e->getMessage());
            return response()->json(['ok' => false], 500);
        }

        $solicitudId = $payment->external_reference;
        $solicitud = SolicitudAnuncio::find($solicitudId);

        if (!$solicitud) {
            Log::warning('Webhook de Mercado Pago: no se encontró la solicitud ' . $solicitudId);
            return response()->json(['ok' => true]);
        }

        $pago = PagoAnuncio::where('solicitud_anuncio_id', $solicitud->id)
            ->where('estado', 'pendiente')
            ->latest()
            ->first();

        if (!$pago) {
            Log::warning('Webhook de Mercado Pago: no se encontró el pago pendiente para la solicitud ' . $solicitud->id);
            return response()->json(['ok' => true]);
        }

        $pago->mp_payment_id = $payment->id;

        if ($payment->status === 'approved') {
            $pago->estado = 'aprobado';
            $pago->fecha_pago = now();
            // Las fechas de inicio/vencimiento del anuncio YA NO se calculan
            // aquí — se calculan cuando el admin lo activa (ver
            // AdminSolicitudAnuncioController::activar), para que el negocio
            // reciba sus días completos sin importar cuánto tarde la revisión.
            $pago->save();

            $solicitud->estado = 'pagado';
            $solicitud->save();
        } elseif ($payment->status === 'rejected') {
            $pago->estado = 'rechazado';
            $pago->save();

            $solicitud->estado = 'pago_rechazado';
            $solicitud->save();
        } else {
            // pending, in_process, etc.
            $pago->save();
        }

        return response()->json(['ok' => true]);
    }
}
