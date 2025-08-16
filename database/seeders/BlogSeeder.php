<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\Blog;
use App\Models\BlogImagenes;
use App\Models\BlogParrafos;

class BlogSeeder extends Seeder
{
    public function run(): void
    {
        DB::beginTransaction();

        try {
            $blogsData = [
                [
                    'producto_id' => 1,
                    'subtitulo' => 'Elegancia y Profesionalismo en tu Marca',
                    'imagen_principal' => 'https://i.imgur.com/bKisDUE.png',
                    'link' => 'letreros-acrilicos-blog',
                    'parrafos' => [
                        'En el panorama actual de la comunicación visual, los letreros acrílicos se han convertido en una herramienta clave para proyectar una imagen moderna y profesional. En Yuntas, nos especializamos en el diseño y fabricación de letreros acrílicos personalizados que combinan estética y funcionalidad para captar la atención y reforzar la identidad de tu negocio.',
                    ],
                    'imagenes' => [
                        ['ruta_imagen' => 'https://i.imgur.com/bKisDUE.png', 'text_alt' => 'Imagen del blog Producto Test'],
                    ],
                ],

                [
                    'producto_id' => 1,
                    'subtitulo' => 'Moderniza tu comunicación con paneles LED de alto impacto',
                    'imagen_principal' => 'https://i.imgur.com/vgxpLns.png',
                    'link' => 'paneles-led-electronicos-blog',
                    'parrafos' => [
                        'En un mundo donde la atención es efímera, destacar ya no es una opción: es una necesidad. Las marcas que realmente conectan son aquellas que entienden el poder de lo visual. Los paneles LED electrónicos no solo iluminan espacios, los transforman en puntos de contacto directo entre la marca y el cliente. En un abrir y cerrar de ojos, una vitrina o fachada puede convertirse en un mensaje claro, dinámico y memorable.',
                    ],
                    'imagenes' => [
                        ['ruta_imagen' => 'https://i.imgur.com/vgxpLns.png', 'text_alt' => 'Imagen del blog Producto Test'],
                    ],
                ],

                [
                    'producto_id' => 1,
                    'subtitulo' => 'Haz que tu espacio hable con luz',
                    'imagen_principal' => 'https://i.imgur.com/ZfXUcxC.png',
                    'link' => 'letreros-neon-led-blog',
                    'parrafos' => [
                        'La conexión entre una marca y su público empieza con la experiencia, y muchas veces, esa experiencia nace de una imagen que impacta. Un letrero Neón LED puede ser ese primer "click visual" que detiene la mirada o invita a tomarse una foto. En un mercado donde la diferenciación es clave, comunicar con luz se vuelve un recurso poderoso.',
                    ],
                    'imagenes' => [
                        ['ruta_imagen' => 'https://i.imgur.com/ZfXUcxC.png', 'text_alt' => 'Dispositivos tecnológicos modernos'],
                    ],
                ],
            ];

            foreach ($blogsData as $blogData) {
             
                $blog = Blog::create([
                    'producto_id' => $blogData['producto_id'],
                    'subtitulo' => $blogData['subtitulo'],
                    'imagen_principal' => $blogData['imagen_principal'],
                    'link' => $blogData['link'],
                ]);

                foreach ($blogData['parrafos'] as $parrafoTexto) {
                    BlogParrafos::create([
                        'blog_id' => $blog->id,
                        'parrafo' => $parrafoTexto
                    ]);
                }

                foreach ($blogData['imagenes'] as $imagenData) {
                    BlogImagenes::create([
                        'blog_id' => $blog->id,
                        'ruta_imagen' => $imagenData['ruta_imagen'],
                        'text_alt' => $imagenData['text_alt']
                    ]);
                }

                $this->command->info("Blog creado: {$blog->subtitulo}");
            }

            DB::commit();
            $this->command->info('BlogSeeder completado exitosamente. Se crearon ' . count($blogsData) . ' blogs.');

        } catch (\Exception $e) {
            DB::rollBack();
            $this->command->error('Error al ejecutar BlogSeeder: ' . $e->getMessage());
            throw $e;
        }
    }
}