<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pedido;
use App\Models\Producto;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Response;

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
            'fechaPedido' => 'nullable|date',
            'total' => 'nullable|numeric|min:0',
            'tipoPago' => 'nullable|string|max:50',
            'estado' => 'nullable|string|max:50',
            'detalles' => 'nullable|array',
            'detalles.*.idProducto' => 'required_with:detalles|exists:producto,idProducto',
            'detalles.*.cantidad' => 'required_with:detalles|integer|min:1',
        ]);

        return DB::transaction(function () use ($request) {
            // generar código único
            $codigo = 'PED-' . strtoupper(Str::random(6));

            $pedido = Pedido::create([
                'idUsuario' => $request->idUsuario,
                'codigo' => $codigo,
                'fechaPedido' => $request->fechaPedido ?? now(),
                'tipoPago' => $request->tipoPago ?? null,
                'total' => 0, // se actualizará
                'estado' => $request->estado ?? 'Pendiente',
            ]);

            $total = 0;

            if ($request->filled('detalles')) {
                // validamos disponibilidad de stock para todos los productos pedidos
                foreach ($request->detalles as $d) {
                    $producto = Producto::findOrFail($d['idProducto']);
                    $cantidad = (int) $d['cantidad'];

                    if (is_numeric($producto->stock) && $producto->stock < $cantidad) {
                        // revertir la transacción
                        return response()->json([
                            'message' => "Stock insuficiente para el producto {$producto->nombre} (id: {$producto->idProducto})"
                        ], Response::HTTP_BAD_REQUEST);
                    }
                }

                // si pasamos la verificación, creamos detalles y decrementamos stock
                foreach ($request->detalles as $d) {
                    $producto = Producto::findOrFail($d['idProducto']);
                    $cantidad = (int) $d['cantidad'];

                    // calcular subtotal
                    if (function_exists('bcmul')) {
                        $subtotal = (float) bcmul($producto->precio, $cantidad, 2);
                    } else {
                        $subtotal = (float) round($producto->precio * $cantidad, 2);
                    }

                    $detalle = $pedido->detalles()->create([
                        'idProducto' => $producto->idProducto,
                        'cantidad' => $cantidad,
                        'subtotal' => $subtotal,
                    ]);

                    // decrementar stock
                    if (is_numeric($producto->stock)) {
                        $producto->decrement('stock', $cantidad);
                    }

                    $total += (float) $subtotal;
                }
            } else {
                // si no se envían detalles, usar total enviado o 0
                $total = $request->total ?? 0;
            }

            // actualizar total y devolver pedido con relaciones
            $pedido->total = $total;
            $pedido->save();

            $pedido->load('detalles', 'usuario');

            return response()->json($pedido, 201);
        });
    }

    public function show($id)
    {
        return response()->json(Pedido::findOrFail($id));
    }

    public function update(Request $request, $id)
    {
        $pedido = Pedido::findOrFail($id);

        $request->validate([
            'fechaPedido' => 'nullable|date',
            'tipoPago' => 'nullable|string|max:50',
            'estado' => 'nullable|string|max:50',
        ]);

        $pedido->update($request->only('fechaPedido', 'tipoPago', 'estado'));

        return response()->json($pedido);
    }

    public function destroy($id)
    {
        return DB::transaction(function () use ($id) {
            $pedido = Pedido::findOrFail($id);

            // Revertir stock de cada detalle
            foreach ($pedido->detalles as $detalle) {
                $producto = Producto::find($detalle->idProducto);
                if ($producto && is_numeric($producto->stock)) {
                    $producto->increment('stock', $detalle->cantidad);
                }
            }

            // Si prefieres no eliminar físicamente, marcar como cancelado:
            $pedido->estado = 'Cancelado';
            $pedido->save();

            return response()->json(['message' => 'Pedido anulado (estado Cancelado) y stock revertido']);
        });
    }
}
