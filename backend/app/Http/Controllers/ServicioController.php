<?php

namespace App\Http\Controllers;

use App\Models\Servicio;
use Illuminate\Http\Request;

class ServicioController extends Controller
{
    public function index()
    {
        $servicios = Servicio::with(['cliente', 'inventario.modelo', 'venta'])->paginate(10);
        
        $servicios->getCollection()->transform(function ($servicio) {
            return [
                'id' => $servicio->id,
                'cliente' => $servicio->cliente ? $servicio->cliente->nombres . ' ' . $servicio->cliente->apellidos : null,
                'telefono' => $servicio->cliente ? $servicio->cliente->telefono : null,
                'motocicleta' => ($servicio->inventario && $servicio->inventario->modelo) ? $servicio->inventario->modelo->marca . ' ' . $servicio->inventario->modelo->nombre : null,
                'numero_serie' => $servicio->inventario ? $servicio->inventario->serie : null,
                'tipo_servicio' => $servicio->tipo_servicio,
                'fecha_venta' => $servicio->venta ? $servicio->venta->fecha->format('Y-m-d') : null,
                'fecha_recomendada' => $servicio->fecha_programada ? $servicio->fecha_programada->format('Y-m-d') : null,
                'dias_restantes' => $servicio->fecha_programada ? now()->diffInDays($servicio->fecha_programada, false) : null,
                'estado' => $servicio->estado,
            ];
        });

        return response()->json($servicios);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'cliente_id' => 'required|exists:clientes,id',
            'inventario_id' => 'required|exists:inventario,id',
            'venta_id' => 'nullable|exists:ventas,id',
            'tipo_servicio' => 'required|in:primer-servicio,segundo-servicio,tercer-servicio,mantenimiento-regular,reparacion',
            'fecha_programada' => 'required|date',
            'notas' => 'nullable|string',
            'costo' => 'nullable|numeric',
        ]);

        $servicio = Servicio::create($validated);
        return response()->json($servicio);
    }

    public function completar(Request $request, $id)
    {
        $servicio = Servicio::findOrFail($id);
        $validated = $request->validate([
            'fecha_realizada' => 'required|date',
            'notas' => 'nullable|string',
        ]);

        $servicio->update(array_merge($validated, ['estado' => 'completado']));
        return response()->json(['message' => 'Servicio marcado como completado exitosamente']);
    }
}
