<?php
// fix_user.php

// Autoload de Laravel
require __DIR__.'/vendor/autoload.php';

// Bootstrap de Laravel
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== CREANDO USUARIO ADMIN ===\n";

try {
    // Verificar si existe
    $usuario = \App\Models\Usuario::where('email', 'admin@kiosconet.com')->first();
    
    if ($usuario) {
        echo "Usuario ya existe - Actualizando password...\n";
        $usuario->password = \Illuminate\Support\Facades\Hash::make('password123');
        $usuario->save();
        echo "Password actualizado\n";
    } else {
        echo "Creando usuario...\n";
        \App\Models\Usuario::create([
            'nombre' => 'Administrador',
            'email' => 'admin@kiosconet.com',
            'usuario' => 'admin',
            'password' => \Illuminate\Support\Facades\Hash::make('password123'),
            'rol' => 'administrador'
        ]);
        echo "Usuario creado exitosamente\n";
    }
    
    echo "Credenciales:\n";
    echo "Email: admin@kiosconet.com\n";
    echo "Password: password123\n";
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    echo "Verificar conexión a la base de datos\n";
}

echo "=== COMPLETADO ===\n";
?>