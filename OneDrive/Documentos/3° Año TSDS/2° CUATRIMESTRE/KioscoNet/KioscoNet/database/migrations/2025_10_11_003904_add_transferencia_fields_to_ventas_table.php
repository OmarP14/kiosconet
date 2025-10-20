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
        Schema::table('ventas', function (Blueprint $table) {
            // Campos para transferencias bancarias
            $table->string('numero_transferencia', 50)->nullable()->after('codigo_autorizacion');
            $table->date('fecha_transferencia')->nullable()->after('numero_transferencia');
            $table->time('hora_transferencia')->nullable()->after('fecha_transferencia');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ventas', function (Blueprint $table) {
            $table->dropColumn([
                'numero_transferencia',
                'fecha_transferencia',
                'hora_transferencia'
            ]);
        });
    }
};