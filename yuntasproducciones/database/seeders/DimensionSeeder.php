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
            // Producto 1: Letreros Neón LED
            ['id_producto' => 1, 'tipo' => 'alto', 'valor' => '30-150 cm', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id_producto' => 1, 'tipo' => 'largo', 'valor' => '40-200 cm', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id_producto' => 1, 'tipo' => 'ancho', 'valor' => '5 cm', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],

            // Producto 2: Sillas y Mesas LED
            ['id_producto' => 2, 'tipo' => 'alto', 'valor' => '80-100 cm (sillas)', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id_producto' => 2, 'tipo' => 'largo', 'valor' => '40-60 cm (sillas)', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id_producto' => 2, 'tipo' => 'ancho', 'valor' => '40-50 cm (sillas)', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],

            // Producto 3: Pisos LED
            ['id_producto' => 3, 'tipo' => 'alto', 'valor' => '5 cm', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id_producto' => 3, 'tipo' => 'largo', 'valor' => '60-100 cm (por módulo)', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id_producto' => 3, 'tipo' => 'ancho', 'valor' => '60-100 cm (por módulo)', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],

            // Producto 4: Barras Pixel LED
            ['id_producto' => 4, 'tipo' => 'alto', 'valor' => '5 cm', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id_producto' => 4, 'tipo' => 'largo', 'valor' => '100-200 cm', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id_producto' => 4, 'tipo' => 'ancho', 'valor' => '5 cm', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
        ];

        DB::table('dimensions')->insert($dimensiones);
    }
}
