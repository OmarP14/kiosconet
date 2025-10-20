<?php
/*
 * ARCHIVO: database/migrations/xxxx_xx_xx_add_limite_credito_to_clientes_table.php
 * 
 * CREAR ESTA MIGRACIÓN CON:
 * php artisan make:migration add_limite_credito_to_clientes_table --table=clientes
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Ejecutar la migración
     * Agrega campos necesarios para métodos de pago
     */
    public function up()
    {
        Schema::table('clientes', function (Blueprint $table) {
            // Campo para límite de crédito en cuenta corriente
            $table->decimal('limite_credito', 10, 2)->default(1000.00)->after('saldo_cc');
            
            // Campo para estado del cliente (activo/inactivo)
            $table->boolean('activo')->default(true)->after('limite_credito');
            
            // Campos adicionales para información de contacto (si no existen)
            if (!Schema::hasColumn('clientes', 'telefono')) {
                $table->string('telefono', 20)->nullable()->after('email');
            }
            
            if (!Schema::hasColumn('clientes', 'direccion')) {
                $table->text('direccion')->nullable()->after('telefono');
            }
            
            // Timestamps para auditoría (si no existen)
            if (!Schema::hasColumn('clientes', 'created_at')) {
                $table->timestamps();
            }
        });
        
        // Actualizar registros existentes con límite por defecto
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
            $table->dropColumn(['limite_credito', 'activo']);
            
            // Solo eliminar si fueron creados por esta migración
            // Comentar estas líneas si los campos ya existían
            // $table->dropColumn(['telefono', 'direccion']);
        });
    }
};