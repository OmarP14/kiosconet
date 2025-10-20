<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pagos_mixtos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('venta_id')->constrained('ventas')->onDelete('cascade');
            $table->string('metodo_pago'); // efectivo, tarjeta, transferencia
            $table->decimal('monto', 10, 2);
            
            // Campos para efectivo
            $table->decimal('monto_recibido', 10, 2)->nullable();
            $table->decimal('vuelto', 10, 2)->nullable();
            
            // Campos para tarjeta
            $table->string('tipo_tarjeta')->nullable();
            $table->string('ultimos_digitos', 4)->nullable();
            $table->string('codigo_autorizacion')->nullable();
            
            // Campos para transferencia
            $table->string('numero_transferencia')->nullable();
            $table->string('banco')->nullable();
            $table->date('fecha_transferencia')->nullable();
            $table->time('hora_transferencia')->nullable();
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pagos_mixtos');
    }
};