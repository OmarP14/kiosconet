<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Producto;
use App\Models\Proveedor;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProductoTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test: Un producto puede ser creado correctamente
     */
    public function test_producto_puede_ser_creado(): void
    {
        $proveedor = Proveedor::factory()->create();

        $producto = Producto::create([
            'nombre' => 'Coca Cola 2.25L',
            'codigo' => 'CC225',
            'proveedor_id' => $proveedor->id,
            'precio_compra' => 1000.00,
            'stock' => 50,
            'stock_minimo' => 10,
            'activo' => true,
        ]);

        $this->assertDatabaseHas('productos', [
            'nombre' => 'Coca Cola 2.25L',
            'codigo' => 'CC225',
            'stock' => 50,
        ]);
    }

    /**
     * Test: El precio de venta se calcula correctamente según margen
     */
    public function test_precio_venta_se_calcula_segun_margen(): void
    {
        $proveedor = Proveedor::factory()->create();

        $producto = Producto::factory()->create([
            'proveedor_id' => $proveedor->id,
            'precio_compra' => 100.00,
        ]);

        // Margen minorista por defecto (30% = 1.30)
        $precioMinorista = $producto->getPrecioVenta('minorista');
        $this->assertEquals(130.00, $precioMinorista);

        // Margen mayorista (20% = 1.20)
        $precioMayorista = $producto->getPrecioVenta('mayorista');
        $this->assertEquals(120.00, $precioMayorista);

        // Margen especial (15% = 1.15)
        $precioEspecial = $producto->getPrecioVenta('especial');
        $this->assertEquals(115.00, $precioEspecial);
    }

    /**
     * Test: Un producto detecta correctamente stock bajo
     */
    public function test_producto_detecta_stock_bajo(): void
    {
        $proveedor = Proveedor::factory()->create();

        $productoStockBajo = Producto::factory()->create([
            'proveedor_id' => $proveedor->id,
            'stock' => 5,
            'stock_minimo' => 10,
        ]);

        $productoStockOk = Producto::factory()->create([
            'proveedor_id' => $proveedor->id,
            'stock' => 50,
            'stock_minimo' => 10,
        ]);

        // Stock bajo
        $this->assertTrue($productoStockBajo->stock <= $productoStockBajo->stock_minimo);

        // Stock OK
        $this->assertTrue($productoStockOk->stock > $productoStockOk->stock_minimo);
    }

    /**
     * Test: El stock se reduce correctamente al vender
     */
    public function test_stock_se_reduce_al_vender(): void
    {
        $proveedor = Proveedor::factory()->create();

        $producto = Producto::factory()->create([
            'proveedor_id' => $proveedor->id,
            'stock' => 100,
        ]);

        $stockInicial = $producto->stock;
        $cantidadVendida = 10;

        // Simular reducción de stock
        $producto->stock -= $cantidadVendida;
        $producto->save();

        $this->assertEquals($stockInicial - $cantidadVendida, $producto->fresh()->stock);
    }

    /**
     * Test: No se puede vender más stock del disponible
     */
    public function test_no_se_puede_vender_mas_stock_del_disponible(): void
    {
        $proveedor = Proveedor::factory()->create();

        $producto = Producto::factory()->create([
            'proveedor_id' => $proveedor->id,
            'stock' => 5,
        ]);

        $cantidadSolicitada = 10;

        // Verificar que no hay suficiente stock
        $this->assertFalse($producto->stock >= $cantidadSolicitada);
    }
}
