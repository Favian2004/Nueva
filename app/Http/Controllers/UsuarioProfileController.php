<?php

namespace App\Http\Controllers;

use App\Models\Calificacion;
use App\Models\Contratacion;
use App\Models\DocumentoVerificacion;
use App\Models\Localidad;
use App\Models\Servicio;
use App\Models\Usuario;
use App\Services\EstadisticasPerfilService;
use App\Services\ModeracionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UsuarioProfileController extends Controller
{
    public function index(EstadisticasPerfilService $estadisticasPerfil)
    {
        $usuario = Usuario::with(['localidad.municipio', 'documentosVerificacion'])
            ->findOrFail(Auth::id());

        $misServiciosIds = Servicio::where('usuario_id', $usuario->id)->pluck('id');

        $serviciosPublicados = $misServiciosIds->count();

        $totalCalificaciones = Calificacion::whereIn('servicio_id', $misServiciosIds)->count();
        $calificacionPromedio = $totalCalificaciones
            ? round(Calificacion::whereIn('servicio_id', $misServiciosIds)->avg('estrellas'), 1)
            : null;

        $clientesAtendidos = Contratacion::whereIn('servicio_id', $misServiciosIds)
            ->where('estado', 'finalizado')
            ->distinct('contratante_id')
            ->count('contratante_id');

        $docIne = $usuario->documentosVerificacion->firstWhere('tipo_documento', 'ine');
        $docSelfie = $usuario->documentosVerificacion->firstWhere('tipo_documento', 'selfie');

        // TODO: cuando haya más de un municipio, filtrar por el municipio
        // que el usuario elija en vez de dejarlo fijo en Zacapoaxtla (id 1).
        $localidades = Localidad::where('municipio_id', 1)->orderBy('nombre')->get();

        return view('usuario.profile', [
            'usuario' => $usuario,
            'serviciosPublicados' => $serviciosPublicados,
            'calificacionPromedio' => $calificacionPromedio,
            'totalCalificaciones' => $totalCalificaciones,
            'clientesAtendidos' => $clientesAtendidos,
            'docIne' => $docIne,
            'docSelfie' => $docSelfie,
            'localidades' => $localidades,
            'estadisticas' => $estadisticasPerfil->para($usuario),
        ]);
    }

    public function update(Request $request, ModeracionService $moderacion)
    {
        $usuario = Usuario::findOrFail(Auth::id());

        $request->validate([
            'nombre' => 'required|string|max:150',
            'email' => 'required|email|max:150|unique:usuarios,email,' . $usuario->id,
            'telefono' => 'nullable|digits:10',
            'descripcion' => 'nullable|string',
            'localidad_id' => 'required|exists:localidades,id',
            'foto' => 'nullable|image|max:4096',
        ], [
            'telefono.digits' => 'El teléfono debe tener exactamente 10 números (solo números, sin espacios).',
        ]);

        $revision = $moderacion->revisar(
            $request->input('nombre') . "\n" . $request->input('descripcion'),
            $request->file('foto')
        );
        if ($revision['flagged']) {
            return response()->json(['ok' => false, 'message' => $revision['mensaje']], 422);
        }

        $usuario->nombre = $request->input('nombre');
        $usuario->email = $request->input('email');
        $usuario->telefono = $request->input('telefono');
        $usuario->descripcion = $request->input('descripcion');
        $usuario->localidad_id = $request->input('localidad_id');

        if ($request->hasFile('foto')) {
            $usuario->foto_perfil = '/storage/' . $request->file('foto')->store('perfiles', 'public');
        }

        $usuario->save();
        $usuario->load('localidad.municipio');

        // Si ya quedó completo, se quita el aviso "Completa tu perfil" de la campana.
        \App\Services\PerfilCompleto::sincronizar($usuario);

        return response()->json(['ok' => true, 'usuario' => $usuario]);
    }

    public function updatePassword(Request $request)
    {
        $usuario = Usuario::findOrFail(Auth::id());
        $tieneContrasenaYa = (bool) $usuario->password;

        $reglas = [
            'password_nueva' => 'required|string|min:8|confirmed',
        ];
        if ($tieneContrasenaYa) {
            $reglas['password_actual'] = 'required|string';
        }

        $request->validate($reglas);

        if ($tieneContrasenaYa && !Hash::check($request->input('password_actual'), $usuario->password)) {
            return response()->json(['ok' => false, 'message' => 'Tu contraseña actual no es correcta.'], 422);
        }

        $usuario->password = Hash::make($request->input('password_nueva'));
        $usuario->save();

        return response()->json(['ok' => true, 'creada' => !$tieneContrasenaYa]);
    }

    public function uploadDocumento(Request $request, $tipo, ModeracionService $moderacion)
    {
        $request->validate([
            'archivo' => 'required|image|max:4096',
        ]);

        if (!in_array($tipo, ['ine', 'selfie'])) {
            return response()->json(['ok' => false, 'error' => 'Tipo de documento inválido.'], 422);
        }

        // Un documento ya aprobado no se puede reemplazar.
        $yaAprobado = DocumentoVerificacion::where('usuario_id', Auth::id())
            ->where('tipo_documento', $tipo)
            ->whereNull('indice')
            ->where('estado', 'aprobado')
            ->exists();
        if ($yaAprobado) {
            return response()->json(['ok' => false, 'message' => 'Este documento ya fue verificado y no se puede cambiar.'], 403);
        }

        $revision = $moderacion->revisar(null, $request->file('archivo'));
        if ($revision['flagged']) {
            return response()->json(['ok' => false, 'message' => $revision['mensaje']], 422);
        }

        // El archivo se guarda con un prefijo único + el nombre original (limpio),
        // así en el perfil se puede mostrar cómo se llama: "xxxx__mi-ine.jpg" -> "mi-ine.jpg".
        $archivo   = $request->file('archivo');
        $extension = strtolower($archivo->getClientOriginalExtension() ?: $archivo->extension() ?: 'jpg');
        $limpio    = \Illuminate\Support\Str::limit(
            \Illuminate\Support\Str::slug(pathinfo($archivo->getClientOriginalName(), PATHINFO_FILENAME)),
            40,
            ''
        ) ?: $tipo;
        $nombre = $limpio . '.' . $extension;

        $ruta = '/storage/' . $archivo->storeAs(
            'documentos',
            \Illuminate\Support\Str::random(10) . '__' . $nombre,
            'public'
        );

        DocumentoVerificacion::updateOrCreate(
            ['usuario_id' => Auth::id(), 'tipo_documento' => $tipo, 'indice' => null],
            ['archivo' => $ruta, 'estado' => 'pendiente']
        );

        // Si la verificación estaba rechazada, al volver a subir un documento la cuenta
        // regresa a "pendiente" para que el administrador la vuelva a revisar.
        Usuario::where('id', Auth::id())
            ->where('verificacion_estado', 'rechazado')
            ->update(['verificacion_estado' => 'pendiente']);

        // Actualiza el aviso "Verifica tu cuenta" de la campana (se acorta o desaparece).
        \App\Services\PerfilCompleto::sincronizar(Usuario::find(Auth::id()));

        return response()->json(['ok' => true, 'nombre' => $nombre, 'url' => $ruta]);
    }
}
