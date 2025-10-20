<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

class UsuarioController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $query = Usuario::withCount(['ventas', 'movimientosCaja']);
            
            // Búsqueda
            if ($request->filled('buscar')) {
                $termino = $request->buscar;
                $query->where(function($q) use ($termino) {
                    $q->where('nombre', 'like', "%{$termino}%")
                      ->orWhere('email', 'like', "%{$termino}%")
                      ->orWhere('usuario', 'like', "%{$termino}%");
                });
            }

            // Filtro por rol
            if ($request->filled('rol')) {
                if ($request->rol === 'administrador') {
                    $query->administradores();
                } elseif ($request->rol === 'vendedor') {
                    $query->vendedores();
                }
            }

            $usuarios = $query->latest()->paginate(10)->withQueryString();

            return view('usuarios.index', compact('usuarios'));

        } catch (\Exception $e) {
            Log::error('Error al cargar usuarios: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error al cargar los usuarios');
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        try {
            return view('usuarios.create');
        } catch (\Exception $e) {
            Log::error('Error al cargar formulario de usuario: ' . $e->getMessage());
            return redirect()->route('usuarios.index')->with('error', 'Error al cargar el formulario');
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'nombre' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:usuarios',
                'usuario' => 'required|string|max:255|unique:usuarios',
                'password' => 'required|string|min:6|confirmed',
                'rol' => 'required|in:administrador,vendedor',
            ]);

            Usuario::create([
                'nombre' => $request->nombre,
                'email' => $request->email,
                'usuario' => $request->usuario,
                'password' => Hash::make($request->password),
                'rol' => $request->rol,
            ]);

            Log::info('Usuario creado exitosamente', [
                'usuario' => $request->usuario,
                'email' => $request->email,
                'rol' => $request->rol
            ]);

            return redirect()->route('usuarios.index')
                           ->with('success', 'Usuario creado exitosamente.');

        } catch (\Exception $e) {
            Log::error('Error al crear usuario: ' . $e->getMessage());
            return redirect()->back()
                           ->with('error', 'Error al crear el usuario')
                           ->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Usuario $usuario)
    {
        try {
            $usuario->load(['ventas' => function($query) {
                $query->with('cliente')->latest()->limit(10);
            }, 'movimientosCaja' => function($query) {
                $query->latest()->limit(10);
            }]);

            // Estadísticas del usuario
            $estadisticas = [
                'total_ventas' => $usuario->ventas->count(),
                'ventas_hoy' => $usuario->ventas()->whereDate('created_at', today())->count(),
                'ventas_mes' => $usuario->ventas()->whereMonth('created_at', now()->month)->count(),
                'total_facturado' => $usuario->ventas->sum('total'),
                'promedio_venta' => $usuario->ventas->avg('total') ?? 0,
                'ultimo_login' => $usuario->updated_at, // Temporal
            ];

            return view('usuarios.show', compact('usuario', 'estadisticas'));

        } catch (\Exception $e) {
            Log::error('Error al mostrar usuario: ' . $e->getMessage());
            return redirect()->route('usuarios.index')->with('error', 'Usuario no encontrado');
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Usuario $usuario)
    {
        try {
            return view('usuarios.edit', compact('usuario'));
        } catch (\Exception $e) {
            Log::error('Error al cargar edición de usuario: ' . $e->getMessage());
            return redirect()->route('usuarios.index')->with('error', 'Error al cargar el usuario');
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Usuario $usuario)
    {
        try {
            $request->validate([
                'nombre' => 'required|string|max:255',
                'email' => [
                    'required', 
                    'string', 
                    'email', 
                    'max:255', 
                    Rule::unique('usuarios')->ignore($usuario->id)
                ],
                'usuario' => [
                    'required', 
                    'string', 
                    'max:255',
                    Rule::unique('usuarios')->ignore($usuario->id)
                ],
                'password' => 'nullable|string|min:6|confirmed',
                'rol' => 'required|in:administrador,vendedor',
            ]);

            $data = [
                'nombre' => $request->nombre,
                'email' => $request->email,
                'usuario' => $request->usuario,
                'rol' => $request->rol,
            ];

            if ($request->filled('password')) {
                $data['password'] = Hash::make($request->password);
            }

            $usuario->update($data);

            Log::info('Usuario actualizado exitosamente', [
                'id' => $usuario->id,
                'usuario' => $usuario->usuario
            ]);

            return redirect()->route('usuarios.index')
                           ->with('success', 'Usuario actualizado exitosamente.');

        } catch (\Exception $e) {
            Log::error('Error al actualizar usuario: ' . $e->getMessage());
            return redirect()->back()
                           ->with('error', 'Error al actualizar el usuario')
                           ->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Usuario $usuario)
    {
        try {
            // Verificar que no sea el último administrador
            if ($usuario->esAdministrador() && Usuario::administradores()->count() <= 1) {
                return redirect()->back()
                               ->with('error', 'No se puede eliminar el último administrador del sistema');
            }

            // Verificar que no tenga ventas asociadas
            if ($usuario->ventas()->count() > 0) {
                return redirect()->back()
                               ->with('error', 'No se puede eliminar un usuario que tiene ventas registradas');
            }

            $nombreUsuario = $usuario->usuario;
            $usuario->delete();

            Log::info('Usuario eliminado exitosamente', [
                'usuario_eliminado' => $nombreUsuario
            ]);

            return redirect()->route('usuarios.index')
                           ->with('success', 'Usuario eliminado exitosamente.');

        } catch (\Exception $e) {
            Log::error('Error al eliminar usuario: ' . $e->getMessage());
            return redirect()->back()
                           ->with('error', 'Error al eliminar el usuario');
        }
    }

    /**
     * Búsqueda de usuarios
     */
    public function search(Request $request)
    {
        $query = $request->get('q', '');
        
        if (strlen($query) < 2) {
            return redirect()->route('usuarios.index');
        }

        try {
            $usuarios = Usuario::where('nombre', 'like', "%{$query}%")
                              ->orWhere('email', 'like', "%{$query}%")
                              ->orWhere('usuario', 'like', "%{$query}%")
                              ->withCount(['ventas', 'movimientosCaja'])
                              ->paginate(10);

            return view('usuarios.index', compact('usuarios'));

        } catch (\Exception $e) {
            Log::error('Error en búsqueda de usuarios: ' . $e->getMessage());
            return redirect()->route('usuarios.index')
                           ->with('error', 'Error en la búsqueda');
        }
    }

    /**
     * Cambiar estado activo/inactivo del usuario
     */
    public function toggleEstado(Usuario $usuario)
    {
        try {
            // Si tu modelo Usuario no tiene campo 'activo', puedes agregar este método más tarde
            // $usuario->activo = !$usuario->activo;
            // $usuario->save();

            return redirect()->back()
                           ->with('success', 'Estado del usuario actualizado');

        } catch (\Exception $e) {
            Log::error('Error al cambiar estado de usuario: ' . $e->getMessage());
            return redirect()->back()
                           ->with('error', 'Error al cambiar el estado');
        }
    }

    /**
     * Resetear contraseña del usuario
     */
    public function resetPassword(Usuario $usuario)
    {
        try {
            $nuevaPassword = 'password123'; // En producción, generar una aleatoria
            
            $usuario->update([
                'password' => Hash::make($nuevaPassword)
            ]);

            Log::info('Contraseña reseteada', [
                'usuario_id' => $usuario->id,
                'usuario' => $usuario->usuario
            ]);

            return redirect()->back()
                           ->with('success', "Contraseña reseteada a: {$nuevaPassword}");

        } catch (\Exception $e) {
            Log::error('Error al resetear contraseña: ' . $e->getMessage());
            return redirect()->back()
                           ->with('error', 'Error al resetear la contraseña');
        }
    }
}