<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class BlogSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $blog = [
            [
                'link' => 'panel-fibra-bamboo',
                'producto_id' => 1,
                'titulo' => 'Panel de fibra de bamboo',
                'parrafo' => 'Panel de Fibra de Bambú: Sostenibilidad y Estética para la construcción moderna',
                'descripcion' => 'Futuro verde en la construcción Beneficios del bambú',
                'imagen_principal' => 'https://i.imgur.com/bKisDUE.png',
                'created_at' => Carbon::now(),
            ],
            [
                'link' => 'soldadora-lingba',
                'producto_id' => 1,
                'titulo' => 'Soldadora lingba',
                'parrafo' => 'Diseño Sostenible: Interiores Eco-Friendly para Oficinas Modernas',
                'descripcion' => 'Descubre cómo incorporar materiales reciclados y energías renovables',
                'imagen_principal' => 'https://i.imgur.com/vgxpLns.png',
                'created_at' => Carbon::now(),
            ],
            [
                'link' => 'soldadora-spark',
                'producto_id' => 1,
                'titulo' => 'Soldadora spark',
                'parrafo' => 'Iluminación Inteligente: Optimizando Espacios de Trabajo',
                'descripcion' => 'Explora las últimas tendencias en iluminación LED y sistemas de control',
                'imagen_principal' => 'https://i.imgur.com/ZfXUcxC.png',
                'created_at' => Carbon::now(),
            ],
            [
                'link' => 'ventilador-holografico',
                'producto_id' => 1,
                'titulo' => 'Ventilador holográfico',
                'parrafo' => 'Acústica en Restaurantes: Diseño para una Experiencia Culinaria Óptima',
                'descripcion' => 'Aprende sobre materiales y técnicas de diseño para crear ambientes acústicamente agradables',
                'imagen_principal' => 'https://i.imgur.com/ZgElRO5.png',
                'created_at' => Carbon::now(),
            ]
        ];
        DB::table('blogs')->insert($blog);
    }
}
