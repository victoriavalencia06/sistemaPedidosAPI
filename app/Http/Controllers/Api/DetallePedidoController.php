<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DetallePedido;

class DetallePedidoController extends Controller
{
    public function index()
    {
        return response()->json(DetallePedido::all());
    }

    public function store(Request $request)
    {
        $request->validate([
            'idPedido' => 'required|exists:pedido,idPedido',
            'idProducto' => 'required|exists:producto,idProducto',
            'cantidad' => 'required|integer|min:1',
        ]);

        $detalle = DetallePedido::create($request->all());

        return response()->json($detalle, 201);
    }

    public function show($id)
    {
        return response()->json(DetallePedido::findOrFail($id));
    }

    public function update(Request $request, $id)
    {
        $detalle = DetallePedido::findOrFail($id);
        $detalle->update($request->all());

        return response()->json($detalle);
    }

    public function destroy($id)
    {
        $detalle = DetallePedido::findOrFail($id);
        $detalle->delete();

        return response()->json(['message' => 'Detalle eliminado']);
    }
}
