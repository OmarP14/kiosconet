<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Ejecutar la migración - Agregar campos de anulación a tabla ventas
     */
    public function up(): void
    {
        Schema::table('ventas', function (Blueprint $table) {
            // Campo para marcar si la venta está anulada (después del campo 'vuelto')
            $table->boolean('anulada')->default(false)->after('vuelto');
            
            // Usuario que realizó la anulación (puede ser null)
            $table->unsignedBigInteger('usuario_anulacion_id')->nullable()->after('anulada');
            
            // Fecha y hora de la anulación
            $table->timestamp('fecha_anulacion')->nullable()->after('usuario_anulacion_id');
            
            // Motivo de la anulación
            $table->text('motivo_anulacion')->nullable()->after('fecha_anulacion');
            
            // Clave foránea para el usuario que anuló (opcional si existe tabla usuarios)
            $table->foreign('usuario_anulacion_id')
                  ->references('id')
                  ->on('usuarios')
                  ->onDelete('set null');
            
            // Índices para mejorar rendimiento
            $table->index('anulada');
            $table->index(['anulada', 'created_at']);
        });
    }

    /**
     * Revertir la migración
     */
    public function down(): void
    {
        Schema::table('ventas', function (Blueprint $table) {
            // Eliminar clave foránea primero
            $table->dropForeign(['usuario_anulacion_id']);
            
            // Eliminar índices
            $table->dropIndex(['anulada']);
            $table->dropIndex(['anulada', 'created_at']);
            
            // Eliminar columnas
            $table->dropColumn([
                'anulada',
                'usuario_anulacion_id', 
                'fecha_anulacion', 
                'motivo_anulacion'
            ]);
        });
    }
};