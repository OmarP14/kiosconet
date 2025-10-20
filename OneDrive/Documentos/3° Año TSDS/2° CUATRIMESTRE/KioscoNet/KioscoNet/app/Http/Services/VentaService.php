// Crear app/Services/VentaService.php
namespace App\Services;

class VentaService
{
    public function crearVenta(array $datos)
    {
        // Mover toda la lógica de store() aquí
        // Esto hace el controlador más delgado y testeable
    }
    
    public function anularVenta(Venta $venta, string $motivo)
    {
        // Lógica de anulación
    }
    
    public function calcularTotal(array $productos)
    {
        // Cálculo de totales
    }
}

// En el controlador:
public function __construct(private VentaService $ventaService)
{
}

public function store(Request $request)
{
    $validated = $request->validate($this->reglas());
    
    try {
        $venta = $this->ventaService->crearVenta($validated);
        return $this->respuestaExitosa(
            "Venta #{$venta->numero} procesada exitosamente",
            ['venta' => $venta],
            route('ventas.show', $venta)
        );
    } catch (\Exception $e) {
        return $this->respuestaError($e->getMessage());
    }
}