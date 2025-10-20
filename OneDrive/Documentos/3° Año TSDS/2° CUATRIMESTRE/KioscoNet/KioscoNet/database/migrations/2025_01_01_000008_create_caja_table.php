<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('caja', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('usuario_id')->nullable();
            $table->enum('tipo', ['ingreso','egreso'])->default('ingreso');
            $table->string('concepto')->nullable();
            $table->decimal('monto', 12, 2)->default(0);
            $table->timestamps();

            $table->foreign('usuario_id')->references('id')->on('usuarios')->nullOnDelete();
        });
    }

    public function down(): void {
        Schema::dropIfExists('caja');
    }
};
