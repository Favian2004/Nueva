<?php

namespace App\Http\Requests;

use App\Http\Requests\Concerns\NormalizaPublicaciones;
use App\Models\Servicio;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * Valida el formulario de "Publicar empleo" / servicio (crear y editar).
 */
class GuardarServicioRequest extends FormRequest
{
    use NormalizaPublicaciones;

    private ?Servicio $anterior = null;
    private bool $anteriorCargada = false;

    public function authorize(): bool
    {
        // La ruta ya exige sesión; si el servicio que se edita no es tuyo,
        // el controlador responde 404 como siempre.
        return true;
    }

    // El servicio que se está editando (null cuando se crea uno nuevo).
    public function servicioActual(): ?Servicio
    {
        if (!$this->anteriorCargada) {
            $id = $this->route('id');
            $this->anterior = $id
                ? Servicio::where('usuario_id', $this->user()?->id)->find($id)
                : null;
            $this->anteriorCargada = true;
        }

        return $this->anterior;
    }

    // Si el servicio ya tiene contrataciones o calificaciones, la categoría
    // ya no se puede cambiar (y por eso tampoco se valida).
    private function categoriaBloqueada(): bool
    {
        $servicio = $this->servicioActual();

        return $servicio && ($servicio->contrataciones()->exists() || $servicio->calificaciones()->exists());
    }

    protected function prepareForValidation(): void
    {
        $limpio = [];

        foreach (['titulo', 'ubicacion'] as $campo) {
            $limpio[$campo] = $this->textoLinea($this->input($campo));
        }
        $limpio['descripcion'] = $this->textoLargo($this->input('descripcion'));

        foreach (['telefono', 'whatsapp'] as $campo) {
            $valor = $this->soloDigitos($this->input($campo));
            $limpio[$campo] = $valor === '' ? null : $valor;
        }

        // Precio: "$1,500.50" -> "1500.50"
        $precio = $this->input('precio');
        if (is_string($precio)) {
            $precio = str_replace(['$', ',', ' '], '', $precio);
            $limpio['precio'] = $precio === '' ? null : $precio;
        }

        $this->merge(array_filter($limpio, fn($v, $k) => $this->has($k) || $v !== null, ARRAY_FILTER_USE_BOTH));
    }

    public function rules(): array
    {
        $reglas = [
            'titulo' => $this->reglasTexto('Título', 3, 100),
            'descripcion' => $this->reglasTextoLargo('Descripción', 20, 2000),
            'ubicacion' => $this->reglasTexto('Ubicación', 3, 150),
            'telefono' => ['nullable', 'digits:10'],
            'whatsapp' => ['nullable', 'digits:10'],
            'precio' => ['required', 'numeric', 'min:1', 'max:999999.99', 'decimal:0,2'],
            'imagen' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
        ];

        if (!$this->categoriaBloqueada()) {
            $reglas['categoria_id'] = ['required', 'integer', 'exists:categorias,id'];
            $reglas['subcategoria_id'] = [
                'required', 'integer',
                Rule::exists('subcategorias', 'id')->where('categoria_id', $this->input('categoria_id')),
            ];
        }

        return $reglas;
    }

    public function attributes(): array
    {
        return [
            'titulo' => 'título',
            'categoria_id' => 'categoría',
            'subcategoria_id' => 'subcategoría',
            'descripcion' => 'descripción',
            'ubicacion' => 'ubicación',
            'telefono' => 'teléfono',
            'whatsapp' => 'WhatsApp',
            'precio' => 'precio',
            'imagen' => 'imagen',
        ];
    }

    public function messages(): array
    {
        return [
            'required' => 'Falta llenar «:attribute».',
            'string' => '«:attribute» debe ser texto.',
            'integer' => '«:attribute» no es válido.',
            'numeric' => '«:attribute» debe ser un número.',
            'min.string' => '«:attribute» necesita al menos :min caracteres.',
            'max.string' => '«:attribute» no puede tener más de :max caracteres.',
            'min.numeric' => '«:attribute» debe ser al menos $:min.',
            'max.numeric' => '«:attribute» no puede ser mayor a $:max.',
            'decimal' => '«:attribute» solo puede tener hasta 2 decimales.',
            'digits' => '«:attribute» debe tener exactamente :digits dígitos (solo números, sin espacios ni lada).',
            'exists' => 'Elige una opción válida en «:attribute».',
            'image' => 'El archivo de «:attribute» debe ser una imagen.',
            'mimes' => 'La imagen debe ser JPG, PNG o WEBP.',
            'max.file' => 'La imagen no puede pesar más de 4 MB.',
        ];
    }

    // Lo que se guarda, ya listo.
    public function datosParaGuardar(): array
    {
        $v = $this->validated();

        $datos = [
            'titulo' => $v['titulo'],
            'descripcion' => $v['descripcion'],
            'ubicacion' => $v['ubicacion'],
            'telefono' => $v['telefono'] ?? null,
            'whatsapp' => $v['whatsapp'] ?? null,
            'precio' => number_format((float) $v['precio'], 2, '.', ''),
        ];

        if (array_key_exists('categoria_id', $v)) {
            $datos['categoria_id'] = (int) $v['categoria_id'];
            $datos['subcategoria_id'] = (int) $v['subcategoria_id'];
        }

        return $datos;
    }
}
