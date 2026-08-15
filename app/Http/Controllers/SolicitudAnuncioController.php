<?php

namespace App\Http\Controllers;

use App\Models\SolicitudAnuncio;
use Illuminate\Http\Request;

class SolicitudAnuncioController extends Controller
{
    // Datos de depósito (cámbialos aquí si algún día cambias de cuenta)
    const CLABE = 'Aun esta en proceso';
    const TITULAR = 'Juan perez';
    const BANCO = 'Nu México';

    public function create()
    {
        return view('anunciar', [
            'clabe' => self::CLABE,
            'titular' => self::TITULAR,
            'banco' => self::BANCO,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre_negocio' => 'required|string|max:150',
            'descripcion' => 'required|string|max:2000',
            'telefono' => 'required|string|max:20',
            'whatsapp' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:150',
            'plan' => 'required|in:mensual,anual',
            'imagen_negocio' => 'nullable|image|max:4096',
            'comprobante_pago' => 'required|mimes:jpg,jpeg,png,webp,pdf|max:4096',
        ]);

        $data = $request->only(['nombre_negocio', 'descripcion', 'telefono', 'whatsapp', 'email', 'plan']);

        if ($request->hasFile('imagen_negocio')) {
            $data['imagen_negocio'] = '/storage/' . $request->file('imagen_negocio')->store('solicitudes-anuncio', 'public');
        }

        $data['comprobante_pago'] = '/storage/' . $request->file('comprobante_pago')->store('solicitudes-anuncio/comprobantes', 'public');
        $data['estado'] = 'pendiente';

        SolicitudAnuncio::create($data);

        return redirect()->route('anunciar')->with('exito', '¡Solicitud enviada! La revisaremos y en cuanto confirmemos tu transferencia, tu anuncio quedará publicado.');
    }
}
