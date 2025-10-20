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
            // Campos para pagos con tarjeta
            $table->enum('tipo_tarjeta', ['debito', 'credito'])->nullable()->after('metodo_pago');
            $table->string('ultimos_digitos', 4)->nullable()->after('tipo_tarjeta');
            $table->string('codigo_autorizacion', 50)->nullable()->after('ultimos_digitos');
            $table->string('token_comprobante', 100)->nullable()->after('codigo_autorizacion');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ventas', function (Blueprint $table) {
            $table->dropColumn([
                'tipo_tarjeta',
                'ultimos_digitos',
                'codigo_autorizacion',
                'token_comprobante'
            ]);
        });
    }
};