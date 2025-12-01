<?php

namespace App\Http\Controllers;

use App\Models\Modelo;
use Illuminate\Http\Request;

class ModeloController extends Controller
{
    public function index()
    {
        $modelos = Modelo::paginate(10);
        return response()->json($modelos);
    }

    public function search(Request $request)
    {
        $query = $request->input('query');
        $modelos = Modelo::where('nombre', 'like', "%{$query}%")
            ->orWhere('marca', 'like', "%{$query}%")
            ->paginate(10);
        return response()->json($modelos);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:120',
            'marca' => 'required|string|max:120',
            'anio' => 'required|string|size:4',
            'tipo_motor' => 'required|in:electrica,gasolina,hibrida',
            'cilindrada' => 'required|string|max:50',
            'precio' => 'required|numeric',
            'colores' => 'required|array',
            'imagen' => 'nullable|url',
        ]);

        $modelo = Modelo::create($validated);
        return response()->json($modelo);
    }

    public function update(Request $request, $id)
    {
        $modelo = Modelo::findOrFail($id);
        $validated = $request->validate([
            'nombre' => 'string|max:120',
            'marca' => 'string|max:120',
            'anio' => 'string|size:4',
            'tipo_motor' => 'in:electrica,gasolina,hibrida',
            'cilindrada' => 'string|max:50',
            'precio' => 'numeric',
            'colores' => 'array',
            'imagen' => 'nullable|url',
        ]);

        $modelo->update($validated);
        return response()->json(['message' => 'Modelo actualizado exitosamente']);
    }

    public function destroy($id)
    {
        Modelo::destroy($id);
        return response()->json(['message' => 'Modelo eliminado exitosamente']);
    }
}
