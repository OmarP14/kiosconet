<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Producto;
use App\Models\ListaPrecio;

class ProductoSeeder extends Seeder
{
    public function run(): void
    {
        $minorista = ListaPrecio::where('nombre', 'Minorista')->first();
        $mayorista = ListaPrecio::where('nombre', 'Mayorista')->first();

        $p1 = Producto::create([
            'nombre' => 'Coca Cola 500ml',
            'codigo' => 'BEB001',
            'categoria' => 'Bebidas',
            'proveedor_id' => 1,
            'precio_compra' => 100,
            'stock' => 50,
            'stock_minimo' => 10,
        ]);
        $p1->precios()->attach($minorista->id, ['precio' => 150]);
        $p1->precios()->attach($mayorista->id, ['precio' => 130]);

        $p2 = Producto::create([
            'nombre' => 'Marlboro Box',
            'codigo' => 'CIG001',
            'categoria' => 'Cigarrillos',
            'proveedor_id' => 2,
            'precio_compra' => 800,
            'stock' => 20,
            'stock_minimo' => 5,
        ]);
        $p2->precios()->attach($minorista->id, ['precio' => 1100]);
        $p2->precios()->attach($mayorista->id, ['precio' => 1000]);

        $p3 = Producto::create([
            'nombre' => 'Chocolate Milka',
            'codigo' => 'GOL001',
            'categoria' => 'Golosinas',
            'proveedor_id' => 3,
            'precio_compra' => 200,
            'stock' => 30,
            'stock_minimo' => 5,
        ]);
        $p3->precios()->attach($minorista->id, ['precio' => 300]);
        $p3->precios()->attach($mayorista->id, ['precio' => 250]);
    }
}
