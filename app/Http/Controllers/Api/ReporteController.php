<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Reporte;

class ReporteController extends Controller
{
    public function index()
    {
        return response()->json(Reporte::all());
    }

    public function store(Request $request)
    {
        $request->validate([
            'idUsuario' => 'required|exists:usuario,idUsuario',
            'titulo' => 'required|string|max:150',
            'descripcion' => 'required|string',
        ]);

        $reporte = Reporte::create($request->all());

        return response()->json($reporte, 201);
    }

    public function show($id)
    {
        return response()->json(Reporte::findOrFail($id));
    }

    public function update(Request $request, $id)
    {
        $reporte = Reporte::findOrFail($id);
        $reporte->update($request->all());

        return response()->json($reporte);
    }

    public function destroy($id)
    {
        $reporte = Reporte::findOrFail($id);
        $reporte->delete();

        return response()->json(['message' => 'Reporte eliminado']);
    }
}
