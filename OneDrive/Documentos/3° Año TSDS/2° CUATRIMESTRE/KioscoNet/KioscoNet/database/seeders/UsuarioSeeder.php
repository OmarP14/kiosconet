<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\Usuario;

class UsuarioSeeder extends Seeder
{
    public function run(): void
    {
        Usuario::create([
            'nombre' => 'Administrador',
            'email' => 'admin@kiosconet.com',
            'usuario' => 'admin',
            'password' => Hash::make('password123'),
            'rol' => 'administrador',
        ]);
    }
}
