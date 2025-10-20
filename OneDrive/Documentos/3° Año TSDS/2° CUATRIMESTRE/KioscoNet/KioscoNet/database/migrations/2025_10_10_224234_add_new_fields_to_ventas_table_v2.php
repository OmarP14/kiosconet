<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * MIGRACIÓN SUPER SIMPLE
     * Esta vez NO usamos ->after() para evitar errores
     */
    public function up(): void
    {
        Schema::table('ventas', function (Blueprint $table) {
            
            // Solo agregar si NO existe
            if (!Schema::hasColumn('ventas', 'lista_precios')) {
                $table->string('lista_precios', 20)->default('minorista');
            }
            
            if (!Schema::hasColumn('ventas', 'fecha_venta')) {
                $table->timestamp('fecha_venta')->nullable();
            }
            
            if (!Schema::hasColumn('ventas', 'subtotal')) {
                $table->decimal('subtotal', 10, 2)->default(0);
            }
            
            if (!Schema::hasColumn('ventas', 'descuento_porcentaje')) {
                $table->decimal('descuento_porcentaje', 5, 2)->default(0);
            }
            
            if (!Schema::hasColumn('ventas', 'descuento_monto')) {
                $table->decimal('descuento_monto', 10, 2)->default(0);
            }
            
            if (!Schema::hasColumn('ventas', 'comision')) {
                $table->decimal('comision', 10, 2)->default(0);
            }
            
            if (!Schema::hasColumn('ventas', 'observaciones')) {
                $table->text('observaciones')->nullable();
            }
            
            if (!Schema::hasColumn('ventas', 'observaciones_cc')) {
                $table->text('observaciones_cc')->nullable();
            }
            
            if (!Schema::hasColumn('ventas', 'saldo_anterior')) {
                $table->decimal('saldo_anterior', 10, 2)->nullable();
            }
            
            if (!Schema::hasColumn('ventas', 'nuevo_saldo')) {
                $table->decimal('nuevo_saldo', 10, 2)->nullable();
            }
            
            if (!Schema::hasColumn('ventas', 'impreso')) {
                $table->boolean('impreso')->default(false);
            }
            
            if (!Schema::hasColumn('ventas', 'fecha_impresion')) {
                $table->timestamp('fecha_impresion')->nullable();
            }
            
            if (!Schema::hasColumn('ventas', 'cantidad_reimpresiones')) {
                $table->integer('cantidad_reimpresiones')->default(0);
            }
            
            if (!Schema::hasColumn('ventas', 'token_comprobante')) {
                $table->string('token_comprobante', 50)->nullable()->unique();
            }
            
            if (!Schema::hasColumn('ventas', 'ultimos_digitos')) {
                $table->string('ultimos_digitos', 4)->nullable();
            }
            
            if (!Schema::hasColumn('ventas', 'banco')) {
                $table->string('banco', 50)->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::table('ventas', function (Blueprint $table) {
            $columnas = [
                'lista_precios',
                'fecha_venta',
                'subtotal',
                'descuento_porcentaje',
                'descuento_monto',
                'comision',
                'observaciones',
                'observaciones_cc',
                'saldo_anterior',
                'nuevo_saldo',
                'impreso',
                'fecha_impresion',
                'cantidad_reimpresiones',
                'token_comprobante',
                'ultimos_digitos',
                'banco',
            ];
            
            foreach ($columnas as $columna) {
                if (Schema::hasColumn('ventas', $columna)) {
                    $table->dropColumn($columna);
                }
            }
        });
    }
};