<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pedido;

class PedidoController extends Controller
{
    public function index()
    {
        return response()->json(Pedido::all());
    }

    public function store(Request $request)
    {
        $request->validate([
            'idUsuario' => 'required|exists:usuario,idUsuario',
            'fecha' => 'required|date',
            'total' => 'required|numeric|min:0',
        ]);

        $pedido = Pedido::create($request->all());

        return response()->json($pedido, 201);
    }

    public function show($id)
    {
        return response()->json(Pedido::findOrFail($id));
    }

    public function update(Request $request, $id)
    {
        $pedido = Pedido::findOrFail($id);

        $pedido->update($request->all());

        return response()->json($pedido);
    }

    public function destroy($id)
    {
        $pedido = Pedido::findOrFail($id);
        $pedido->delete();

        return response()->json(['message' => 'Pedido eliminado']);
    }
}
