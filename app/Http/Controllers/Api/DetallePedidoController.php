<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DetallePedido;
use App\Models\Pedido;
use App\Models\Producto;
use Illuminate\Support\Facades\DB;

class DetallePedidoController extends Controller
{
    public function index()
    {
        $detalles = DetallePedido::with(['pedido', 'producto'])
            ->get();

        return response()->json($detalles);
    }

    public function store(Request $request)
    {
        $request->validate([
            'idPedido' => 'required|exists:pedido,idPedido',
            'idProducto' => 'required|exists:producto,idProducto',
            'cantidad' => 'required|integer|min:1',
            'subtotal' => 'nullable|numeric|min:0',
        ]);

        return DB::transaction(function () use ($request) {
            $pedido = Pedido::findOrFail($request->idPedido);
            $producto = Producto::findOrFail($request->idProducto);
            $cantidad = (int) $request->cantidad;

            // calcular subtotal si no viene
            $subtotal = $request->has('subtotal')
                ? (float) $request->subtotal
                : (float) bcmul($producto->precio, $cantidad, 2);

            // crear detalle
            $detalle = DetallePedido::create([
                'idPedido' => $pedido->idPedido,
                'idProducto' => $producto->idProducto,
                'cantidad' => $cantidad,
                'subtotal' => $subtotal,
            ]);

            // decrementar stock si aplica
            if (is_numeric($producto->stock)) {
                $producto->decrement('stock', $cantidad);
            }

            // actualizar total del pedido
            $pedido->total = (float) $pedido->total + $subtotal;
            $pedido->save();

            $detalle->load(['pedido', 'producto']);
            return response()->json($detalle, 201);
        });
    }

    public function show($id)
    {
        $detalle = DetallePedido::with(['pedido', 'producto'])
            ->findOrFail($id);

        return response()->json($detalle);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'idPedido' => 'required|exists:pedido,idPedido',
            'idProducto' => 'required|exists:producto,idProducto',
            'cantidad' => 'required|integer|min:1',
            'subtotal' => 'nullable|numeric|min:0',
        ]);

        return DB::transaction(function () use ($request, $id) {
            $detalle = DetallePedido::findOrFail($id);
            $pedido  = Pedido::findOrFail($request->idPedido);
            $productoNuevo = Producto::findOrFail($request->idProducto);

            // Datos antiguos
            $productoAnt = Producto::find($detalle->idProducto);
            $cantidadAnt = (int) $detalle->cantidad;
            $subtotalAnt = (float) $detalle->subtotal;

            $cantidadNueva = (int) $request->cantidad;

            // Si cambió el producto: devolver stock del antiguo, descontar stock del nuevo
            if ($detalle->idProducto != $productoNuevo->idProducto) {
                if (is_numeric($productoAnt->stock)) {
                    $productoAnt->increment('stock', $cantidadAnt);
                }
                if (is_numeric($productoNuevo->stock)) {
                    $productoNuevo->decrement('stock', $cantidadNueva);
                }
            } else {
                // mismo producto: ajustar stock según diferencia
                $diff = $cantidadNueva - $cantidadAnt;
                if (is_numeric($productoNuevo->stock)) {
                    if ($diff > 0) {
                        $productoNuevo->decrement('stock', $diff);
                    } elseif ($diff < 0) {
                        $productoNuevo->increment('stock', abs($diff));
                    }
                }
            }

            // recalcular subtotal si no viene
            $subtotalNuevo = $request->has('subtotal')
                ? (float) $request->subtotal
                : (float) bcmul($productoNuevo->precio, $cantidadNueva, 2);

            // actualizar detalle
            $detalle->update([
                'idPedido' => $pedido->idPedido,
                'idProducto' => $productoNuevo->idProducto,
                'cantidad' => $cantidadNueva,
                'subtotal' => $subtotalNuevo,
            ]);

            // actualizar total del pedido
            $pedido->total = (float) $pedido->total - $subtotalAnt + $subtotalNuevo;
            $pedido->save();

            $detalle->load(['pedido', 'producto']);

            return response()->json($detalle);
        });
    }

    public function destroy($id)
    {
        $detalle = DetallePedido::findOrFail($id);
        $detalle->delete();

        return response()->json(['message' => 'Detalle eliminado']);
    }
}
