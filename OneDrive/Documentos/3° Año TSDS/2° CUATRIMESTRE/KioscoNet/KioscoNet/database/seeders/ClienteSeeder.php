<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Cliente;

class ClienteSeeder extends Seeder
{
    public function run(): void
    {
        Cliente::create([
            'nombre' => 'Juan Pérez',
            'telefono' => '2645000000',
            'email' => 'juanperez@test.com',
            'direccion' => 'Calle Falsa 123',
            'saldo_cc' => 0,
        ]);
    }
}
