<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Usuario;
use Illuminate\Support\Facades\Hash;

class VerificarAuth extends Command
{
    protected $signature = 'auth:verificar';
    protected $description = 'Verificar y crear usuario admin';

    public function handle()
    {
        $this->info('=== VERIFICANDO AUTENTICACIÓN ===');
        
        // Verificar usuario admin
        $admin = Usuario::where('email', 'admin@kiosconet.com')->first();
        
        if ($admin) {
            $this->info('✅ Usuario admin existe:');
            $this->line('   - ID: ' . $admin->id);
            $this->line('   - Nombre: ' . $admin->nombre);
            $this->line('   - Email: ' . $admin->email);
            
            // Verificar password
            if (Hash::check('password123', $admin->password)) {
                $this->info('✅ Password correcto');
            } else {
                $this->error('❌ Password incorrecto - Actualizando...');
                $admin->password = Hash::make('password123');
                $admin->save();
                $this->info('✅ Password actualizado');
            }
        } else {
            $this->warn('❌ Usuario admin NO existe - Creando...');
            
            $admin = Usuario::create([
                'nombre' => 'Administrador',
                'email' => 'admin@kiosconet.com',
                'usuario' => 'admin',
                'password' => Hash::make('password123'),
                'rol' => 'administrador'
            ]);
            
            $this->info('✅ Usuario creado con ID: ' . $admin->id);
        }
        
        // Verificar total de usuarios
        $total = Usuario::count();
        $this->info("Total de usuarios en el sistema: {$total}");
        
        return Command::SUCCESS;
    }
}