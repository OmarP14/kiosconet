<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Venta;
use App\Models\Cliente;
use App\Models\Producto;
use App\Models\Proveedor;
use App\Models\Usuario;

class VentaTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test: Puede crear una venta exitosamente
     */
    public function test_puede_crear_venta_exitosa(): void
    {
        $usuario = Usuario::factory()->create();
        $cliente = Cliente::factory()->create();
        $proveedor = Proveedor::factory()->create();
        $producto = Producto::factory()->create([
            'proveedor_id' => $proveedor->id,
            'stock' => 100,
            'precio_compra' => 100,
        ]);

        $this->actingAs($usuario);

        $datosVenta = [
            'cliente_id' => $cliente->id,
            'lista_precios' => 'minorista',
            'metodo_pago' => 'efectivo',
            'productos' => [
                [
                    'id' => $producto->id,
                    'cantidad' => 5,
                    'precio' => 130.00,
                ]
            ],
            'total' => 650.00,
            'monto_recibido' => 1000.00,
        ];

        $response = $this->postJson(route('api.ventas.store'), $datosVenta);

        $response->assertStatus(201)
                 ->assertJson([
                     'success' => true,
                 ]);

        $this->assertDatabaseHas('ventas', [
            'cliente_id' => $cliente->id,
            'total' => 650.00,
        ]);
    }

    /**
     * Test: No se puede crear venta sin stock suficiente
     */
    public function test_no_puede_vender_sin_stock_suficiente(): void
    {
        $usuario = Usuario::factory()->create();
        $cliente = Cliente::factory()->create();
        $proveedor = Proveedor::factory()->create();
        $producto = Producto::factory()->create([
            'proveedor_id' => $proveedor->id,
            'stock' => 5, // Stock bajo
        ]);

        $this->actingAs($usuario);

        $datosVenta = [
            'cliente_id' => $cliente->id,
            'lista_precios' => 'minorista',
            'metodo_pago' => 'efectivo',
            'productos' => [
                [
                    'id' => $producto->id,
                    'cantidad' => 10, // Más de lo disponible
                    'precio' => 130.00,
                ]
            ],
            'total' => 1300.00,
            'monto_recibido' => 1500.00,
        ];

        $response = $this->postJson(route('api.ventas.store'), $datosVenta);

        $response->assertStatus(422); // Error de validación/lógica
    }

    /**
     * Test: El stock se reduce después de una venta
     */
    public function test_stock_se_reduce_despues_de_venta(): void
    {
        $usuario = Usuario::factory()->create();
        $cliente = Cliente::factory()->create();
        $proveedor = Proveedor::factory()->create();
        $producto = Producto::factory()->create([
            'proveedor_id' => $proveedor->id,
            'stock' => 100,
            'precio_compra' => 100,
        ]);

        $stockInicial = $producto->stock;

        $this->actingAs($usuario);

        $datosVenta = [
            'cliente_id' => $cliente->id,
            'lista_precios' => 'minorista',
            'metodo_pago' => 'efectivo',
            'productos' => [
                [
                    'id' => $producto->id,
                    'cantidad' => 10,
                    'precio' => 130.00,
                ]
            ],
            'total' => 1300.00,
            'monto_recibido' => 1500.00,
        ];

        $this->postJson(route('api.ventas.store'), $datosVenta);

        $producto->refresh();
        $this->assertEquals($stockInicial - 10, $producto->stock);
    }

    /**
     * Test: Una venta puede ser anulada
     */
    public function test_venta_puede_ser_anulada(): void
    {
        $usuario = Usuario::factory()->create();
        $venta = Venta::factory()->create([
            'usuario_id' => $usuario->id,
            'anulada' => false,
        ]);

        $this->actingAs($usuario);

        $response = $this->postJson(route('ventas.anular', $venta->id));

        $response->assertStatus(200);

        $this->assertDatabaseHas('ventas', [
            'id' => $venta->id,
            'anulada' => true,
        ]);
    }

    /**
     * Test: Validación de datos requeridos para crear venta
     */
    public function test_validacion_campos_requeridos_venta(): void
    {
        $usuario = Usuario::factory()->create();
        $this->actingAs($usuario);

        $datosIncompletos = [
            'cliente_id' => null, // Falta cliente
            'productos' => [], // Sin productos
        ];

        $response = $this->postJson(route('api.ventas.store'), $datosIncompletos);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['cliente_id', 'productos']);
    }

    /**
     * Test: Venta en cuenta corriente actualiza saldo del cliente
     */
    public function test_venta_cuenta_corriente_actualiza_saldo_cliente(): void
    {
        $usuario = Usuario::factory()->create();
        $cliente = Cliente::factory()->create([
            'saldo_cc' => 0,
        ]);
        $proveedor = Proveedor::factory()->create();
        $producto = Producto::factory()->create([
            'proveedor_id' => $proveedor->id,
            'stock' => 100,
            'precio_compra' => 100,
        ]);

        $this->actingAs($usuario);

        $datosVenta = [
            'cliente_id' => $cliente->id,
            'lista_precios' => 'minorista',
            'metodo_pago' => 'cuenta_corriente',
            'productos' => [
                [
                    'id' => $producto->id,
                    'cantidad' => 5,
                    'precio' => 130.00,
                ]
            ],
            'total' => 650.00,
        ];

        $this->postJson(route('api.ventas.store'), $datosVenta);

        $cliente->refresh();
        $this->assertEquals(-650.00, $cliente->saldo_cc);
    }
}
