<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserController extends Controller
{
    /**
     * Listar usuarios
     */

    public function show()
    {
        return view('usuarios');
    }
    public function index()
    {    
        try {
            $usuarios = User::with('padre')
                ->orderBy('id', 'desc')
                ->get();
            return response()->json([
                'success' => true,
                'usuarios' => $usuarios
            ]);

        } catch (\Exception $e) {

            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile()
            ], 500);
        }
    }


    /**
     * Crear usuario
     */
    public function store(Request $request)
    {
        $request->validate([
            'firts_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'email' => 'required|email|max:150|unique:users,email',
            'username' => 'required|string|max:100|unique:users,username',
            'password' => 'required|string|min:6',
        ]);

        try {

            $usuario = User::create([
                'firts_name' => $request->firts_name,
                'last_name' => $request->last_name,
                'token' => Str::random(10),
                'email' => $request->email,
                'username' => $request->username,
                'img' => 'none.png',
                'rol_id' => 2,
                'id_user_padre' => 1,
                'password' => Hash::make($request->password),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Usuario creado correctamente.',
                'usuario' => $usuario
            ], 201);

        } catch (\Exception $e) {

            return response()->json([
                'success' => false,
                'message' => 'No fue posible crear el usuario.',
                'error' => $e->getMessage()
            ], 500);
        }
    }


    /**
     * Actualizar usuario
     */
    public function update(Request $request, $id)
    {
        $usuario = User::findOrFail($id);

        $request->validate([
            'firts_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'email' => 'required|email|max:150|unique:users,email,' . $id,
            'username' => 'required|string|max:100|unique:users,username,' . $id,

        ]);

        try {

            $usuario->firts_name = $request->firts_name;
            $usuario->last_name = $request->last_name;
            $usuario->email = $request->email;
            $usuario->username = $request->username;

            if ($request->filled('password')) {
                $usuario->password = Hash::make($request->password);
            }

            $usuario->save();

            return response()->json([
                'success' => true,
                'message' => 'Usuario actualizado correctamente.',
                'usuario' => $usuario
            ]);

        } catch (\Exception $e) {

            return response()->json([
                'success' => false,
                'message' => 'No fue posible actualizar el usuario.',
                'error' => $e->getMessage()
            ], 500);
        }
    }


    /**
     * Activar / desactivar usuario
     */
    public function cambiarEstado($id)
    {
        $usuario = User::findOrFail($id);

        $usuario->estado = $usuario->estado == 1 ? 0 : 1;

        $usuario->save();

        return response()->json([
            'success' => true,
            'message' => $usuario->estado == 1
                ? 'Usuario activado correctamente.'
                : 'Usuario desactivado correctamente.',
            'estado' => $usuario->estado
        ]);
    }
}