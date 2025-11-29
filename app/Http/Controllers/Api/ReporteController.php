<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Reporte;
use Carbon\Carbon;

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
            'idPedido' => 'nullable|exists:pedido,idPedido',
            'titulo' => 'required|string|max:150',
            'descripcion' => 'nullable|string',
            'tipo' => 'nullable|string|max:50',
            'fechaGeneracion' => 'nullable|date',
        ]);

        $reporte = Reporte::create([
            'idUsuario' => $request->idUsuario,
            'idPedido' => $request->idPedido ?? null,
            'titulo' => $request->titulo,
            'descripcion' => $request->descripcion ?? null,
            'tipo' => $request->tipo ?? null,
            'fechaGeneracion' => $request->fechaGeneracion ?? now(),
        ]);

        return response()->json($reporte, 201);
    }

    public function show($id)
    {
        return response()->json(Reporte::findOrFail($id));
    }

    public function update(Request $request, $id)
    {
        $reporte = Reporte::findOrFail($id);

        $data = $request->validate([
            'idPedido' => 'nullable|exists:pedido,idPedido',
            'idUsuario' => 'nullable|exists:usuario,idUsuario',
            'titulo' => 'nullable|string|max:150',
            'descripcion' => 'nullable|string',
            'tipo' => 'nullable|string|max:50',
            'fechaGeneracion' => 'nullable|date',
            'estado' => 'nullable|boolean',
        ]);

        if (!empty($data['fechaGeneracion'])) {
            $data['fechaGeneracion'] = Carbon::parse($data['fechaGeneracion']);
        }

        $reporte->update($data);
        $reporte->refresh();

        return response()->json($reporte);
    }

    public function destroy($id)
    {
        $reporte = Reporte::findOrFail($id);

        $reporte->estado = 0;
        $reporte->save();

        return response()->json(['message' => 'Reporte desactivado correctamente']);
    }
}
