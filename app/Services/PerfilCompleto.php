<?php

namespace App\Services;

use App\Models\DocumentoVerificacion;
use App\Models\Usuario;
use App\Notifications\CompletarPerfil;
use App\Notifications\InvitarVerificacion;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;

/**
 * Dos avisos de la campana que invitan a la persona a completar su cuenta:
 *
 *  1) "Completa tu perfil"  -> falta teléfono, foto o descripción. Lleva al formulario "Editar perfil".
 *  2) "Verifica tu cuenta"  -> falta subir el INE o la selfie (o la verificación fue rechazada).
 *                              Lleva a la pestaña Documentos.
 *
 * Reglas (iguales para los dos):
 *  - Mientras falte algo, la persona tiene UN aviso de ese tipo (el texto se acorta conforme avanza).
 *  - Si lo marca como leído y sigue pendiente, se le recuerda de nuevo después de REPETIR_DIAS días.
 *  - En cuanto no falte nada, el aviso desaparece y ya no se avisa más.
 *
 * La localidad no cuenta para el perfil: al registrarse a todos se les pone una por defecto,
 * así que no se puede saber si ya la eligieron.
 */
class PerfilCompleto
{
    public const REPETIR_DIAS = 3;

    private const DOCUMENTOS = ['ine' => 'tu INE', 'selfie' => 'tu selfie con INE'];

    /** @return array<string,string> clave => cómo se le dice a la persona */
    public static function faltantes(Usuario $u): array
    {
        $falta = [];

        if (!preg_match('/^\d{10}$/', (string) $u->telefono)) {
            $falta['telefono'] = 'tu teléfono';
        }
        if (trim((string) $u->foto_perfil) === '') {
            $falta['foto'] = 'tu foto';
        }
        if (trim((string) $u->descripcion) === '') {
            $falta['descripcion'] = 'tu descripción';
        }

        return $falta;
    }

    /**
     * Documentos que la persona todavía tiene que subir para verificarse.
     * Vacío = ya es verificado, o ya subió todo y solo espera al administrador.
     *
     * @return array<string,string>
     */
    public static function documentosPorSubir(Usuario $u): array
    {
        if ($u->verificacion_estado === 'aprobado') {
            return [];
        }

        $estados = DocumentoVerificacion::where('usuario_id', $u->id)
            ->whereNull('indice')
            ->pluck('estado', 'tipo_documento');

        $rechazada = $u->verificacion_estado === 'rechazado';
        $falta = [];
        foreach (self::DOCUMENTOS as $tipo => $nombre) {
            $estado = $estados[$tipo] ?? null;
            if ($estado === null || ($rechazada && $estado === 'rechazado')) {
                $falta[$tipo] = $nombre;
            }
        }

        return $falta;
    }

    /** Crea, actualiza o quita los avisos según cómo esté la cuenta ahora. Nunca rompe lo que se estaba haciendo. */
    public static function sincronizar(?Usuario $u): void
    {
        if (!$u || $u->rol === 'admin') {
            return;
        }

        try {
            if (!Schema::hasTable('notifications')) {
                return;
            }

            // 1) Completar el perfil
            $falta = self::faltantes($u);
            self::gestionar(
                $u, CompletarPerfil::class, $falta, [],
                ['texto' => CompletarPerfil::texto($falta)],
                fn() => new CompletarPerfil($falta)
            );

            // 2) Verificar la cuenta
            $docs = self::documentosPorSubir($u);
            $rechazada = $u->verificacion_estado === 'rechazado';
            self::gestionar(
                $u, InvitarVerificacion::class, $docs, ['rechazada' => $rechazada],
                InvitarVerificacion::textos($docs, $rechazada),
                fn() => new InvitarVerificacion($docs, $rechazada)
            );
        } catch (\Throwable $e) {
            Log::warning('No se pudieron sincronizar los avisos de la cuenta: ' . $e->getMessage());
        }
    }

    /**
     * @param array<string,string> $falta   lo que falta (vacío = quitar el aviso)
     * @param array<string,mixed>  $estado  datos que, si cambian, hacen que se avise de nuevo enseguida
     * @param array<string,string> $campos  texto actual que debe verse en el aviso (titulo/texto)
     */
    private static function gestionar(Usuario $u, string $clase, array $falta, array $estado, array $campos, \Closure $crear): void
    {
        $avisos = $u->notifications()->where('type', $clase);

        if (!$falta) {
            (clone $avisos)->delete(); // no falta nada: se acabó el aviso
            return;
        }

        $ultimo = (clone $avisos)->latest()->first();
        $datos = $ultimo ? $ultimo->data : [];

        // Cambió algo importante (ej. el administrador rechazó la verificación): se avisa de nuevo ya.
        $cambio = false;
        foreach ($estado as $clave => $valor) {
            if (($datos[$clave] ?? $valor) !== $valor) {
                $cambio = true;
            }
        }

        if ($ultimo && !$cambio) {
            if ($ultimo->read_at === null) {
                // Sigue sin leer: solo se pone al día lo que falta.
                $nuevo = $campos + $estado + ['faltan' => array_keys($falta)] + $datos;
                if (array_diff_assoc(array_map('json_encode', $nuevo), array_map('json_encode', $datos))) {
                    $ultimo->forceFill(['data' => $nuevo])->save();
                }
                return;
            }

            if (($ultimo->read_at ?? $ultimo->created_at)->gt(now()->subDays(self::REPETIR_DIAS))) {
                return; // lo leyó hace poco: todavía no toca recordarle
            }
        }

        (clone $avisos)->delete(); // se deja una sola fila por persona
        Avisos::enviar($u, $crear());
    }
}
