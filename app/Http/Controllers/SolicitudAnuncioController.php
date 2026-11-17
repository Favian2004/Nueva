<?php

namespace App\Http\Controllers;

use App\Models\PagoAnuncio;
use App\Models\SolicitudAnuncio;
use App\Services\GeneradorImagenAnuncio;
use App\Services\ModeracionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
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

    public function store(Request $request, ModeracionService $moderacion, GeneradorImagenAnuncio $generador)
    {
        // Acciones de apoyo de la misma página (usan esta misma ruta, así no hay rutas nuevas):
        //  - moderar: revisa textos/imagen mientras la persona llena el formulario.
        //  - generar_imagen: crea la imagen del anuncio con IA.
        $accion = $request->input('accion');
        if ($accion === 'moderar') {
            return $this->moderarCampos($request, $moderacion);
        }
        if ($accion === 'generar_imagen') {
            return $this->generarImagen($request, $generador);
        }

        $request->validate($this->reglasFormulario(), $this->mensajesFormulario(), $this->nombresCampos());

        // Todo lo que la persona escribe pasa por el moderador (palabras prohibidas, texto sin sentido e IA).
        $textoARevisar = implode("\n", array_filter([
            $request->input('nombre_negocio'),
            $request->input('nombre_encargado'),
            $request->input('descripcion'),
            $request->input('direccion'),
            $request->input('eslogan'),
        ]));

        $revision = $moderacion->revisar($textoARevisar, $request->file('imagen_negocio'));
        if ($revision['flagged']) {
            return response()->json(['ok' => false, 'message' => $revision['mensaje']], 422);
        }

        // Los links no son texto libre: se revisan con la lista de palabras y la IA, sin el detector de "texto sin sentido".
        $links = implode("\n", array_filter([$request->input('link_externo'), $request->input('link_ubicacion')]));
        if ($links !== '') {
            $revisionLinks = $moderacion->revisar($links, null, false);
            if ($revisionLinks['flagged']) {
                return response()->json(['ok' => false, 'message' => str_replace('Tu publicación', 'Tu anuncio', $revisionLinks['mensaje'])], 422);
            }
        }

        // Imagen creada con IA: solo vale una que esta misma persona generó en esta sesión.
        $rutaIa = null;
        if (! $request->hasFile('imagen_negocio') && $request->filled('imagen_ia')) {
            $candidata = (string) $request->input('imagen_ia');
            $valida = preg_match('#^anuncios-ia/[0-9a-f-]{36}\.jpg$#', $candidata)
                && in_array($candidata, (array) session('anuncios_ia', []), true)
                && Storage::disk('public')->exists($candidata);
            if (! $valida) {
                return response()->json(['ok' => false, 'message' => 'La imagen creada con IA ya no está disponible. Genera otra o sube la tuya.'], 422);
            }
            $rutaIa = $candidata;
        }

        $data = $request->only(['nombre_negocio', 'nombre_encargado', 'descripcion', 'direccion', 'telefono', 'whatsapp', 'email', 'link_externo', 'link_ubicacion', 'eslogan', 'plan']);

        if ($request->hasFile('imagen_negocio')) {
            $data['imagen_negocio'] = '/storage/' . $request->file('imagen_negocio')->store('solicitudes-anuncio', 'public');
        } elseif ($rutaIa) {
            $definitiva = 'solicitudes-anuncio/' . basename($rutaIa);
            Storage::disk('public')->move($rutaIa, $definitiva);
            $data['imagen_negocio'] = '/storage/' . $definitiva;
            session(['anuncios_ia' => array_values(array_diff((array) session('anuncios_ia', []), [$rutaIa]))]);
        }

        $data['estado'] = 'pendiente_pago';

        $solicitud = SolicitudAnuncio::create($data);

        // En vez de mandarlo de regreso con un mensaje, lo mandamos directo
        // a pagar con Mercado Pago.
        return $this->irAPagar($solicitud);
    }

    /** Reglas de tipo y tamaño de cada campo del formulario de anuncio. */
    private function reglasFormulario(): array
    {
        $telefono = fn (string $etiqueta) => function ($attr, $valor, $fail) use ($etiqueta) {
            if (! preg_match('/^\d{10}$/', preg_replace('/[\s-]/', '', (string) $valor))) {
                $fail("$etiqueta debe tener exactamente 10 dígitos, solo números.");
            }
        };
        $persona = function ($attr, $valor, $fail) {
            $valor = (string) $valor;
            if (! preg_match('/^[\pL\pM\s.\'’-]+$/u', $valor)) {
                $fail('El nombre del encargado solo puede llevar letras (sin números ni símbolos).');
            } elseif (preg_match('/(\pL)\1{2,}/iu', $valor)) {
                $fail('El nombre del encargado no puede repetir la misma letra 3 veces seguidas.');
            }
        };
        $linkDeMapa = function ($attr, $valor, $fail) {
            $host = strtolower((string) parse_url((string) $valor, PHP_URL_HOST));
            $ruta = (string) parse_url((string) $valor, PHP_URL_PATH);
            $esGoogle = (bool) preg_match('/(^|\.)google\.[a-z.]+$/', $host);
            $ok = in_array($host, ['maps.app.goo.gl', 'goo.gl', 'g.co'], true)
                || ($esGoogle && (str_starts_with($host, 'maps.') || str_starts_with($ruta, '/maps')));
            if (! $ok) {
                $fail('El link de ubicación debe ser un enlace de Google Maps (usa "Compartir" > "Copiar enlace").');
            }
        };

        return [
            'nombre_negocio' => 'required|string|min:2|max:80',
            'nombre_encargado' => ['required', 'string', 'min:3', 'max:60', $persona],
            'descripcion' => 'required|string|min:10|max:600',
            'direccion' => 'required|string|min:5|max:150',
            'telefono' => ['required', 'string', 'max:14', $telefono('El teléfono')],
            'whatsapp' => ['nullable', 'string', 'max:14', $telefono('El WhatsApp')],
            'email' => 'nullable|email|max:100',
            'link_externo' => 'nullable|url|starts_with:http://,https://|max:255',
            'link_ubicacion' => ['nullable', 'url', 'starts_with:http://,https://', 'max:500', $linkDeMapa],
            'eslogan' => 'required_if:plan,mensual,anual|nullable|string|max:80',
            'plan' => 'required|in:basico,mensual,anual',
            'imagen_negocio' => 'required_without:imagen_ia|nullable|image|max:4096',
            'imagen_ia' => 'nullable|string|max:100',
        ];
    }

    private function mensajesFormulario(): array
    {
        return [
            'imagen_negocio.required_without' => 'Sube la imagen de tu anuncio o créala con IA.',
            'imagen_negocio.image' => 'El archivo debe ser una imagen (JPG, PNG o WebP).',
            'imagen_negocio.max' => 'La imagen pesa demasiado, el máximo es 4 MB.',
            'link_externo.url' => 'El link a tu página o red social debe ser una dirección válida, por ejemplo https://facebook.com/tu-negocio.',
            'link_externo.starts_with' => 'El link a tu página o red social debe empezar con https://',
            'link_ubicacion.url' => 'El link de ubicación debe ser una dirección válida de Google Maps.',
            'link_ubicacion.starts_with' => 'El link de ubicación debe empezar con https://',
            'email.email' => 'Escribe un correo válido, por ejemplo nombre@correo.com.',
            'eslogan.required_if' => 'Escribe el eslogan que aparecerá debajo de tu imagen.',
            'max' => 'El campo «:attribute» es demasiado largo (máximo :max caracteres). Acórtalo para continuar.',
            'min' => 'El campo «:attribute» es demasiado corto. Revisa lo que escribiste.',
            'required' => 'Falta llenar: :attribute.',
        ];
    }

    private function nombresCampos(): array
    {
        return [
            'nombre_negocio' => 'nombre del negocio', 'nombre_encargado' => 'nombre del encargado', 'descripcion' => 'descripción',
            'direccion' => 'dirección', 'telefono' => 'teléfono', 'whatsapp' => 'WhatsApp', 'email' => 'correo',
            'link_externo' => 'link a tu página', 'link_ubicacion' => 'link de ubicación', 'eslogan' => 'eslogan', 'plan' => 'plan',
        ];
    }

    /** Revisa con el moderador los textos (y la imagen) mientras se llena el formulario. No guarda nada. */
    private function moderarCampos(Request $request, ModeracionService $moderacion)
    {
        $clave = 'anuncio-moderar:' . $request->ip();
        if (RateLimiter::tooManyAttempts($clave, 90)) {
            return response()->json(['ok' => false, 'message' => 'Demasiadas revisiones seguidas. Espera un momento.'], 429);
        }
        RateLimiter::hit($clave, 60);

        $request->validate([
            'textos' => 'nullable|array|max:10',
            'textos.*' => 'nullable|string|max:2000',
            'imagen_negocio' => 'nullable|image|max:4096',
        ], [
            'imagen_negocio.image' => 'El archivo debe ser una imagen (JPG, PNG o WebP).',
            'imagen_negocio.max' => 'La imagen pesa demasiado, el máximo es 4 MB.',
        ]);

        $texto = collect((array) $request->input('textos', []))->filter(fn ($v) => is_string($v) && trim($v) !== '')->implode("\n");
        $revision = $moderacion->revisar($texto, $request->file('imagen_negocio'));
        if (! empty($revision['flagged'])) {
            return response()->json(['ok' => false, 'message' => str_replace('Tu publicación', 'Tu anuncio', (string) $revision['mensaje'])], 422);
        }

        return response()->json(['ok' => true]);
    }

    /** Crea la imagen del anuncio con IA (máximo 3 intentos por sesión y 10 por IP cada día). */
    private function generarImagen(Request $request, GeneradorImagenAnuncio $generador)
    {
        $request->validate([
            'descripcion_ia' => 'required|string|min:10|max:300',
            'estilo' => 'nullable|in:' . implode(',', array_keys(GeneradorImagenAnuncio::ESTILOS)),
            'imagen_referencia' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:4096',
        ], [
            'imagen_referencia.image' => 'La foto de referencia debe ser una imagen (JPG, PNG o WebP).',
            'imagen_referencia.mimes' => 'La foto de referencia debe ser JPG, PNG o WebP.',
            'imagen_referencia.max' => 'La foto de referencia pesa demasiado, el máximo es 4 MB.',
            'descripcion_ia.required' => 'Cuéntale a la IA qué vendes o qué hace tu negocio.',
            'descripcion_ia.min' => 'Describe un poco más tu negocio (mínimo 10 letras).',
            'descripcion_ia.max' => 'La descripción es muy larga (máximo 300 caracteres).',
        ]);

        $porSesion = 'anuncio-ia-s:' . $request->session()->getId();
        $porIp = 'anuncio-ia-ip:' . $request->ip();
        if (RateLimiter::tooManyAttempts($porSesion, 3) || RateLimiter::tooManyAttempts($porIp, 10)) {
            return response()->json(['ok' => false, 'message' => 'Ya usaste tus 3 intentos de hoy para crear imágenes con IA. Sube tu propia imagen o vuelve mañana.', 'restantes' => 0], 429);
        }
        RateLimiter::hit($porSesion, 86400);
        RateLimiter::hit($porIp, 86400);
        $restantes = RateLimiter::remaining($porSesion, 3);

        $resultado = $generador->generar((string) $request->input('descripcion_ia'), (string) $request->input('estilo', 'fotografia'), $request->file('imagen_referencia'));
        if (! $resultado['ok']) {
            return response()->json(['ok' => false, 'message' => $resultado['error'], 'restantes' => $restantes], 422);
        }

        // Solo se podrá usar al enviar la solicitud si esta sesión la generó.
        $lista = (array) session('anuncios_ia', []);
        $lista[] = $resultado['ruta'];
        session(['anuncios_ia' => array_slice($lista, -6)]);

        return response()->json(['ok' => true, 'ruta' => $resultado['ruta'], 'url' => $resultado['url'], 'restantes' => $restantes]);
    }

    // ===== Renovación: el negocio vuelve a pagar el mismo plan =====
    // Se entra con un enlace firmado (lo genera el admin desde "Pagos
    // anuncios"). Primero se muestra una página de confirmación (así, si el
    // enlace se pega en WhatsApp y la app lo "previsualiza", no se crea nada).
    public function mostrarRenovacion($id)
    {
        $solicitud = SolicitudAnuncio::with('pagos')->where('estado', 'aprobado')->findOrFail($id);
        $pago = $solicitud->pagos->where('estado', 'aprobado')->sortByDesc('id')->first();

        return view('anunciar-renovar', [
            'solicitud' => $solicitud,
            'monto' => self::PRECIOS[$solicitud->plan],
            'vence' => $pago?->fecha_vencimiento_anuncio,
            'urlPagar' => URL::temporarySignedRoute('anunciar.renovar.pagar', now()->addHours(2), ['id' => $solicitud->id]),
        ]);
    }

    // Al confirmar: crea una solicitud nueva con los mismos datos e imagen y
    // manda a Mercado Pago. Después sigue el flujo normal: el webhook la marca
    // "pagado" y el admin la activa (ahí se calculan las fechas nuevas).
    public function pagarRenovacion($id)
    {
        $original = SolicitudAnuncio::where('estado', 'aprobado')->findOrFail($id);

        $nueva = $original->replicate(['notas_admin', 'comprobante_pago']);
        $nueva->estado = 'pendiente_pago';
        $nueva->save();

        try {
            $datos = $this->irAPagar($nueva)->getData(true);
        } catch (\Throwable $e) {
            Log::error('Renovación: falló al crear el pago en Mercado Pago: ' . $e->getMessage(), ['solicitud_id' => $nueva->id]);
            $datos = [];
        }

        if (!empty($datos['ok']) && !empty($datos['redirectUrl'])) {
            return redirect()->away($datos['redirectUrl']);
        }

        $nueva->delete();

        return view('anuncio-pago-resultado', [
            'tipo' => 'fallo',
            'mensaje' => 'No pudimos conectar con Mercado Pago. Intenta de nuevo en unos minutos.',
        ]);
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

            return response()->json([
                'ok' => false,
                'message' => 'No se pudo conectar con Mercado Pago. Código: ' . $e->getApiResponse()?->getStatusCode() . ' — Revisa storage/logs/laravel.log para más detalle.',
            ], 500);
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
        return response()->json(['ok' => true, 'redirectUrl' => $preference->init_point]);
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
