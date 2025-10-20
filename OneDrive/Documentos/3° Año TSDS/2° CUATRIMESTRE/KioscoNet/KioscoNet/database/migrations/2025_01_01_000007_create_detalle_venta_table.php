<?php
/*
 * ARCHIVO: database/migrations/2025_01_01_000007_create_detalle_venta_table.php
 * ACTUALIZADO: Con soporte para métodos de pago completos
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    
    /**
     * Ejecutar la migración
     * Crear tabla detalle_venta con campos adicionales para métodos de pago
     */
    public function up(): void {
        Schema::create('detalle_venta', function (Blueprint $table) {
            // Campos básicos existentes
            $table->id();
            $table->unsignedBigInteger('venta_id');
            $table->unsignedBigInteger('producto_id');
            $table->integer('cantidad')->default(1);
            $table->decimal('precio_unitario', 12, 2)->default(0);
            $table->decimal('subtotal', 12, 2)->default(0);
            
            // Nuevos campos para métodos de pago (se agregarán a tabla ventas)
            // Estos campos van en la tabla ventas, no en detalle_venta
            $table->timestamps();

            // Claves foráneas existentes
            $table->foreign('venta_id')->references('id')->on('ventas')->cascadeOnDelete();
            $table->foreign('producto_id')->references('id')->on('productos')->restrictOnDelete();
            
            // Índices para optimizar consultas
            $table->index(['venta_id', 'producto_id']);
            $table->index('venta_id');
        });
        
        // Agregar campos para métodos de pago a la tabla ventas
        Schema::table('ventas', function (Blueprint $table) {
            // Campos para método de pago efectivo
            $table->decimal('monto_recibido', 12, 2)->nullable()->after('total');
            $table->decimal('vuelto', 12, 2)->default(0)->after('monto_recibido');
            
            // Campos para método de pago tarjeta
            $table->enum('tipo_tarjeta', ['debito', 'credito'])->nullable()->after('metodo_pago');
            $table->string('numero_tarjeta', 4)->nullable()->after('tipo_tarjeta')->comment('Últimos 4 dígitos');
            $table->string('codigo_autorizacion', 20)->nullable()->after('numero_tarjeta');
            
            // Campos para método de pago transferencia
            $table->string('numero_transferencia', 30)->nullable()->after('codigo_autorizacion');
            $table->string('banco_origen', 50)->nullable()->after('numero_transferencia');
            $table->date('fecha_transferencia')->nullable()->after('banco_origen');
            $table->time('hora_transferencia')->nullable()->after('fecha_transferencia');
            
            // Campos para cuenta corriente
            $table->text('observaciones_cc')->nullable()->after('hora_transferencia');
            
            // Campos de auditoría adicionales
            $table->enum('estado_venta', ['completada', 'pendiente', 'anulada'])
                  ->default('completada')->after('observaciones_cc');
            $table->timestamp('fecha_procesamiento')->nullable()->after('estado_venta');
            
            // Campos para anulación
            $table->boolean('anulada')->default(false)->after('fecha_procesamiento');
            $table->unsignedBigInteger('usuario_anulacion_id')->nullable()->after('anulada');
            $table->timestamp('fecha_anulacion')->nullable()->after('usuario_anulacion_id');
            $table->text('motivo_anulacion')->nullable()->after('fecha_anulacion');
            
            // Clave foránea para usuario que anula
            $table->foreign('usuario_anulacion_id')->references('id')->on('usuarios')->nullOnDelete();
            
            // Índices para optimizar consultas
            $table->index(['estado_venta', 'anulada']);
            $table->index('fecha_procesamiento');
            $table->index(['metodo_pago', 'created_at']);
        });
    }

    /**
     * Revertir la migración
     */
    public function down(): void {
        // Eliminar campos agregados a la tabla ventas
        Schema::table('ventas', function (Blueprint $table) {
            // Eliminar claves foráneas primero
            $table->dropForeign(['usuario_anulacion_id']);
            
            // Eliminar índices
            $table->dropIndex(['estado_venta', 'anulada']);
            $table->dropIndex(['fecha_procesamiento']);
            $table->dropIndex(['metodo_pago', 'created_at']);
            
            // Eliminar campos
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
                'estado_venta',
                'fecha_procesamiento',
                'anulada',
                'usuario_anulacion_id',
                'fecha_anulacion',
                'motivo_anulacion'
            ]);
        });
        
        // Eliminar tabla detalle_venta
        Schema::dropIfExists('detalle_venta');
    }
};