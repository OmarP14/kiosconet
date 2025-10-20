<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('clientes', function (Blueprint $table) {
            // Agregar apellido
            $table->string('apellido', 191)->nullable()->after('nombre');
            
            // Agregar límite de crédito
            $table->decimal('limite_credito', 12, 2)->default(0)->after('direccion');
            
            // Agregar tipo de cliente
            $table->enum('tipo_cliente', ['minorista', 'mayorista', 'especial'])->default('minorista')->after('limite_credito');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('clientes', function (Blueprint $table) {
            $table->dropColumn(['apellido', 'limite_credito', 'tipo_cliente']);
        });
    }
};