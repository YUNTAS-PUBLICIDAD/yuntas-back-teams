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
            // Producto 1
            ['id_producto' => 1, 'id_relacionado' => 2, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id_producto' => 1, 'id_relacionado' => 3, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            
            // Producto 2
            ['id_producto' => 2, 'id_relacionado' => 1, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id_producto' => 2, 'id_relacionado' => 3, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],

            // Producto 3
            ['id_producto' => 3, 'id_relacionado' => 1, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id_producto' => 3, 'id_relacionado' => 2, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id_producto' => 3, 'id_relacionado' => 4, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id_producto' => 3, 'id_relacionado' => 6, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],

            // Producto 4
            ['id_producto' => 4, 'id_relacionado' => 3, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id_producto' => 4, 'id_relacionado' => 5, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],

            // Producto 5
            ['id_producto' => 5, 'id_relacionado' => 4, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id_producto' => 5, 'id_relacionado' => 6, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],

            // Producto 6
            ['id_producto' => 6, 'id_relacionado' => 5, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id_producto' => 6, 'id_relacionado' => 7, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],

            // Producto 7
            ['id_producto' => 7, 'id_relacionado' => 6, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id_producto' => 7, 'id_relacionado' => 8, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],

            // Producto 8
            ['id_producto' => 8, 'id_relacionado' => 7, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id_producto' => 8, 'id_relacionado' => 9, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],

            // Producto 9
            ['id_producto' => 9, 'id_relacionado' => 8, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id_producto' => 9, 'id_relacionado' => 10, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],

            // Producto 10
            ['id_producto' => 10, 'id_relacionado' => 9, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id_producto' => 10, 'id_relacionado' => 11, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],

            // Producto 11
            ['id_producto' => 11, 'id_relacionado' => 1, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id_producto' => 11, 'id_relacionado' => 3, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
        ];

        DB::table('producto_relacionados')->insert($relaciones);
    }
}
