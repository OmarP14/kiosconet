<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            UsuarioSeeder::class,
            ListaPrecioSeeder::class,
            ProveedorSeeder::class,
            ProductoSeeder::class,
            ClienteSeeder::class,
        ]);
    }
}
