<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Usuario;

class UsuarioController extends Controller
{
    public function index()
    {
        return response()->json(Usuario::where('estado', true)->get());
    }

    public function store(Request $request)
    {
        $request->validate([
            'idRol' => 'required|exists:rol,idRol',
            'nombre' => 'required|string|max:100',
            'email' => 'required|email|unique:usuario,email',
            'password' => 'required|min:8',
        ]);

        $usuario = Usuario::create([
            'idRol' => $request->idRol,
            'nombre' => $request->nombre,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'estado' => true
        ]);

        return response()->json($usuario, 201);
    }

    public function show($id)
    {
        return response()->json(Usuario::findOrFail($id));
    }

    public function update(Request $request, $id)
    {
        $usuario = Usuario::findOrFail($id);

        $usuario->update($request->only('idRol', 'nombre', 'email', 'estado'));

        return response()->json($usuario);
    }

    public function destroy($id)
    {
        $usuario = Usuario::findOrFail($id);
        $usuario->estado = 0;
        $usuario->save();

        return response()->json(['message' => 'Usuario desactivado']);
    }
}
