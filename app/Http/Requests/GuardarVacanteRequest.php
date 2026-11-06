<?php

namespace App\Http\Requests;

use App\Http\Requests\Concerns\NormalizaPublicaciones;
use App\Models\Vacante;
use Carbon\Carbon;
use Closure;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * Valida el formulario de "Publicar vacante" (crear y editar).
 *
 * Es la misma revisión que hace el formulario en pantalla, pero aquí en el
 * servidor, que es la que de verdad cuenta (en pantalla alguien podría
 * saltársela).
 */
class GuardarVacanteRequest extends FormRequest
{
    use NormalizaPublicaciones;

    public const TIPOS_PAGO = ['Pago al día', 'Pago por destajo', 'Pago semanal', 'Pago quincenal', 'Pago mensual'];
    public const EXPERIENCIAS = ['Sin experiencia', '6 meses mínimo', '1 año mínimo', '2 años mínimo', '3 años mínimo', '5 años mínimo'];
    public const CONTRATOS = ['Temporal', 'Temporada', 'Por obra', 'Fijo', 'Eventual'];
    public const BENEFICIOS = [
        'Comida incluida', 'Alojamiento', 'Transporte incluido', 'Equipo de seguridad',
        'Uniforme', 'Bono por rendimiento', 'Herramientas proporcionadas', 'Pago puntual',
    ];
    public const UNIDADES_DURACION = ['día', 'días', 'semana', 'semanas', 'mes', 'meses', 'año', 'años'];

    private ?Vacante $anterior = null;
    private bool $anteriorCargada = false;

    public function authorize(): bool
    {
        // La ruta ya exige haber iniciado sesión; y si la vacante que se edita
        // no es tuya, el controlador responde 404 como siempre.
        return true;
    }

    // La vacante que se está editando (null cuando se está creando una nueva).
    public function vacanteActual(): ?Vacante
    {
        if (!$this->anteriorCargada) {
            $id = $this->route('id');
            $this->anterior = $id
                ? Vacante::where('empleador_id', $this->user()?->id)->find($id)
                : null;
            $this->anteriorCargada = true;
        }

        return $this->anterior;
    }

    protected function prepareForValidation(): void
    {
        $limpio = [];

        foreach (['titulo', 'publicante', 'ubicacion'] as $campo) {
            $limpio[$campo] = $this->textoLinea($this->input($campo));
        }
        $limpio['descripcion'] = $this->textoLargo($this->input('descripcion'));

        foreach (['telefono', 'whatsapp'] as $campo) {
            $valor = $this->soloDigitos($this->input($campo));
            $limpio[$campo] = $valor === '' ? null : $valor;
        }

        // Salario: "$1,500" -> "1500" (solo el monto; si queda vacío es "A convenir").
        $salario = $this->input('salario');
        if (is_string($salario)) {
            $salario = str_replace(['$', ',', ' '], '', $salario);
            $limpio['salario'] = $salario === '' ? null : $salario;
        }

        // Fechas: acepta aaaa-mm-dd (calendario) y también dd/mm/aaaa.
        foreach (['fecha_trabajo', 'fecha_limite'] as $campo) {
            $valor = $this->input($campo);
            if (is_string($valor)) {
                $valor = trim($valor);
                if (preg_match('#^(\d{2})/(\d{2})/(\d{4})$#', $valor, $m)) {
                    $valor = "{$m[3]}-{$m[2]}-{$m[1]}";
                }
                $limpio[$campo] = $valor === '' ? null : $valor;
            }
        }

        $duracion = $this->input('duracion');
        if (is_string($duracion)) {
            $duracion = trim(preg_replace('/\s+/u', ' ', $duracion));
            $limpio['duracion'] = $duracion === '' ? null : $duracion;
        }

        $this->merge(array_filter($limpio, fn($v, $k) => $this->has($k) || $v !== null, ARRAY_FILTER_USE_BOTH));
    }

    public function rules(): array
    {
        $anterior = $this->vacanteActual();
        $hoy = today()->toDateString();
        $tope = today()->addYears(2)->toDateString();

        // ¿Cambió la fecha respecto a lo que ya estaba guardado? Si no cambió,
        // no se exige que sea futura (para poder editar otros datos de una
        // vacante que ya venció sin tener que mover sus fechas).
        $trabajoCambio = !$anterior || $this->input('fecha_trabajo') !== $anterior->fecha_trabajo_iso;
        $limiteCambio = !$anterior || $this->input('fecha_limite') !== $anterior->fecha_limite?->toDateString();
        $duracionCambio = !$anterior || $this->input('duracion') !== $anterior->duracion;

        $reglas = [
            'categoria_id' => ['required', 'integer', 'exists:categorias,id'],
            'subcategoria_id' => [
                'required', 'integer',
                Rule::exists('subcategorias', 'id')->where('categoria_id', $this->input('categoria_id')),
            ],
            'publicante' => $this->reglasTexto('Nombre del publicante', 3, 100),
            'ubicacion' => $this->reglasTexto('Ubicación', 3, 150),
            'trabajadores_requeridos' => ['required', 'integer', 'between:1,100'],
            'tipo_pago' => ['required', Rule::in(self::TIPOS_PAGO)],
            'salario' => ['nullable', 'numeric', 'min:1', 'max:1000000', 'decimal:0,2'],
            'experiencia' => ['required', Rule::in(self::EXPERIENCIAS)],
            'contrato' => ['required', Rule::in(self::CONTRATOS)],

            'fecha_trabajo' => [
                'bail', 'required', 'date_format:Y-m-d',
                function ($atributo, $valor, Closure $fail) use ($trabajoCambio, $hoy, $tope) {
                    if (!$trabajoCambio) {
                        return;
                    }
                    if ($valor <= $hoy) {
                        $fail('El día del trabajo tiene que ser a partir de mañana.');
                    } elseif ($valor > $tope) {
                        $fail('El día del trabajo no puede ser tan lejano (máximo 2 años).');
                    }
                },
            ],

            'fecha_limite' => [
                'bail', 'nullable', 'date_format:Y-m-d',
                function ($atributo, $valor, Closure $fail) use ($limiteCambio, $trabajoCambio, $hoy, $tope) {
                    if ($limiteCambio && $valor <= $hoy) {
                        $fail('El límite para postular tiene que ser a partir de mañana.');
                        return;
                    }
                    if ($limiteCambio && $valor > $tope) {
                        $fail('El límite para postular no puede ser tan lejano (máximo 2 años).');
                        return;
                    }
                    $trabajo = $this->input('fecha_trabajo');
                    $trabajoValido = is_string($trabajo) && preg_match('/^\d{4}-\d{2}-\d{2}$/', $trabajo);
                    if (($limiteCambio || $trabajoCambio) && $trabajoValido && $valor > $trabajo) {
                        $fail('El límite para postular no puede ser después del día del trabajo.');
                    }
                },
            ],

            'duracion' => [
                'nullable', 'string',
                function ($atributo, $valor, Closure $fail) use ($duracionCambio) {
                    if (!$duracionCambio) {
                        return;
                    }
                    $unidades = implode('|', self::UNIDADES_DURACION);
                    if (!preg_match("/^(\\d{1,2}) ({$unidades})$/u", $valor, $m)) {
                        $fail('Elige la duración con los dos selectores (el número y si son días, semanas, meses o años).');
                        return;
                    }
                    $numero = (int) $m[1];
                    if ($numero < 1 || $numero > 31) {
                        $fail('La duración debe ser un número del 1 al 31.');
                        return;
                    }
                    $singular = in_array($m[2], ['día', 'semana', 'mes', 'año'], true);
                    if (($numero === 1) !== $singular) {
                        $fail('La unidad no coincide con el número (por ejemplo: «1 día» o «2 días»).');
                    }
                },
            ],

            'telefono' => ['required', 'digits:10'],
            'whatsapp' => ['nullable', 'digits:10'],
            'imagen' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'beneficios' => ['nullable', 'array', 'max:' . count(self::BENEFICIOS)],
            'beneficios.*' => ['string', Rule::in(self::BENEFICIOS)],
            'requiere_cv' => ['nullable', 'boolean'],
            'requiere_solicitud_empleo' => ['nullable', 'boolean'],
        ];

        // Título y descripción: si la vacante ya tiene postulaciones ya no se
        // pueden cambiar, así que tampoco se validan.
        $bloqueada = $anterior && $anterior->postulaciones()->exists();
        if (!$bloqueada) {
            $reglas['titulo'] = $this->reglasTexto('Título', 3, 100);
            $reglas['descripcion'] = $this->reglasTextoLargo('Descripción', 20, 2000);
        }

        return $reglas;
    }

    public function attributes(): array
    {
        return [
            'titulo' => 'título',
            'categoria_id' => 'categoría',
            'subcategoria_id' => 'subcategoría',
            'publicante' => 'nombre del publicante',
            'ubicacion' => 'ubicación',
            'trabajadores_requeridos' => 'número de trabajadores',
            'tipo_pago' => 'tipo de pago',
            'salario' => 'salario',
            'experiencia' => 'experiencia',
            'contrato' => 'tipo de contrato',
            'descripcion' => 'descripción',
            'fecha_trabajo' => 'día del trabajo',
            'fecha_limite' => 'límite para postular',
            'duracion' => 'duración del trabajo',
            'telefono' => 'teléfono',
            'whatsapp' => 'WhatsApp',
            'imagen' => 'imagen',
            'beneficios' => 'beneficios',
            'beneficios.*' => 'beneficio',
        ];
    }

    public function messages(): array
    {
        return [
            'required' => 'Falta llenar «:attribute».',
            'string' => '«:attribute» debe ser texto.',
            'integer' => '«:attribute» debe ser un número entero (sin decimales).',
            'numeric' => '«:attribute» debe ser un número.',
            'min.string' => '«:attribute» necesita al menos :min caracteres.',
            'max.string' => '«:attribute» no puede tener más de :max caracteres.',
            'min.numeric' => '«:attribute» debe ser al menos :min.',
            'max.numeric' => '«:attribute» no puede ser mayor a :max.',
            'between.numeric' => '«:attribute» debe estar entre :min y :max.',
            'decimal' => '«:attribute» solo puede tener hasta 2 decimales.',
            'digits' => '«:attribute» debe tener exactamente :digits dígitos (solo números, sin espacios ni lada).',
            'in' => 'Elige una opción válida en «:attribute».',
            'exists' => 'Elige una opción válida en «:attribute».',
            'date_format' => 'La fecha de «:attribute» no es válida.',
            'image' => 'El archivo de «:attribute» debe ser una imagen.',
            'mimes' => 'La imagen debe ser JPG, PNG o WEBP.',
            'max.file' => 'La imagen no puede pesar más de 4 MB.',
            'array' => '«:attribute» no es válido.',
            'boolean' => '«:attribute» no es válido.',
        ];
    }

    /**
     * Todo lo que se guarda en la base de datos, ya listo: fechas en
     * dd/mm/aaaa, salario con formato, duración "2 días", etc.
     */
    public function datosParaGuardar(): array
    {
        $v = $this->validated();

        $trabajo = Carbon::createFromFormat('!Y-m-d', $v['fecha_trabajo']);

        // Si no se pone límite para postular, el límite es el día del trabajo
        // (así la vacante no se queda abierta para siempre).
        // Si el día del trabajo ya pasó (vacante vieja que no se movió), se
        // conserva el límite que tenía; y si no tenía, el límite es ese día
        // pasado. Así una vacante vencida NO se vuelve a abrir sola por dejar
        // el límite en blanco.
        $limite = $v['fecha_limite'] ?? null;
        if (!$limite) {
            $limite = $trabajo->gt(today())
                ? $trabajo->toDateString()
                : ($this->vacanteActual()?->fecha_limite?->toDateString() ?? $trabajo->toDateString());
        }

        $salario = 'A convenir';
        if (isset($v['salario']) && $v['salario'] !== null && $v['salario'] !== '') {
            $monto = (float) $v['salario'];
            $salario = '$' . number_format($monto, fmod($monto, 1.0) === 0.0 ? 0 : 2);
        }

        $duracion = null;
        if (!empty($v['duracion']) && preg_match('/^(\d{1,2}) (.+)$/u', $v['duracion'], $m)) {
            $duracion = ((int) $m[1]) . ' ' . $m[2];
        } elseif (!empty($v['duracion']) && $v['duracion'] === $this->vacanteActual()?->duracion) {
            // Duración vieja escrita a mano que no se tocó: se respeta tal cual.
            $duracion = $v['duracion'];
        }

        $datos = [
            'categoria_id' => (int) $v['categoria_id'],
            'subcategoria_id' => (int) $v['subcategoria_id'],
            'publicante' => $v['publicante'],
            'ubicacion' => $v['ubicacion'],
            'trabajadores_requeridos' => (int) $v['trabajadores_requeridos'],
            'tipo_pago' => $v['tipo_pago'],
            'salario' => $salario,
            'experiencia' => $v['experiencia'],
            'contrato' => $v['contrato'],
            'beneficios' => array_values($v['beneficios'] ?? []),
            'requiere_cv' => $this->boolean('requiere_cv'),
            'requiere_solicitud_empleo' => $this->boolean('requiere_solicitud_empleo'),
            'fecha_trabajo' => $trabajo->format('d/m/Y'),
            'duracion' => $duracion,
            'fecha_limite' => $limite,
            'telefono' => $v['telefono'],
            'whatsapp' => $v['whatsapp'] ?? null,
        ];

        if (array_key_exists('titulo', $v)) {
            $datos['titulo'] = $v['titulo'];
        }
        if (array_key_exists('descripcion', $v)) {
            $datos['descripcion'] = $v['descripcion'];
        }

        return $datos;
    }
}
