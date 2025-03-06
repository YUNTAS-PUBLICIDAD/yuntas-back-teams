<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class EspecificacionSeeder extends Seeder
{
    public function run(): void
    {
        $especificaciones = [
            // Producto 1
            ['id_producto' => 1, 'clave' => 'power', 'valor' => '220V', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id_producto' => 1, 'clave' => 'capacity', 'valor' => 'Varía según el modelo', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id_producto' => 1, 'clave' => 'material', 'valor' => 'Acero inoxidable', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],

            // Producto 2
            ['id_producto' => 2, 'clave' => 'power', 'valor' => '110V/220V', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id_producto' => 2, 'clave' => 'capacity', 'valor' => 'Hasta 500 unidades/hora', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id_producto' => 2, 'clave' => 'material', 'valor' => 'Aluminio', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],

            // Producto 3
            ['id_producto' => 3, 'clave' => 'power', 'valor' => '220-240V/50Hz', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id_producto' => 3, 'clave' => 'capacity', 'valor' => '1-100g (Según el modelo)', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id_producto' => 3, 'clave' => 'width', 'valor' => '3-20cm (Según el modelo)', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id_producto' => 3, 'clave' => 'flow', 'valor' => '10-20 bolsas/min', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],

            // Producto 4
            ['id_producto' => 4, 'clave' => 'power', 'valor' => '220V', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id_producto' => 4, 'clave' => 'capacity', 'valor' => 'Hasta 30 bolsas/min', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id_producto' => 4, 'clave' => 'material', 'valor' => 'Plástico resistente', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],

            // Producto 5
            ['id_producto' => 5, 'clave' => 'power', 'valor' => '220V', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id_producto' => 5, 'clave' => 'amperage', 'valor' => '50-200A', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id_producto' => 5, 'clave' => 'material', 'valor' => 'Acero', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],

            // Producto 6
            ['id_producto' => 6, 'clave' => 'power', 'valor' => '24V', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id_producto' => 6, 'clave' => 'capacity', 'valor' => '50 GPD', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id_producto' => 6, 'clave' => 'stages', 'valor' => '5 etapas de filtración', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],

            // Producto 7
            ['id_producto' => 7, 'clave' => 'power', 'valor' => '12V', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id_producto' => 7, 'clave' => 'resolution', 'valor' => '1024x768', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id_producto' => 7, 'clave' => 'diameter', 'valor' => '50 cm', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],

            // Producto 8
            ['id_producto' => 8, 'clave' => 'material', 'valor' => 'Fibra de bambú', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id_producto' => 8, 'clave' => 'dimensions', 'valor' => '60x60 cm', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id_producto' => 8, 'clave' => 'thickness', 'valor' => '5 mm', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],

            // Producto 9
            ['id_producto' => 9, 'clave' => 'material', 'valor' => 'WPC (Wood Plastic Composite)', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id_producto' => 9, 'clave' => 'dimensions', 'valor' => '180x30 cm', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id_producto' => 9, 'clave' => 'thickness', 'valor' => '2 cm', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],

            // Producto 10
            ['id_producto' => 10, 'clave' => 'material', 'valor' => 'WPC (Wood Plastic Composite)', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id_producto' => 10, 'clave' => 'dimensions', 'valor' => '200x40 cm', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id_producto' => 10, 'clave' => 'thickness', 'valor' => '2.5 cm', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],

            // Producto 11
            ['id_producto' => 11, 'clave' => 'power', 'valor' => '220V', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id_producto' => 11, 'clave' => 'capacity', 'valor' => '300-500 vasos/hora', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id_producto' => 11, 'clave' => 'diameter', 'valor' => '90-95 mm', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
        ];

        DB::table('especificacions')->insert($especificaciones);
    }
}
