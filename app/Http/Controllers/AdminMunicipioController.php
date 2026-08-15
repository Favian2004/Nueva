<?php

namespace App\Http\Controllers;

use App\Models\Municipio;
use App\Models\Localidad;
use Illuminate\Http\Request;

class AdminMunicipioController extends Controller
{
    public function index()
    {
        $municipios = Municipio::withCount('localidades')->orderBy('nombre')->get();
        $localidades = Localidad::with('municipio')->withCount('usuarios')->orderBy('nombre')->get();

        return view('admin.municipios', [
            'municipios' => $municipios,
            'localidades' => $localidades,
        ]);
    }

    public function storeMunicipio(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:150',
        ]);

        Municipio::create([
            'nombre' => $request->input('nombre'),
        ]);

        return response()->json(['ok' => true]);
    }

    public function destroyMunicipio($id)
    {
        $municipio = Municipio::findOrFail($id);
        $municipio->delete(); // borra en cascada sus localidades (definido en la base de datos)

        return response()->json(['ok' => true]);
    }

    public function storeLocalidad(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:150',
            'municipio_id' => 'required|exists:municipios,id',
        ]);

        Localidad::create([
            'nombre' => $request->input('nombre'),
            'municipio_id' => $request->input('municipio_id'),
        ]);

        return response()->json(['ok' => true]);
    }

    public function destroyLocalidad($id)
    {
        $localidad = Localidad::findOrFail($id);
        $localidad->delete();

        return response()->json(['ok' => true]);
    }
}
