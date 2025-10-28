<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\Usuario;

class UsuarioSeeder extends Seeder
{
    /**
     * Seed the application's database with default admin user.
     * ✅ MEJORADO: Usa contraseña desde .env para mayor seguridad
     */
    public function run(): void
    {
        // Obtener contraseña desde .env o generar una aleatoria
        $adminPassword = env('ADMIN_DEFAULT_PASSWORD');

        if (!$adminPassword) {
            $this->command->warn('⚠️  ADMIN_DEFAULT_PASSWORD no está definida en .env');
            $this->command->warn('⚠️  Se usará una contraseña por defecto INSEGURA solo para desarrollo');
            $adminPassword = 'admin123'; // Solo para desarrollo local
        }

        Usuario::create([
            'nombre' => 'Administrador',
            'email' => 'admin@kiosconet.com',
            'usuario' => 'admin',
            'password' => Hash::make($adminPassword),
            'rol' => 'administrador',
        ]);

        $this->command->info('✅ Usuario administrador creado');
        if (app()->environment('local')) {
            $this->command->info("📧 Email: admin@kiosconet.com");
            $this->command->info("🔑 Password: {$adminPassword}");
        }
    }
}
