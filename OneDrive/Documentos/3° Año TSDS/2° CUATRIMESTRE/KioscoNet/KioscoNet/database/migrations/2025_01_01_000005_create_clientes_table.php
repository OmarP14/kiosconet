<?php
/*
 * CREAR NUEVA MIGRACIÓN: 
 * php artisan make:migration add_limite_credito_to_clientes_table --table=clientes
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Ejecutar la migración
     */
    public function up()
    {
        Schema::table('clientes', function (Blueprint $table) {
            // Agregar campo límite de crédito
            $table->decimal('limite_credito', 10, 2)->default(1000.00)->after('saldo_cc');
            
            // Agregar campo activo
            $table->boolean('activo')->default(true)->after('limite_credito');
            
            // Si no tienes estos campos, descomenta las siguientes líneas:
            // $table->string('telefono', 20)->nullable()->after('email');
            // $table->text('direccion')->nullable()->after('telefono');
            
            // Índices para optimizar consultas
            $table->index(['activo', 'saldo_cc']);
            $table->index('limite_credito');
        });
        
        // Actualizar registros existentes
        DB::table('clientes')->update([
            'limite_credito' => 1000.00,
            'activo' => true
        ]);
    }

    /**
     * Revertir la migración
     */
    public function down()
    {
        Schema::table('clientes', function (Blueprint $table) {
            $table->dropIndex(['activo', 'saldo_cc']);
            $table->dropIndex(['limite_credito']);
            $table->dropColumn(['limite_credito', 'activo']);
        });
    }
};