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
                'nombre' => 'SELLADORA DE BOTELLAS',
                'titulo' => 'SELLADORA DE BOTELLAS',
                'subtitulo' => 'Protege, conserva y destaca con cada botella',
                'lema' => '¡Sella tus botellas con la mejor calidad!',
                'descripcion' => 'Sella botellas de diferentes tamaños de manera eficiente y segura.',
                'imagen_principal' => '/Productos/Selladora de Botellas.webp',
                'stock' => 10,
                'precio' => 299.99,
                'seccion' => 'Trabajo',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'id' => 2,
                'nombre' => 'MÁQUINA DE EMBALAJE',
                'titulo' => 'MÁQUINA DE EMBALAJE',
                'subtitulo' => 'Protege, conserva y destaca EN CADA EMBALAJE',
                'lema' => '¡Embalaje perfecto para tus productos!',
                'descripcion' => 'Máquina de embalaje versátil para diferentes tipos de productos.',
                'imagen_principal' => '/Productos/Selladora de Vasos.webp',
                'stock' => 5,
                'precio' => 499.99,
                'seccion' => 'Trabajo',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'id' => 3,
                'nombre' => 'SELLADORA DE BOLSAS',
                'titulo' => 'SELLADORA DE BOLSAS',
                'subtitulo' => 'Protege, conserva y destaca EN CADA SELLADO DE BOLSAS',
                'lema' => '¡Conserva y mantén tus alimentos frescos por más tiempo!',
                'descripcion' => 'Empaca de manera eficiente al rellenar bolsas con chifles, harinas o líquidos con tres modelos de máquina. Se pueden sellar film de aluminio, papel de filtrante, film de plástico impreso.',
                'imagen_principal' => 'https://placehold.co/100x150/orange/white?text=producto-3',
                'stock' => 15,
                'precio' => 199.99,
                'seccion' => 'Trabajo',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'id' => 4,
                'nombre' => 'SELLADORA DE BOLSAS de CHIFLES',
                'titulo' => 'SELLADORA DE BOLSAS de CHIFLES',
                'subtitulo' => '¡MANTEN LOS CHIFLES FRESCOS Y CROCANTES!',
                'lema' => '¡La mejor opción para mantener tus chifles siempre frescos!',
                'descripcion' => 'Sella bolsas de chifles de manera rápida y eficiente.',
                'imagen_principal' => 'https://placehold.co/100x150/orange/white?text=producto-4',
                'stock' => 20,
                'precio' => 149.99,
                'seccion' => 'Trabajo',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'id' => 5,
                'nombre' => 'SOLDADORA LINGBA',
                'titulo' => 'SOLDADORA LINGBA',
                'subtitulo' => 'TUS SOLDADURAS PERFECTAS',
                'lema' => '¡La mejor opción para soldar con precisión!',
                'descripcion' => 'Soldadora de alta precisión para trabajos industriales.',
                'imagen_principal' => 'https://placehold.co/100x150/orange/white?text=producto-5',
                'stock' => 8,
                'precio' => 399.99,
                'seccion' => 'Trabajo',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'id' => 6,
                'nombre' => 'IMPRESORA 3D',
                'titulo' => 'IMPRESORA 3D',
                'subtitulo' => 'CREA TUS PROPIOS DISEÑOS',
                'lema' => '¡Imprime tus ideas en 3D!',
                'descripcion' => 'Impresora 3D de alta precisión para crear prototipos y piezas personalizadas.',
                'imagen_principal' => 'https://placehold.co/100x150/orange/white?text=producto-6',
                'stock' => 12,
                'precio' => 599.99,
                'seccion' => 'Trabajo',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'id' => 7,
                'nombre' => 'TALADRO INALÁMBRICO',
                'titulo' => 'TALADRO INALÁMBRICO',
                'subtitulo' => 'POTENCIA Y PRECISIÓN EN TUS MANOS',
                'lema' => '¡Taladra sin límites!',
                'descripcion' => 'Taladro inalámbrico de alta potencia para trabajos de construcción y bricolaje.',
                'imagen_principal' => 'https://placehold.co/100x150/orange/white?text=producto-7',
                'stock' => 25,
                'precio' => 89.99,
                'seccion' => 'Trabajo',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'id' => 8,
                'nombre' => 'SIERRA CIRCULAR',
                'titulo' => 'SIERRA CIRCULAR',
                'subtitulo' => 'CORTES PRECISOS Y RÁPIDOS',
                'lema' => '¡Corta con precisión y rapidez!',
                'descripcion' => 'Sierra circular de alta precisión para cortes en madera y otros materiales.',
                'imagen_principal' => 'https://placehold.co/100x150/orange/white?text=producto-8',
                'stock' => 10,
                'precio' => 129.99,
                'seccion' => 'Trabajo',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'id' => 9,
                'nombre' => 'LIJADORA ELÉCTRICA',
                'titulo' => 'LIJADORA ELÉCTRICA',
                'subtitulo' => 'ACABADOS PERFECTOS EN TUS PROYECTOS',
                'lema' => '¡Lija con precisión y facilidad!',
                'descripcion' => 'Lijadora eléctrica de alta potencia para acabados perfectos en madera y otros materiales.',
                'imagen_principal' => 'https://placehold.co/100x150/orange/white?text=producto-9',
                'stock' => 18,
                'precio' => 79.99,
                'seccion' => 'Trabajo',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'id' => 10,
                'nombre' => 'ASPIRADORA INDUSTRIAL',
                'titulo' => 'ASPIRADORA INDUSTRIAL',
                'subtitulo' => 'POTENCIA Y EFICIENCIA EN LA LIMPIEZA',
                'lema' => '¡Limpieza industrial eficiente!',
                'descripcion' => 'Aspiradora industrial de alta potencia para limpieza de grandes superficies.',
                'imagen_principal' => 'https://placehold.co/100x150/orange/white?text=producto-10',
                'stock' => 7,
                'precio' => 299.99,
                'seccion' => 'Trabajo',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'id' => 11,
                'nombre' => 'COMPRESOR DE AIRE',
                'titulo' => 'COMPRESOR DE AIRE',
                'subtitulo' => 'POTENCIA Y VERSATILIDAD EN TUS MANOS',
                'lema' => '¡Potencia y versatilidad en un solo equipo!',
                'descripcion' => 'Compresor de aire de alta potencia para trabajos industriales y de bricolaje.',
                'imagen_principal' => 'https://placehold.co/100x150/orange/white?text=producto-11',
                'stock' => 9,
                'precio' => 249.99,
                'seccion' => 'Trabajo',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ]
        ];

        DB::table('productos')->insert($productos);
    }
}
