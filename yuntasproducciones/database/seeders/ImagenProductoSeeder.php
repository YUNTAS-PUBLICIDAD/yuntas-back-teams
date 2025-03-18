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
            // Producto 1: Letreros Neón LED
            ['id_producto' => 1, 'url_imagen' => 'https://placehold.co/100x150/orange/white?text=letrero-neon-1', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id_producto' => 1, 'url_imagen' => 'https://placehold.co/100x150/orange/white?text=letrero-neon-2', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id_producto' => 1, 'url_imagen' => 'https://placehold.co/100x150/orange/white?text=letrero-neon-3', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id_producto' => 1, 'url_imagen' => 'https://placehold.co/100x150/orange/white?text=letrero-neon-4', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],

            // Producto 2: Sillas y Mesas LED
            ['id_producto' => 2, 'url_imagen' => 'https://placehold.co/100x150/orange/white?text=sillas-led-1', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id_producto' => 2, 'url_imagen' => 'https://placehold.co/100x150/orange/white?text=sillas-led-2', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id_producto' => 2, 'url_imagen' => 'https://placehold.co/100x150/orange/white?text=mesas-led-1', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id_producto' => 2, 'url_imagen' => 'https://placehold.co/100x150/orange/white?text=mesas-led-2', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],

            // Producto 3: Pisos LED
            ['id_producto' => 3, 'url_imagen' => 'https://placehold.co/100x150/orange/white?text=pisos-led-1', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id_producto' => 3, 'url_imagen' => 'https://placehold.co/100x150/orange/white?text=pisos-led-2', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id_producto' => 3, 'url_imagen' => 'https://placehold.co/100x150/orange/white?text=pisos-led-3', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id_producto' => 3, 'url_imagen' => 'https://placehold.co/100x150/orange/white?text=pisos-led-4', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],

            // Producto 4: Barras Pixel LED
            ['id_producto' => 4, 'url_imagen' => 'https://placehold.co/100x150/orange/white?text=barras-pixel-1', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id_producto' => 4, 'url_imagen' => 'https://placehold.co/100x150/orange/white?text=barras-pixel-2', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id_producto' => 4, 'url_imagen' => 'https://placehold.co/100x150/orange/white?text=barras-pixel-3', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id_producto' => 4, 'url_imagen' => 'https://placehold.co/100x150/orange/white?text=barras-pixel-4', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
        ];

        DB::table('imagen_productos')->insert($imagenes);
    }
}
