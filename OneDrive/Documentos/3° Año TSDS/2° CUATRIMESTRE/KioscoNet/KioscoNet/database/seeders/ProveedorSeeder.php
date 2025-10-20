<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Proveedor;

class ProveedorSeeder extends Seeder
{
    public function run(): void
    {
        Proveedor::create(['nombre' => 'Distribuidora San Juan', 'telefono' => '2644000001', 'email' => 'ventas@dsj.com']);
        Proveedor::create(['nombre' => 'Cigarrillos SA', 'telefono' => '2644000002', 'email' => 'contacto@cigsa.com']);
        Proveedor::create(['nombre' => 'Golosinas SRL', 'telefono' => '2644000003', 'email' => 'info@golosinas.com']);
    }
}
