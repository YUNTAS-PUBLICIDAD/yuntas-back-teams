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
        $blogs = [
            [
                'id_blog' => 1,
                'titulo' => 'Transformando espacios con iluminación LED: Tendencias 2023',
                'descripcion' => 'Descubre cómo la iluminación LED está revolucionando el diseño de interiores y eventos.',
                'imagen_principal' => '/Blogs/tendencias-led-2023.webp',
                'estatus' => 'publicado',
                'fecha_creacion' => Carbon::now(),
                'fecha_actualizacion' => Carbon::now(),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'id_blog' => 2,
                'titulo' => 'Eventos inolvidables: El poder de los pisos y mobiliario LED',
                'descripcion' => 'Las nuevas tendencias en diseño de eventos incorporan elementos LED para crear experiencias inmersivas.',
                'imagen_principal' => '/Blogs/eventos-led.webp',
                'estatus' => 'publicado',
                'fecha_creacion' => Carbon::now(),
                'fecha_actualizacion' => Carbon::now(),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'id_blog' => 3,
                'titulo' => 'Letreros Neón LED: El nuevo estándar para negocios modernos',
                'descripcion' => 'Los letreros tradicionales de neón dan paso a la tecnología LED: más segura, eficiente y versátil.',
                'imagen_principal' => '/Blogs/letreros-led.webp',
                'estatus' => 'publicado',
                'fecha_creacion' => Carbon::now(),
                'fecha_actualizacion' => Carbon::now(),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
        ];

        DB::table('blogs')->insert($blogs);
    }
}
