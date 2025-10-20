<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Modificar el enum de metodo_pago para incluir cuenta_corriente y mixto
        DB::statement("ALTER TABLE `ventas` MODIFY `metodo_pago` ENUM('efectivo','tarjeta','transferencia','billetera','cc','cuenta_corriente','mixto') NOT NULL DEFAULT 'efectivo'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Volver al enum original
        DB::statement("ALTER TABLE `ventas` MODIFY `metodo_pago` ENUM('efectivo','tarjeta','transferencia','billetera','cc') NOT NULL DEFAULT 'efectivo'");
    }
};