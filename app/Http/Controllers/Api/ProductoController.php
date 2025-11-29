<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Producto;

class ProductoController extends Controller
{
    public function index()
    {
        return response()->json(Producto::where('estado', true)->get());
    }

    public function store(Request $request)
    {
        $request->validate([
            'idCategoria' => 'required|exists:categoria,idCategoria',
            'nombre' => 'required|string|max:100',
            'precio' => 'required|numeric|min:0',
            'descripcion' => 'nullable|string',
            'stock' => 'nullable|integer|min:0',
        ]);

        $prod = Producto::create([
            'idCategoria' => $request->idCategoria,
            'nombre' => $request->nombre,
            'descripcion' => $request->descripcion ?? null,
            'precio' => $request->precio,
            'stock' => $request->stock ?? 0,
            'estado' => true,
        ]);

        return response()->json($prod, 201);
    }

    public function show($id)
    {
        return response()->json(Producto::findOrFail($id));
    }

    public function update(Request $request, $id)
    {
        $prod = Producto::findOrFail($id);

        $request->validate([
            'idCategoria' => 'nullable|exists:categoria,idCategoria',
            'nombre' => 'nullable|string|max:100',
            'precio' => 'nullable|numeric|min:0',
            'descripcion' => 'nullable|string',
            'stock' => 'nullable|integer|min:0',
            'estado' => 'nullable|boolean',
        ]);

        $data = $request->only('idCategoria', 'nombre', 'precio', 'descripcion', 'stock', 'estado');

        if (! $request->has('stock')) {
            unset($data['stock']);
        }

        $prod->update($data);

        return response()->json($prod);
    }

    public function destroy($id)
    {
        $prod = Producto::findOrFail($id);
        $prod->estado = 0;
        $prod->save();

        return response()->json(['message' => 'Producto desactivado']);
    }
}
