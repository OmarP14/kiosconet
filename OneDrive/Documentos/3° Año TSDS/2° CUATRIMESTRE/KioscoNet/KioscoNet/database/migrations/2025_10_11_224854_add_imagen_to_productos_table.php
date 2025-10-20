<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Agregar campo imagen a la tabla productos
     */
    public function up(): void
    {
        Schema::table('productos', function (Blueprint $table) {
            // Agregar columna 'imagen' después de 'fecha_vencimiento'
            $table->string('imagen')->nullable()->after('fecha_vencimiento');
        });
    }

    /**
     * Revertir los cambios (eliminar el campo imagen)
     */
    public function down(): void
    {
        Schema::table('productos', function (Blueprint $table) {
            // Eliminar columna 'imagen' si se revierte la migración
            $table->dropColumn('imagen');
        });
    }
};