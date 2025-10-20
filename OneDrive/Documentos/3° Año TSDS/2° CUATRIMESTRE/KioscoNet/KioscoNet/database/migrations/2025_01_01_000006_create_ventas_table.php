<?php
/*
 * ARCHIVO: database/migrations/xxxx_xx_xx_update_ventas_table.php
 * 
 * CREAR ESTA MIGRACIÓN CON:
 * php artisan make:migration update_ventas_table --table=ventas
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Ejecutar la migración
     * Actualiza tabla ventas para soportar todos los métodos de pago
     */
    public function up()
    {
        Schema::table('ventas', function (Blueprint $table) {
            // Campos para método de pago efectivo
            $table->decimal('monto_recibido', 10, 2)->nullable()->after('total');
            $table->decimal('vuelto', 10, 2)->default(0)->after('monto_recibido');
            
            // Campos para método de pago tarjeta
            $table->enum('tipo_tarjeta', ['debito', 'credito'])->nullable()->after('metodo_pago');
            $table->string('numero_tarjeta', 4)->nullable()->after('tipo_tarjeta')->comment('Últimos 4 dígitos');
            $table->string('codigo_autorizacion', 10)->nullable()->after('numero_tarjeta');
            
            // Campos para método de pago transferencia
            $table->string('numero_transferencia', 20)->nullable()->after('codigo_autorizacion');
            $table->string('banco_origen', 50)->nullable()->after('numero_transferencia');
            $table->date('fecha_transferencia')->nullable()->after('banco_origen');
            $table->time('hora_transferencia')->nullable()->after('fecha_transferencia');
            
            // Campos para cuenta corriente
            $table->text('observaciones_cc')->nullable()->after('hora_transferencia');
            
            // Campos de auditoría adicionales
            $table->string('estado', 20)->default('completada')->after('observaciones_cc');
            $table->timestamp('fecha_procesamiento')->nullable()->after('estado');
        });
    }

    /**
     * Revertir la migración
     */
    public function down()
    {
        Schema::table('ventas', function (Blueprint $table) {
            $table->dropColumn([
                'monto_recibido',
                'vuelto', 
                'tipo_tarjeta',
                'numero_tarjeta',
                'codigo_autorizacion',
                'numero_transferencia',
                'banco_origen',
                'fecha_transferencia',
                'hora_transferencia',
                'observaciones_cc',
                'estado',
                'fecha_procesamiento'
            ]);
        });
    }
};