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
        $cat = Categoria::findOrFail($id);
        $cat->estado = 0;
        $cat->save();

        return response()->json(['message' => 'Categoría desactivada']);
    }
}
