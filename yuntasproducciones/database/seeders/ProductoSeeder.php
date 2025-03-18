<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ProductoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $productos = [
            [
                'id' => 1,
                'nombre' => 'Letreros Neón LED',
                'titulo' => 'LETREROS NEÓN LED',
                'subtitulo' => 'Iluminación y decoración para espacios únicos',
                'lema' => '¡Dale vida a tus espacios con iluminación única!',
                'descripcion' => 'Los Letreros Neón LED son herramientas que decoran e iluminan tus negocios y eventos para crear experiencias únicas. Disponibles en una amplia gama de colores y diseños.',
                'imagen_principal' => '/Productos/letrero-neon-led.webp',
                'stock' => 15,
                'precio' => 249.99,
                'seccion' => 'Iluminación',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'id' => 2,
                'nombre' => 'Sillas y Mesas LED',
                'titulo' => 'SILLAS Y MESAS LED',
                'subtitulo' => 'Mobiliario iluminado para eventos extraordinarios',
                'lema' => '¡Transforma cualquier evento con nuestro mobiliario LED!',
                'descripcion' => 'Nuestras sillas y mesas LED son adaptable y versátil a cualquier ambiente, perfecto para eventos y con bajo consumo de energia. Para finiquitar, el producto es personalizable al gusto del ciente.',
                'imagen_principal' => '/Productos/sillas-mesas-led.webp',
                'stock' => 8,
                'precio' => 399.99,
                'seccion' => 'Mobiliario',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'id' => 3,
                'nombre' => 'Pisos LED',
                'titulo' => 'PISOS LED',
                'subtitulo' => 'Superficies interactivas para experiencias inolvidables',
                'lema' => '¡Haz que tus invitados caminen sobre luz!',
                'descripcion' => 'Los Pisos LED ofrecen iluminación interactiva y personalizable, ideales para eventos, escenarios y espacios comerciales, brindando un impacto visual dinámico y moderno.',
                'imagen_principal' => '/Productos/pisos-led.webp',
                'stock' => 5,
                'precio' => 599.99,
                'seccion' => 'Iluminación',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'id' => 4,
                'nombre' => 'Barras Pixel LED',
                'titulo' => 'BARRAS PIXEL LED',
                'subtitulo' => 'Efectos visuales dinámicos y personalizables',
                'lema' => '¡Dale movimiento a tu iluminación con efectos pixel!',
                'descripcion' => 'Nuestras Barras de Pixel LED forman parte de nuestra nueva línea de productos, perfectas para crear efectos visuales dinámicos y personalizables. Ideales para eventos, vitrinas, y fachadas modernas.',
                'imagen_principal' => '/Productos/barras-pixel-led.webp',
                'stock' => 12,
                'precio' => 349.99,
                'seccion' => 'Iluminación',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
        ];

        DB::table('productos')->insert($productos);
    }
}
