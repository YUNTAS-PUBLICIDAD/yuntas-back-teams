<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DimensionSeeder extends Seeder
{
    public function run(): void
    {
        $dimensiones = [
            // Producto 1
            ['id_producto' => 1, 'tipo' => 'alto', 'valor' => '40 cm', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id_producto' => 1, 'tipo' => 'largo', 'valor' => '30 cm', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id_producto' => 1, 'tipo' => 'ancho', 'valor' => '20 cm', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],

            // Producto 2
            ['id_producto' => 2, 'tipo' => 'alto', 'valor' => '60 cm', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id_producto' => 2, 'tipo' => 'largo', 'valor' => '40 cm', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id_producto' => 2, 'tipo' => 'ancho', 'valor' => '30 cm', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],

            // Producto 3
            ['id_producto' => 3, 'tipo' => 'alto', 'valor' => '150 cm', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id_producto' => 3, 'tipo' => 'largo', 'valor' => '55 cm', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id_producto' => 3, 'tipo' => 'ancho', 'valor' => '56 cm', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],

            // Producto 4
            ['id_producto' => 4, 'tipo' => 'alto', 'valor' => '35 cm', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id_producto' => 4, 'tipo' => 'largo', 'valor' => '25 cm', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id_producto' => 4, 'tipo' => 'ancho', 'valor' => '15 cm', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],

            // Producto 5
            ['id_producto' => 5, 'tipo' => 'alto', 'valor' => '50 cm', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id_producto' => 5, 'tipo' => 'largo', 'valor' => '40 cm', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id_producto' => 5, 'tipo' => 'ancho', 'valor' => '25 cm', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],

            // Producto 6
            ['id_producto' => 6, 'tipo' => 'alto', 'valor' => '45 cm', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id_producto' => 6, 'tipo' => 'largo', 'valor' => '35 cm', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id_producto' => 6, 'tipo' => 'ancho', 'valor' => '20 cm', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],

            // Producto 7
            ['id_producto' => 7, 'tipo' => 'alto', 'valor' => '55 cm', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id_producto' => 7, 'tipo' => 'largo', 'valor' => '55 cm', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id_producto' => 7, 'tipo' => 'ancho', 'valor' => '10 cm', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],

            // Producto 8
            ['id_producto' => 8, 'tipo' => 'alto', 'valor' => '60 cm', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id_producto' => 8, 'tipo' => 'largo', 'valor' => '60 cm', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id_producto' => 8, 'tipo' => 'ancho', 'valor' => '0.5 cm', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],

            // Producto 9
            ['id_producto' => 9, 'tipo' => 'alto', 'valor' => '180 cm', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id_producto' => 9, 'tipo' => 'largo', 'valor' => '30 cm', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id_producto' => 9, 'tipo' => 'ancho', 'valor' => '2 cm', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],

            // Producto 10
            ['id_producto' => 10, 'tipo' => 'alto', 'valor' => '200 cm', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id_producto' => 10, 'tipo' => 'largo', 'valor' => '40 cm', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id_producto' => 10, 'tipo' => 'ancho', 'valor' => '2.5 cm', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],

            // Producto 11
            ['id_producto' => 11, 'tipo' => 'alto', 'valor' => '60 cm', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id_producto' => 11, 'tipo' => 'largo', 'valor' => '30 cm', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id_producto' => 11, 'tipo' => 'ancho', 'valor' => '25 cm', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
        ];

        DB::table('dimensions')->insert($dimensiones);
    }
}
