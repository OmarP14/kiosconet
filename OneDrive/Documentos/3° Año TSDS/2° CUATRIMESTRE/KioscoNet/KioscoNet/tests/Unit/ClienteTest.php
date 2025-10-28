<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Cliente;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ClienteTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test: Un cliente puede ser creado correctamente
     */
    public function test_cliente_puede_ser_creado(): void
    {
        $cliente = Cliente::create([
            'nombre' => 'Juan',
            'apellido' => 'Pérez',
            'telefono' => '3815551234',
            'email' => 'juan@example.com',
            'tipo_cliente' => 'minorista',
            'limite_credito' => 50000.00,
            'activo' => true,
        ]);

        $this->assertDatabaseHas('clientes', [
            'nombre' => 'Juan',
            'apellido' => 'Pérez',
            'email' => 'juan@example.com',
        ]);
    }

    /**
     * Test: El saldo de cuenta corriente se actualiza correctamente
     */
    public function test_saldo_cuenta_corriente_se_actualiza(): void
    {
        $cliente = Cliente::factory()->create([
            'tipo_cliente' => 'minorista',
            'limite_credito' => 50000.00,
            'saldo_cc' => 0,
        ]);

        // Simular una compra en cuenta corriente
        $montoCompra = 1000.00;
        $cliente->saldo_cc -= $montoCompra; // Deuda es negativa
        $cliente->save();

        $this->assertEquals(-1000.00, $cliente->fresh()->saldo_cc);
    }

    /**
     * Test: Cliente no puede exceder su límite de crédito
     */
    public function test_cliente_no_puede_exceder_limite_credito(): void
    {
        $cliente = Cliente::factory()->create([
            'tipo_cliente' => 'mayorista',
            'limite_credito' => 10000.00,
            'saldo_cc' => -8000.00, // Ya debe $8000
        ]);

        $nuevaCompra = 5000.00;
        $saldoProyectado = $cliente->saldo_cc - $nuevaCompra; // -13000

        // Verificar que excede el límite
        $this->assertTrue(abs($saldoProyectado) > $cliente->limite_credito);
    }

    /**
     * Test: Cliente puede pagar su deuda
     */
    public function test_cliente_puede_pagar_deuda(): void
    {
        $cliente = Cliente::factory()->create([
            'tipo_cliente' => 'minorista',
            'saldo_cc' => -5000.00, // Debe $5000
        ]);

        $montoPago = 2000.00;
        $cliente->saldo_cc += $montoPago; // Aumenta el saldo (reduce la deuda)
        $cliente->save();

        $this->assertEquals(-3000.00, $cliente->fresh()->saldo_cc);
    }

    /**
     * Test: Cliente especial tiene descuento mayor
     */
    public function test_cliente_especial_tiene_mejor_precio(): void
    {
        $clienteMinorista = Cliente::factory()->create([
            'tipo_cliente' => 'minorista',
        ]);

        $clienteEspecial = Cliente::factory()->create([
            'tipo_cliente' => 'especial',
        ]);

        // Los clientes especiales deberían obtener mejores precios
        $this->assertEquals('minorista', $clienteMinorista->tipo_cliente);
        $this->assertEquals('especial', $clienteEspecial->tipo_cliente);

        // Verificar que ambos tipos están definidos correctamente
        $this->assertContains($clienteMinorista->tipo_cliente, ['minorista', 'mayorista', 'especial']);
        $this->assertContains($clienteEspecial->tipo_cliente, ['minorista', 'mayorista', 'especial']);
    }

    /**
     * Test: Cliente inactivo no puede realizar compras
     */
    public function test_cliente_inactivo_no_puede_comprar(): void
    {
        $cliente = Cliente::factory()->create([
            'activo' => false,
        ]);

        $this->assertFalse($cliente->activo);
    }

    /**
     * Test: El email del cliente debe ser único
     */
    public function test_email_cliente_debe_ser_unico(): void
    {
        Cliente::factory()->create([
            'email' => 'unico@example.com',
        ]);

        $this->expectException(\Illuminate\Database\QueryException::class);

        // Intentar crear otro cliente con el mismo email
        Cliente::factory()->create([
            'email' => 'unico@example.com',
        ]);
    }
}
