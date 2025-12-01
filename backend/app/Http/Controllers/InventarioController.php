<?php

namespace App\Http\Controllers;

use App\Models\Inventario;
use Illuminate\Http\Request;

class InventarioController extends Controller
{
    public function conteo()
    {
        $total = Inventario::count();
        $disponibles = Inventario::where('estado', 'disponible')->count();
        $vendidas = Inventario::where('estado', 'vendida')->count();
        $defectuosas = Inventario::where('estado', 'defectuosa')->count();

        return response()->json([
            'total' => $total,
            'disponibles' => $disponibles,
            'vendidas' => $vendidas,
            'defectuosas' => $defectuosas,
        ]);
    }

    public function index()
    {
        $inventario = Inventario::paginate(10);
        return response()->json($inventario);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'modelo_id' => 'required|exists:modelos,id',
            'color' => 'required|string|max:60',
            'serie' => 'required|string|max:60|unique:inventario,serie',
            'motor' => 'required|string|max:60',
            'vin' => 'required|string|size:17|unique:inventario,vin',
            'estado' => 'in:disponible,vendida,reservada,defectuosa',
        ]);

        $item = Inventario::create($validated);
        return response()->json($item);
    }
}
