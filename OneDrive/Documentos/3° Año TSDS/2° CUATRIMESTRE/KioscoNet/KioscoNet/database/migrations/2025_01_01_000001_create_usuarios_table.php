<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('usuarios', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->string('email')->unique();
            $table->string('usuario')->unique();
            $table->string('password');
            $table->enum('rol', ['administrador','vendedor'])->default('vendedor');
            $table->rememberToken(); // 🔹 añade la columna remember_token
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void {
        Schema::dropIfExists('usuarios');
    }
};
