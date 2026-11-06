<?php

namespace App\Http\Controllers;

use App\Http\Requests\GuardarVacanteRequest;
use App\Models\Categoria;
use App\Models\Postulacion;
use App\Models\Vacante;
use App\Services\ModeracionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UsuarioVacanteController extends Controller
{
    // Categorías con sus subcategorías, para los selects del formulario.
    private function categoriasConSubcategorias()
    {
        return Categoria::with('subcategorias:id,categoria_id,nombre')
            ->orderBy('nombre')
            ->get(['id', 'nombre']);
    }

    public function create()
    {
        $usuario = Auth::user();

        return view('usuario.publicar-vacante', [
            'usuario' => $usuario,
            'vacante' => null,
            'tituloDescripcionBloqueada' => false,
            'categorias' => $this->categoriasConSubcategorias(),
        ]);
    }

    // Página completa con toda la información de UNA vacante.
    public function show($id)
    {
        $vacante = Vacante::withCount('postulaciones')->findOrFail($id);

        $yaPostulado = Postulacion::where('vacante_id', $vacante->id)
            ->where('postulante_id', Auth::id())
            ->whereIn('estado', ['pendiente', 'contratado'])
            ->exists();

        return view('usuario.ver-vacante', [
            'vacante' => $vacante,
            'yaPostulado' => $yaPostulado,
        ]);
    }

    public function store(GuardarVacanteRequest $request, ModeracionService $moderacion)
    {
        // Las reglas (tipos de dato, largos, teléfono de 10 dígitos, fechas,
        // duración...) viven en GuardarVacanteRequest.
        $datos = $request->datosParaGuardar();

        $revision = $moderacion->revisar(
            $datos['titulo'] . "\n" . $datos['descripcion'],
            $request->file('imagen')
        );
        if ($revision['flagged']) {
            return response()->json(['ok' => false, 'message' => $revision['mensaje']], 422);
        }

        $rutaImagen = null;
        if ($request->hasFile('imagen')) {
            $rutaImagen = '/storage/' . $request->file('imagen')->store('vacantes', 'public');
        }

        Vacante::create($datos + [
            'empleador_id' => Auth::id(),
            'imagen' => $rutaImagen,
            'estado' => 'activa',
        ]);

        return response()->json(['ok' => true]);
    }

    public function edit($id)
    {
        $usuario = Auth::user();
        $vacante = Vacante::where('empleador_id', Auth::id())->findOrFail($id);

        $tituloDescripcionBloqueada = $vacante->postulaciones()->exists();

        return view('usuario.publicar-vacante', [
            'usuario' => $usuario,
            'vacante' => $vacante,
            'tituloDescripcionBloqueada' => $tituloDescripcionBloqueada,
            'categorias' => $this->categoriasConSubcategorias(),
        ]);
    }

    public function update(GuardarVacanteRequest $request, $id, ModeracionService $moderacion)
    {
        $vacante = Vacante::where('empleador_id', Auth::id())->findOrFail($id);

        $tituloDescripcionBloqueada = $vacante->postulaciones()->exists();

        // Las reglas viven en GuardarVacanteRequest (si la vacante ya tiene
        // postulaciones, ahí mismo se deja de pedir título y descripción).
        $datos = $request->datosParaGuardar();

        // Si NO está bloqueada, sí se actualizan título y descripción con lo
        // que mande el formulario. Si SÍ está bloqueada, se ignora cualquier
        // intento de cambiarlos (se quedan como ya estaban), sin importar
        // qué se haya mandado en la petición.
        if ($tituloDescripcionBloqueada) {
            unset($datos['titulo'], $datos['descripcion']);
        }

        $revision = $moderacion->revisar(
            ($datos['titulo'] ?? '') . "\n" . ($datos['descripcion'] ?? ''),
            $request->file('imagen')
        );
        if ($revision['flagged']) {
            return response()->json(['ok' => false, 'message' => $revision['mensaje']], 422);
        }

        $datos['imagen'] = $vacante->imagen;
        if ($request->hasFile('imagen')) {
            $datos['imagen'] = '/storage/' . $request->file('imagen')->store('vacantes', 'public');
        }

        // Una vacante marcada "vencida" que se vuelve a abrir con un nuevo
        // límite para postular pasa otra vez a "activa".
        if ($vacante->estado === 'vencida' && (empty($datos['fecha_limite']) || $datos['fecha_limite'] >= today()->toDateString())) {
            $datos['estado'] = 'activa';
        }

        $vacante->update($datos);

        return response()->json(['ok' => true]);
    }

    public function misVacantes(Request $request)
    {
        $estado = $request->input('estado');
        $contrato = $request->input('contrato');
        $q = $request->input('q');

        $vacantes = Vacante::withCount('postulaciones')
            ->where('empleador_id', Auth::id())
            ->when($estado, function ($query) use ($estado) {
                // "Activas" son las que siguen abiertas (no vencidas por fecha);
                // "Vencidas" incluye las que ya pasaron su límite para postular.
                return match ($estado) {
                    'activa' => $query->abiertas(),
                    'vencida' => $query->vencidas(),
                    default => $query->where('estado', $estado),
                };
            })
            ->when($contrato, fn($query) => $query->where('contrato', $contrato))
            ->when($q, fn($query) => $query->where('titulo', 'like', "%{$q}%"))
            ->latest()
            ->get();

        $vacantesActivas = Vacante::where('empleador_id', Auth::id())->abiertas()->count();

        $totalPostulantes = Postulacion::whereHas('vacante', function ($qy) {
            $qy->where('empleador_id', Auth::id());
        })->count();

        $contratados = Postulacion::whereHas('vacante', function ($qy) {
            $qy->where('empleador_id', Auth::id());
        })->where('estado', 'contratado')->count();

        return view('usuario.mis-vacantes', [
            'vacantes' => $vacantes,
            'vacantesActivas' => $vacantesActivas,
            'totalPostulantes' => $totalPostulantes,
            'contratados' => $contratados,
            'estado' => $estado,
            'contrato' => $contrato,
            'q' => $q,
        ]);
    }

    public function cerrar($id)
    {
        $vacante = Vacante::where('empleador_id', Auth::id())->findOrFail($id);
        $vacante->estado = 'cerrada';
        $vacante->save();

        return response()->json(['ok' => true]);
    }

    public function reactivar($id)
    {
        $vacante = Vacante::where('empleador_id', Auth::id())->findOrFail($id);

        // Si ya pasó su límite para postular, reactivarla no serviría de
        // nada: hay que editarla y poner una fecha límite nueva.
        if ($vacante->fecha_limite && $vacante->fecha_limite->lt(today())) {
            return response()->json([
                'ok' => false,
                'message' => 'Esta vacante ya pasó su límite para postular. Edítala y elige una fecha nueva para volver a publicarla.',
            ], 422);
        }

        $vacante->estado = 'activa';
        $vacante->save();

        return response()->json(['ok' => true]);
    }

    public function destroy($id)
    {
        $vacante = Vacante::where('empleador_id', Auth::id())->findOrFail($id);
        $vacante->delete();

        return response()->json(['ok' => true]);
    }
}
