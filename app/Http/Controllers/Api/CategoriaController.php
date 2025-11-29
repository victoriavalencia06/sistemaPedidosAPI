<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Categoria;

class CategoriaController extends Controller
{
    public function index()
    {
        return response()->json(Categoria::where('estado', true)->get());
    }

    public function store(Request $request)
    {
        $request->validate(['nombre' => 'required|string|max:100']);

        $cat = Categoria::create([
            'nombre' => $request->nombre,
            'estado' => true
        ]);

        return response()->json($cat, 201);
    }

    public function show($id)
    {
        return response()->json(Categoria::findOrFail($id));
    }

    public function update(Request $request, $id)
    {
        $cat = Categoria::findOrFail($id);
        $cat->update($request->only('nombre', 'estado'));

        return response()->json($cat);
    }

    public function destroy($id)
    {
        $categoria = Categoria::findOrFail($id);

        // verificar si tiene productos activos
        $hasProductos = $categoria->productos()->where('estado', true)->exists();
        if ($hasProductos) {
            return response()->json([
                'message' => 'No se puede desactivar la categoría: tiene productos activos'
            ], 400);
        }

        $categoria->estado = false;
        $categoria->save();

        return response()->json(['message' => 'Categoría desactivada']);
    }
}
