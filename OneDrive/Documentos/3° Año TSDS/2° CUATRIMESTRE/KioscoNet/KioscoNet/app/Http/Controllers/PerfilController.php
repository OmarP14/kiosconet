<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class PerfilController extends Controller
{
    /**
     * Mostrar el perfil del usuario autenticado
     */
    public function index()
    {
        try {
            // Por ahora, mientras no tengas autenticación, usa un usuario de prueba
            // $usuario = Auth::user(); // Esto causará el error null
            
            // TEMPORAL: usar el primer usuario de la BD para testing
            $usuario = \App\Models\Usuario::first();
            
            if (!$usuario) {
                return redirect()->route('home')->with('error', 'No hay usuarios en el sistema');
            }

            return view('perfil.index', compact('usuario'));

        } catch (\Exception $e) {
            \Log::error('Error al cargar perfil: ' . $e->getMessage());
            return redirect()->route('home')->with('error', 'Error al cargar el perfil');
        }
    }

    /**
     * Mostrar formulario de edición del perfil
     */
    public function edit()
    {
        try {
            // TEMPORAL: usar el primer usuario
            $usuario = \App\Models\Usuario::first();
            
            if (!$usuario) {
                return redirect()->route('home')->with('error', 'Usuario no encontrado');
            }

            return view('perfil.edit', compact('usuario'));

        } catch (\Exception $e) {
            \Log::error('Error al cargar edición de perfil: ' . $e->getMessage());
            return redirect()->route('perfil')->with('error', 'Error al cargar el formulario');
        }
    }

    /**
     * Actualizar el perfil del usuario
     */
    public function update(Request $request)
    {
        try {
            // TEMPORAL: usar el primer usuario
            $usuario = \App\Models\Usuario::first();
            
            if (!$usuario) {
                return redirect()->route('home')->with('error', 'Usuario no encontrado');
            }

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
            ]);

            $usuario->update([
                'nombre' => $request->nombre,
                'email' => $request->email,
                'usuario' => $request->usuario,
            ]);

            return redirect()->route('perfil')
                           ->with('success', 'Perfil actualizado exitosamente');

        } catch (\Exception $e) {
            \Log::error('Error al actualizar perfil: ' . $e->getMessage());
            return redirect()->back()
                           ->with('error', 'Error al actualizar el perfil')
                           ->withInput();
        }
    }

    /**
     * Cambiar contraseña del usuario
     */
    public function cambiarPassword(Request $request)
    {
        try {
            // TEMPORAL: usar el primer usuario
            $usuario = \App\Models\Usuario::first();
            
            if (!$usuario) {
                return redirect()->route('home')->with('error', 'Usuario no encontrado');
            }

            $request->validate([
                'password_actual' => 'required',
                'password' => 'required|string|min:6|confirmed',
            ]);

            // Verificar contraseña actual
            if (!Hash::check($request->password_actual, $usuario->password)) {
                return redirect()->back()
                               ->with('error', 'La contraseña actual no es correcta');
            }

            // Actualizar contraseña
            $usuario->update([
                'password' => Hash::make($request->password)
            ]);

            return redirect()->route('perfil')
                           ->with('success', 'Contraseña actualizada exitosamente');

        } catch (\Exception $e) {
            \Log::error('Error al cambiar contraseña: ' . $e->getMessage());
            return redirect()->back()
                           ->with('error', 'Error al cambiar la contraseña');
        }
    }

    /**
     * Estadísticas del usuario (para vendedores)
     */
    public function estadisticas()
    {
        try {
            // TEMPORAL: usar el primer usuario
            $usuario = \App\Models\Usuario::first();
            
            if (!$usuario) {
                return redirect()->route('home')->with('error', 'Usuario no encontrado');
            }

            // Estadísticas de ventas del usuario
            $ventasHoy = $usuario->ventas()->whereDate('created_at', today())->count();
            $ventasMes = $usuario->ventas()->whereMonth('created_at', now()->month)->count();
            $totalVentasHoy = $usuario->ventas()->whereDate('created_at', today())->sum('total');
            $totalVentasMes = $usuario->ventas()->whereMonth('created_at', now()->month)->sum('total');

            // Top productos vendidos por este usuario
            $topProductos = \App\Models\DetalleVenta::select('producto_id', \DB::raw('SUM(cantidad) as total_vendido'))
                ->whereHas('venta', function($query) use ($usuario) {
                    $query->where('usuario_id', $usuario->id);
                })
                ->with('producto')
                ->groupBy('producto_id')
                ->orderBy('total_vendido', 'desc')
                ->limit(5)
                ->get();

            $estadisticas = [
                'ventas_hoy' => $ventasHoy,
                'ventas_mes' => $ventasMes,
                'total_ventas_hoy' => $totalVentasHoy,
                'total_ventas_mes' => $totalVentasMes,
                'promedio_venta_hoy' => $ventasHoy > 0 ? $totalVentasHoy / $ventasHoy : 0,
                'promedio_venta_mes' => $ventasMes > 0 ? $totalVentasMes / $ventasMes : 0,
                'top_productos' => $topProductos
            ];

            return view('perfil.estadisticas', compact('usuario', 'estadisticas'));

        } catch (\Exception $e) {
            \Log::error('Error al cargar estadísticas: ' . $e->getMessage());
            return redirect()->route('perfil')
                           ->with('error', 'Error al cargar las estadísticas');
        }
    }

    /**
     * Ver actividad reciente del usuario
     */
    public function actividad()
    {
        try {
            // TEMPORAL: usar el primer usuario
            $usuario = \App\Models\Usuario::first();
            
            if (!$usuario) {
                return redirect()->route('home')->with('error', 'Usuario no encontrado');
            }

            // Obtener las últimas ventas del usuario
            $ventasRecientes = $usuario->ventas()
                                     ->with(['cliente', 'detalles.producto'])
                                     ->orderBy('created_at', 'desc')
                                     ->limit(10)
                                     ->get();

            // Obtener movimientos de caja del usuario
            $movimientosCaja = $usuario->movimientosCaja()
                                      ->orderBy('created_at', 'desc')
                                      ->limit(10)
                                      ->get();

            return view('perfil.actividad', compact('usuario', 'ventasRecientes', 'movimientosCaja'));

        } catch (\Exception $e) {
            \Log::error('Error al cargar actividad: ' . $e->getMessage());
            return redirect()->route('perfil')
                           ->with('error', 'Error al cargar la actividad');
        }
    }

    /**
     * Configuración del usuario
     */
    public function configuracion()
    {
        try {
            // TEMPORAL: usar el primer usuario
            $usuario = \App\Models\Usuario::first();
            
            if (!$usuario) {
                return redirect()->route('home')->with('error', 'Usuario no encontrado');
            }

            return view('perfil.configuracion', compact('usuario'));

        } catch (\Exception $e) {
            \Log::error('Error al cargar configuración: ' . $e->getMessage());
            return redirect()->route('perfil')
                           ->with('error', 'Error al cargar la configuración');
        }
    }
}