<?php

namespace App\Http\Controllers;

use App\Models\Contratacion;
use Illuminate\Http\Request;

class AdminContratacionController extends Controller
{
    public function index()
    {
        $contrataciones = Contratacion::with(['servicio', 'contratante', 'trabajador'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.contrataciones', [
            'contrataciones' => $contrataciones,
        ]);
    }
}
