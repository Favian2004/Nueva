<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use App\Models\Vacante;
use App\Models\Servicio;
use App\Models\Postulacion;
use App\Models\Anuncio;
use App\Models\Municipio;
use App\Models\Categoria;
use App\Models\Reporte;
use Illuminate\Http\Request;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'usuarios' => Usuario::count(),
            'verificaciones' => Usuario::where('verificacion_estado', 'pendiente')->count(),
            'vacantes' => Vacante::where('estado', 'activa')->count(),
            'servicios' => Servicio::where('estado', 'activo')->count(),
            'postulaciones' => Postulacion::where('estado', 'pendiente')->count(),
            'anuncios' => Anuncio::where('estado', 'activo')->count(),
            'municipios' => Municipio::count(),
            'categorias' => Categoria::count(),
            'reportes' => Reporte::where('estado', 'pendiente')->count(),
        ];

        $verificacionesPendientes = Usuario::with('localidad')
            ->where('verificacion_estado', 'pendiente')
            ->latest()
            ->get();

        $ultimasVacantes = Vacante::latest()->take(5)->get();

        return view('admin.index', [
            'stats' => $stats,
            'verificacionesPendientes' => $verificacionesPendientes,
            'ultimasVacantes' => $ultimasVacantes,
        ]);
    }
}
