<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('listas_precios', function (Blueprint $table) {
            $table->id();
            $table->string('nombre'); // minorista, mayorista
            $table->string('descripcion')->nullable();
            $table->timestamps();
        });

        Schema::create('producto_precio', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('producto_id');
            $table->unsignedBigInteger('lista_precio_id');
            $table->decimal('precio', 12, 2);
            $table->timestamps();

            $table->foreign('producto_id')->references('id')->on('productos')->cascadeOnDelete();
            $table->foreign('lista_precio_id')->references('id')->on('listas_precios')->cascadeOnDelete();
            $table->unique(['producto_id','lista_precio_id']);
        });
    }

    public function down(): void {
        Schema::dropIfExists('producto_precio');
        Schema::dropIfExists('listas_precios');
    }
};
