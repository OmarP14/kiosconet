<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('productos', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->string('codigo')->nullable()->unique();
            $table->string('categoria')->nullable();
            $table->unsignedBigInteger('proveedor_id')->nullable();
            $table->decimal('precio_compra', 12, 2)->default(0);
            $table->integer('stock')->default(0);
            $table->integer('stock_minimo')->default(0);
            $table->date('fecha_vencimiento')->nullable();
            $table->boolean('activo')->default(true);
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('proveedor_id')->references('id')->on('proveedores')->nullOnDelete();
        });
    }

    public function down(): void {
        Schema::dropIfExists('productos');
    }
};
