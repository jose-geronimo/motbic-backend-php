<?php

namespace App\Http\Controllers;

use App\Models\Venta;
use Illuminate\Http\Request;

class VentaController extends Controller
{
    public function index()
    {
        $ventas = Venta::paginate(10);
        return response()->json($ventas);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'cliente_id' => 'required|exists:clientes,id',
            'inventario_id' => 'required|exists:inventario,id',
            'fecha' => 'required|date',
            'metodo_pago' => 'required|in:efectivo,transferencia,tarjeta-credito,tarjeta-debito,cheque,financiamiento,mixto',
            'precio_total' => 'required|numeric',
            'estado' => 'in:completada,pendiente,cancelada',
        ]);

        // Generate Folio
        $lastVenta = Venta::latest()->first();
        $nextId = $lastVenta ? intval(substr($lastVenta->folio, 2)) + 1 : 1;
        $folio = 'V-' . str_pad($nextId, 4, '0', STR_PAD_LEFT);

        $venta = Venta::create(array_merge($validated, ['folio' => $folio]));
        
        // Load relationships for response
        $venta->load(['cliente', 'inventario.modelo']);
        
        // Format response to match docs
        $response = $venta->toArray();
        if ($venta->inventario && $venta->inventario->modelo) {
             $response['motocicleta'] = $venta->inventario->modelo->marca . ' ' . $venta->inventario->modelo->nombre;
        }
        if ($venta->cliente) {
            $response['cliente'] = $venta->cliente->nombres . ' ' . $venta->cliente->apellidos;
        }

        return response()->json($response);
    }

    public function show($id)
    {
        $venta = Venta::with(['cliente', 'inventario.modelo'])->findOrFail($id);
        return response()->json($venta);
    }
}
