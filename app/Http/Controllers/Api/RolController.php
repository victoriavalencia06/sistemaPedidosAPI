<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Rol;

class RolController extends Controller
{
    /**
     * Mostrar todos los roles activos.
     */
    public function index()
    {
        $roles = Rol::where('estado', true)->get();

        return response()->json([
            'success' => true,
            'data' => $roles
        ], 200);
    }

    /**
     * Crear un nuevo rol.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:50',
        ]);

        $rol = Rol::create([
            'nombre' => $request->nombre,
            'estado' => true
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Rol creado correctamente.',
            'data' => $rol
        ], 201);
    }

    /**
     * Mostrar un rol por ID.
     */
    public function show($id)
    {
        $rol = Rol::find($id);

        if (!$rol) {
            return response()->json([
                'success' => false,
                'message' => 'Rol no encontrado.'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $rol
        ], 200);
    }

    /**
     * Actualizar un rol.
     */
    public function update(Request $request, $id)
    {
        $rol = Rol::find($id);

        if (!$rol) {
            return response()->json([
                'success' => false,
                'message' => 'Rol no encontrado.'
            ], 404);
        }

        $request->validate([
            'nombre' => 'sometimes|required|string|max:50',
            'estado' => 'sometimes|boolean'
        ]);

        $rol->update($request->only('nombre', 'estado'));

        return response()->json([
            'success' => true,
            'message' => 'Rol actualizado correctamente.',
            'data' => $rol
        ], 200);
    }

    /**
     * Desactivar (no eliminar) un rol.
     */
    public function destroy($id)
    {
        $rol = Rol::find($id);

        if (!$rol) {
            return response()->json([
                'success' => false,
                'message' => 'Rol no encontrado.'
            ], 404);
        }

        // Desactivar
        $rol->estado = 0;
        $rol->save();

        return response()->json([
            'success' => true,
            'message' => 'Rol desactivado correctamente.'
        ], 200);
    }
}
