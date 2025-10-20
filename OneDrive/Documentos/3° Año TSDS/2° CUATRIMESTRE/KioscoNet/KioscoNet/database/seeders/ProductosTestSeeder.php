<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Producto;
use App\Models\ListaPrecio;
use App\Models\Proveedor;
use Illuminate\Support\Facades\DB;

class ProductosTestSeeder extends Seeder
{
    public function run(): void
    {
        // Limpiar datos existentes si es necesario
        DB::table('producto_precio')->truncate();
        Producto::truncate();
        
        // Verificar o crear listas de precios
        $minorista = ListaPrecio::firstOrCreate(
            ['nombre' => 'Minorista'],
            ['descripcion' => 'Precios de venta al público']
        );
        
        $mayorista = ListaPrecio::firstOrCreate(
            ['nombre' => 'Mayorista'],
            ['descripcion' => 'Precios para compras por volumen']
        );
        
        // Verificar o crear proveedor
        $proveedor1 = Proveedor::firstOrCreate(
            ['nombre' => 'Distribuidora Test'],
            [
                'telefono' => '2644000001',
                'email' => 'test@distribuidor.com'
            ]
        );

        $productos = [
            [
                'nombre' => 'Coca Cola 500ml',
                'codigo' => 'BEB001',
                'categoria' => 'Bebidas',
                'precio_compra' => 100,
                'stock' => 50,
                'stock_minimo' => 10,
                'precio_minorista' => 150,
                'precio_mayorista' => 130,
            ],
            [
                'nombre' => 'Pepsi 500ml',
                'codigo' => 'BEB002',
                'categoria' => 'Bebidas',
                'precio_compra' => 95,
                'stock' => 40,
                'stock_minimo' => 10,
                'precio_minorista' => 145,
                'precio_mayorista' => 125,
            ],
            [
                'nombre' => 'Agua Mineral 500ml',
                'codigo' => 'BEB003',
                'categoria' => 'Bebidas',
                'precio_compra' => 50,
                'stock' => 100,
                'stock_minimo' => 20,
                'precio_minorista' => 80,
                'precio_mayorista' => 70,
            ],
            [
                'nombre' => 'Marlboro Box',
                'codigo' => 'CIG001',
                'categoria' => 'Cigarrillos',
                'precio_compra' => 800,
                'stock' => 20,
                'stock_minimo' => 5,
                'precio_minorista' => 1100,
                'precio_mayorista' => 1000,
            ],
            [
                'nombre' => 'Chocolate Milka',
                'codigo' => 'GOL001',
                'categoria' => 'Golosinas',
                'precio_compra' => 200,
                'stock' => 30,
                'stock_minimo' => 5,
                'precio_minorista' => 300,
                'precio_mayorista' => 250,
            ],
        ];

        foreach ($productos as $productoData) {
            // Crear el producto
            $producto = Producto::create([
                'nombre' => $productoData['nombre'],
                'codigo' => $productoData['codigo'],
                'categoria' => $productoData['categoria'],
                'proveedor_id' => $proveedor1->id,
                'precio_compra' => $productoData['precio_compra'],
                'stock' => $productoData['stock'],
                'stock_minimo' => $productoData['stock_minimo'],
                'activo' => true,
            ]);

            // Agregar precios usando insert directo para evitar problemas
            DB::table('producto_precio')->insert([
                [
                    'producto_id' => $producto->id,
                    'lista_precio_id' => $minorista->id,
                    'precio' => $productoData['precio_minorista'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'producto_id' => $producto->id,
                    'lista_precio_id' => $mayorista->id,
                    'precio' => $productoData['precio_mayorista'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            ]);
        }
        
        echo "✅ Creados " . count($productos) . " productos con precios\n";
        echo "✅ Lista Minorista ID: " . $minorista->id . "\n";
        echo "✅ Lista Mayorista ID: " . $mayorista->id . "\n";
    }
}