<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ImagenProductoSeeder extends Seeder
{
    public function run(): void
    {
        $imagenes = [
            // Producto 1
            ['id_producto' => 1, 'url_imagen' => 'https://placehold.co/100x150/orange/white?text=producto-1-1', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id_producto' => 1, 'url_imagen' => 'https://placehold.co/100x150/orange/white?text=producto-1-2', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id_producto' => 1, 'url_imagen' => 'https://placehold.co/100x150/orange/white?text=producto-1-3', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id_producto' => 1, 'url_imagen' => 'https://placehold.co/100x150/orange/white?text=producto-1-4', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],

            // Producto 2
            ['id_producto' => 2, 'url_imagen' => 'https://placehold.co/100x150/orange/white?text=producto-2-1', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id_producto' => 2, 'url_imagen' => 'https://placehold.co/100x150/orange/white?text=producto-2-2', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id_producto' => 2, 'url_imagen' => 'https://placehold.co/100x150/orange/white?text=producto-2-3', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id_producto' => 2, 'url_imagen' => 'https://placehold.co/100x150/orange/white?text=producto-2-4', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],

            // Producto 3
            ['id_producto' => 3, 'url_imagen' => 'https://placehold.co/100x150/orange/white?text=producto-3-1', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id_producto' => 3, 'url_imagen' => 'https://placehold.co/100x150/orange/white?text=producto-3-2', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id_producto' => 3, 'url_imagen' => 'https://placehold.co/100x150/orange/white?text=producto-3-3', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id_producto' => 3, 'url_imagen' => 'https://placehold.co/100x150/orange/white?text=producto-3-4', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],

            // Producto 4
            ['id_producto' => 4, 'url_imagen' => 'https://placehold.co/100x150/orange/white?text=producto-4-1', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id_producto' => 4, 'url_imagen' => 'https://placehold.co/100x150/orange/white?text=producto-4-2', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id_producto' => 4, 'url_imagen' => 'https://placehold.co/100x150/orange/white?text=producto-4-3', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id_producto' => 4, 'url_imagen' => 'https://placehold.co/100x150/orange/white?text=producto-4-4', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],

            // Producto 5
            ['id_producto' => 5, 'url_imagen' => 'https://placehold.co/100x130/orange/white?text=producto-5-1', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id_producto' => 5, 'url_imagen' => 'https://placehold.co/100x150/orange/white?text=producto-5-2', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id_producto' => 5, 'url_imagen' => 'https://placehold.co/100x150/orange/white?text=producto-5-3', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id_producto' => 5, 'url_imagen' => 'https://placehold.co/100x150/orange/white?text=producto-5-4', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],

            // Producto 6
            ['id_producto' => 6, 'url_imagen' => 'https://placehold.co/100x150/orange/white?text=producto-6-1', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id_producto' => 6, 'url_imagen' => 'https://placehold.co/100x150/orange/white?text=producto-6-2', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id_producto' => 6, 'url_imagen' => 'https://placehold.co/100x150/orange/white?text=producto-6-3', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id_producto' => 6, 'url_imagen' => 'https://placehold.co/100x150/orange/white?text=producto-6-4', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],

            // Producto 7
            ['id_producto' => 7, 'url_imagen' => 'https://placehold.co/100x150/orange/white?text=producto-7-1', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id_producto' => 7, 'url_imagen' => 'https://placehold.co/100x150/orange/white?text=producto-7-2', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id_producto' => 7, 'url_imagen' => 'https://placehold.co/100x150/orange/white?text=producto-7-3', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id_producto' => 7, 'url_imagen' => 'https://placehold.co/100x150/orange/white?text=producto-7-4', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],

            // Producto 8
            ['id_producto' => 8, 'url_imagen' => 'https://placehold.co/100x150/orange/white?text=producto-8-1', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id_producto' => 8, 'url_imagen' => 'https://placehold.co/100x150/orange/white?text=producto-8-2', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id_producto' => 8, 'url_imagen' => 'https://placehold.co/100x150/orange/white?text=producto-8-3', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id_producto' => 8, 'url_imagen' => 'https://placehold.co/100x150/orange/white?text=producto-8-4', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],

            // Producto 9
            ['id_producto' => 9, 'url_imagen' => 'https://placehold.co/100x150/orange/white?text=producto-9-1', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id_producto' => 9, 'url_imagen' => 'https://placehold.co/100x150/orange/white?text=producto-9-2', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id_producto' => 9, 'url_imagen' => 'https://placehold.co/100x150/orange/white?text=producto-9-3', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id_producto' => 9, 'url_imagen' => 'https://placehold.co/100x150/orange/white?text=producto-9-4', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],

            // Producto 10
            ['id_producto' => 10, 'url_imagen' => 'https://placehold.co/100x150/orange/white?text=producto-10-1', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id_producto' => 10, 'url_imagen' => 'https://placehold.co/100x150/orange/white?text=producto-10-2', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id_producto' => 10, 'url_imagen' => 'https://placehold.co/100x150/orange/white?text=producto-10-3', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id_producto' => 10, 'url_imagen' => 'https://placehold.co/100x150/orange/white?text=producto-10-4', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],

            // Producto 11
            ['id_producto' => 11, 'url_imagen' => 'https://placehold.co/100x150/orange/white?text=producto-11-1', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id_producto' => 11, 'url_imagen' => 'https://placehold.co/100x150/orange/white?text=producto-11-2', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id_producto' => 11, 'url_imagen' => 'https://placehold.co/100x150/orange/white?text=producto-11-3', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id_producto' => 11, 'url_imagen' => 'https://placehold.co/100x150/orange/white?text=producto-11-4', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
        ];

        DB::table('imagen_productos')->insert($imagenes);
    }
}
