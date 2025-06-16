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
            ['id_producto' => 1, 'id_relacionado' => 2, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()]
            , ['id_producto' => 2, 'id_relacionado' => 4, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()]
            , ['id_producto' => 3, 'id_relacionado' => 1, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()]
            , ['id_producto' => 4, 'id_relacionado' => 3, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()]
            , ['id_producto' => 5, 'id_relacionado' => 1, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()]
            , ['id_producto' => 6, 'id_relacionado' => 2, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()]
            , ['id_producto' => 7, 'id_relacionado' => 3, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()]
            , ['id_producto' => 8, 'id_relacionado' => 4, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()]
        ];

        DB::table('producto_relacionados')->insert($relaciones);
    }
}
