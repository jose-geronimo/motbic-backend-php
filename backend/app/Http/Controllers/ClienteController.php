<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use Illuminate\Http\Request;

class ClienteController extends Controller
{
    public function index()
    {
        $clientes = Cliente::paginate(10);
        return response()->json($clientes);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombres' => 'required|string|max:120',
            'apellidos' => 'required|string|max:150',
            'telefono' => 'required|string|max:20|unique:clientes,telefono',
            'email' => 'required|email|max:150|unique:clientes,email',
            'rfc' => 'required|string|size:13|unique:clientes,rfc',
            'calle' => 'required|string|max:150',
            'colonia' => 'required|string|max:120',
            'ciudad' => 'required|string|max:120',
            'estado' => 'required|string|max:120',
            'codigo_postal' => 'required|string|size:5',
        ]);

        $cliente = Cliente::create($validated);
        return response()->json($cliente);
    }

    public function update(Request $request, $id)
    {
        $cliente = Cliente::findOrFail($id);
        $validated = $request->validate([
            'nombres' => 'string|max:120',
            'apellidos' => 'string|max:150',
            'telefono' => 'string|max:20|unique:clientes,telefono,' . $id,
            'email' => 'email|max:150|unique:clientes,email,' . $id,
            'rfc' => 'string|size:13|unique:clientes,rfc,' . $id,
            'calle' => 'string|max:150',
            'colonia' => 'string|max:120',
            'ciudad' => 'string|max:120',
            'estado' => 'string|max:120',
            'codigo_postal' => 'string|size:5',
            'estado_servicios' => 'in:al-dia,pendiente,vencido',
        ]);

        $cliente->update($validated);
        return response()->json(['message' => 'Cliente actualizado exitosamente']);
    }

    public function destroy($id)
    {
        Cliente::destroy($id);
        return response()->json(['message' => 'Cliente eliminado exitosamente']);
    }
}
