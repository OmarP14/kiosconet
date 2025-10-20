<?php

namespace App\Http\Controllers;

use App\Models\Proveedor;
use Illuminate\Http\Request;

class ProveedorController extends Controller
{
    public function index(Request $request)
    {
        $query = Proveedor::withCount('productos');
        
        if ($request->filled('buscar')) {
            $query->buscar($request->buscar);
        }

        $proveedores = $query->latest()->paginate(15);

        return view('proveedores.index', compact('proveedores'));
    }

    public function create()
    {
        return view('proveedores.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'telefono' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'direccion' => 'nullable|string|max:255',
        ]);

        Proveedor::create($request->all());

        return redirect()->route('proveedores.index')
                        ->with('success', 'Proveedor creado exitosamente.');
    }

    public function show(Proveedor $proveedor)
    {
        $proveedor->load(['productos' => function ($query) {
            $query->with('precios')->latest();
        }]);
        
        return view('proveedores.show', compact('proveedor'));
    }

    public function edit(Proveedor $proveedor)
    {
        return view('proveedores.edit', compact('proveedor'));
    }

    public function update(Request $request, Proveedor $proveedor)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'telefono' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'direccion' => 'nullable|string|max:255',
        ]);

        $proveedor->update($request->all());

        return redirect()->route('proveedores.index')
                        ->with('success', 'Proveedor actualizado exitosamente.');
    }

    public function destroy(Proveedor $proveedor)
    {
        if ($proveedor->productos()->count() > 0) {
            return redirect()->back()
                           ->with('error', 'No se puede eliminar el proveedor porque tiene productos asociados.');
        }

        $proveedor->delete();

        return redirect()->route('proveedores.index')
                        ->with('success', 'Proveedor eliminado exitosamente.');
    }

    public function productos(Proveedor $proveedor)
    {
        $productos = $proveedor->productos()
                             ->with('precios')
                             ->latest()
                             ->paginate(15);

        return view('proveedores.productos', compact('proveedor', 'productos'));
    }
}