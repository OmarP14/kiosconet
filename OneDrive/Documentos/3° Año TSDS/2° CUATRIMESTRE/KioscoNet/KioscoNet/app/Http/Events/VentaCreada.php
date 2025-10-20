// Crear app/Events/VentaCreada.php
namespace App\Events;

class VentaCreada
{
    public function __construct(public Venta $venta)
    {
    }
}

// En el controlador después de crear la venta:
event(new VentaCreada($venta));

// Esto permite:
// - Enviar notificaciones
// - Actualizar estadísticas en tiempo real
// - Integración con sistemas externos
// - Generar reportes automáticos