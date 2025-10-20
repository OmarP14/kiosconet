<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('usuarios', function (Blueprint $table) {
            $table->rememberToken()->after('rol'); // 🔹 agrega la columna remember_token
        });
    }

    public function down(): void {
        Schema::table('usuarios', function (Blueprint $table) {
            $table->dropColumn('remember_token');
        });
    }
};
