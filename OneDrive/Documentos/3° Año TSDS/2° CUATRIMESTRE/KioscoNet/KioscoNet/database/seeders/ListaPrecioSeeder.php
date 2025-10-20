<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ListaPrecio;

class ListaPrecioSeeder extends Seeder
{
    public function run(): void
    {
        ListaPrecio::create(['nombre' => 'Minorista', 'descripcion' => 'Precios de venta al público']);
        ListaPrecio::create(['nombre' => 'Mayorista', 'descripcion' => 'Precios para compras por volumen']);
    }
}
