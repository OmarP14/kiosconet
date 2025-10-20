<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * ESTA MIGRACIÓN AGREGA CAMPOS NUEVOS A LA TABLA VENTAS
     * - Lista de precios (minorista, mayorista, especial)
     * - Campos para descuentos
     * - Campos para comisiones
     * - Campos para cuenta corriente
     * - Campos para imprimir tickets
     */
    public function up(): void
    {
        Schema::table('ventas', function (Blueprint $table) {
            
            // Campo para saber qué lista de precios usamos
            if (!Schema::hasColumn('ventas', 'lista_precios')) {
                $table->string('lista_precios', 20)->default('minorista')->after('cliente_id');
            }
            
            // Fecha de la venta
            if (!Schema::hasColumn('ventas', 'fecha_venta')) {
                $table->timestamp('fecha_venta')->nullable()->after('lista_precios');
            }
            
            // Subtotal (antes de descuentos)
            if (!Schema::hasColumn('ventas', 'subtotal')) {
                $table->decimal('subtotal', 10, 2)->default(0)->after('fecha_venta');
            }
            
            // Descuento en porcentaje (ejemplo: 10%)
            if (!Schema::hasColumn('ventas', 'descuento_porcentaje')) {
                $table->decimal('descuento_porcentaje', 5, 2)->default(0)->after('subtotal');
            }
            
            // Descuento en dinero (ejemplo: $50)
            if (!Schema::hasColumn('ventas', 'descuento_monto')) {
                $table->decimal('descuento_monto', 10, 2)->default(0)->after('descuento_porcentaje');
            }
            
            // Comisión por usar tarjeta
            if (!Schema::hasColumn('ventas', 'comision')) {
                $table->decimal('comision', 10, 2)->default(0)->after('total');
            }
            
            // Notas u observaciones de la venta
            if (!Schema::hasColumn('ventas', 'observaciones')) {
                $table->text('observaciones')->nullable()->after('comision');
            }
            
            // Saldo anterior del cliente (cuenta corriente)
            if (!Schema::hasColumn('ventas', 'saldo_anterior')) {
                $table->decimal('saldo_anterior', 10, 2)->nullable()->after('observaciones_cc');
            }
            
            // Nuevo saldo del cliente (cuenta corriente)
            if (!Schema::hasColumn('ventas', 'nuevo_saldo')) {
                $table->decimal('nuevo_saldo', 10, 2)->nullable()->after('saldo_anterior');
            }
            
            // ¿Ya se imprimió el ticket?
            if (!Schema::hasColumn('ventas', 'impreso')) {
                $table->boolean('impreso')->default(false)->after('estado');
            }
            
            // ¿Cuándo se imprimió?
            if (!Schema::hasColumn('ventas', 'fecha_impresion')) {
                $table->timestamp('fecha_impresion')->nullable()->after('impreso');
            }
            
            // ¿Cuántas veces se reimprimió?
            if (!Schema::hasColumn('ventas', 'cantidad_reimpresiones')) {
                $table->integer('cantidad_reimpresiones')->default(0)->after('fecha_impresion');
            }
            
            // Código secreto para ver el comprobante online
            if (!Schema::hasColumn('ventas', 'token_comprobante')) {
                $table->string('token_comprobante', 50)->nullable()->unique()->after('cantidad_reimpresiones');
            }
            
            // Cambiar nombres de columnas si existen con otro nombre
            if (Schema::hasColumn('ventas', 'numero_tarjeta') && !Schema::hasColumn('ventas', 'ultimos_digitos')) {
                $table->renameColumn('numero_tarjeta', 'ultimos_digitos');
            }
            
            if (Schema::hasColumn('ventas', 'banco_origen') && !Schema::hasColumn('ventas', 'banco')) {
                $table->renameColumn('banco_origen', 'banco');
            }
        });
    }

    /**
     * SI ALGO SALE MAL, ESTO BORRA LOS CAMBIOS
     */
    public function down(): void
    {
        Schema::table('ventas', function (Blueprint $table) {
            // Lista de columnas que agregamos
            $columnasNuevas = [
                'lista_precios',
                'fecha_venta',
                'subtotal',
                'descuento_porcentaje',
                'descuento_monto',
                'comision',
                'observaciones',
                'saldo_anterior',
                'nuevo_saldo',
                'impreso',
                'fecha_impresion',
                'cantidad_reimpresiones',
                'token_comprobante',
            ];
            
            // Borrar cada columna si existe
            foreach ($columnasNuevas as $columna) {
                if (Schema::hasColumn('ventas', $columna)) {
                    $table->dropColumn($columna);
                }
            }
        });
    }
};