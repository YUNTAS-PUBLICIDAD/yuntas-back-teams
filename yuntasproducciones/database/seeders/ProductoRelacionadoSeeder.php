<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ProductoRelacionadoSeeder extends Seeder
{
    public function run(): void
    {
        $relaciones = [
            // Producto 1: Letreros Neón LED
            ['id_producto' => 1, 'id_relacionado' => 2, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id_producto' => 1, 'id_relacionado' => 4, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            
            // Producto 2: Sillas y Mesas LED
            ['id_producto' => 2, 'id_relacionado' => 1, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id_producto' => 2, 'id_relacionado' => 3, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],

            // Producto 3: Pisos LED
            ['id_producto' => 3, 'id_relacionado' => 2, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id_producto' => 3, 'id_relacionado' => 4, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],

            // Producto 4: Barras Pixel LED
            ['id_producto' => 4, 'id_relacionado' => 1, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id_producto' => 4, 'id_relacionado' => 3, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
        ];

        DB::table('producto_relacionados')->insert($relaciones);
    }
}
