<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

/**
 * La campana de notificaciones (menú de arriba): lista, contador y marcar como leído.
 * Es una sola lista por cuenta; cada aviso trae su etiqueta de modo
 * ("Como trabajador" / "Como empleador").
 */
class UsuarioNotificacionController extends Controller
{
    private const MAXIMO = 15;

    // Lista corta + cuántas hay sin leer (la campana lo consulta cada minuto).
    public function index(Request $request): JsonResponse
    {
        if (!$this->tablaLista()) {
            return $this->json(['no_leidas' => 0, 'items' => []]);
        }

        $usuario = $request->user();

        $items = $usuario->notifications()
            ->latest()
            ->limit(self::MAXIMO)
            ->get()
            ->map(fn($n) => [
                'id' => $n->id,
                'tipo' => $n->data['tipo'] ?? '',
                'modo' => $n->data['modo'] ?? '',
                'titulo' => $n->data['titulo'] ?? '',
                'texto' => $n->data['texto'] ?? '',
                'url' => $n->data['url'] ?? '/usuario',
                'leida' => $n->read_at !== null,
                'fecha' => $n->created_at?->toIso8601String(),
            ])
            ->values();

        return $this->json([
            'no_leidas' => $usuario->unreadNotifications()->count(),
            'items' => $items,
        ]);
    }

    public function leer(Request $request, string $id): JsonResponse
    {
        if ($this->tablaLista()) {
            // Solo se pueden marcar los avisos propios.
            $request->user()->notifications()->where('id', $id)->first()?->markAsRead();
        }

        return $this->json(['ok' => true]);
    }

    public function leerTodas(Request $request): JsonResponse
    {
        if ($this->tablaLista()) {
            $request->user()->unreadNotifications()->update(['read_at' => now()]);
        }

        return $this->json(['ok' => true]);
    }

    // Si todavía no se creó la tabla "notifications", la campana simplemente sale vacía.
    private function tablaLista(): bool
    {
        return Schema::hasTable('notifications');
    }

    private function json(array $datos): JsonResponse
    {
        return response()->json($datos)->header('Cache-Control', 'no-store');
    }
}
