<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ImagenProductoSeeder extends Seeder
{
    public function run(): void
    {
        $imagenes = [
            [
                'producto_id' => 1,
                'url_imagen' => 'https://placehold.co/400x300/ff6b35/white?text=Letrero+Neon+LED+Premium',
                'texto_alt_SEO' => 'Letrero neón LED premium multicolor para negocios',
            ],
            [
                'producto_id' => 1,
                'url_imagen' => 'https://placehold.co/400x300/ff6b35/white?text=LED+Neon+Vista+Lateral',
                'texto_alt_SEO' => 'Vista lateral del letrero neón LED premium',
            ],
            [
                'producto_id' => 1,
                'url_imagen' => 'https://placehold.co/400x300/ff6b35/white?text=LED+Neon+Instalado',
                'texto_alt_SEO' => 'Letrero neón LED instalado en establecimiento comercial',
            ],

            // Letrero Vintage Retro (ID: 2)
            [
                'producto_id' => 2,
                'url_imagen' => 'https://placehold.co/400x300/8b4513/white?text=Letrero+Vintage+Retro',
                'texto_alt_SEO' => 'Letrero vintage retro con acabado envejecido',
            ],
            [
                'producto_id' => 2,
                'url_imagen' => 'https://placehold.co/400x300/8b4513/white?text=Vintage+Detalle',
                'texto_alt_SEO' => 'Detalle del acabado vintage en letrero retro',
            ],

            // Letrero Digital Inteligente (ID: 3)
            [
                'producto_id' => 3,
                'url_imagen' => 'https://placehold.co/400x300/2c3e50/white?text=Letrero+Digital+HD',
                'texto_alt_SEO' => 'Letrero digital inteligente con pantalla LED HD',
            ],
            [
                'producto_id' => 3,
                'url_imagen' => 'https://placehold.co/400x300/2c3e50/white?text=Control+App+Movil',
                'texto_alt_SEO' => 'Control de letrero digital mediante aplicación móvil',
            ],
            [
                'producto_id' => 3,
                'url_imagen' => 'https://placehold.co/400x300/2c3e50/white?text=Display+Funcionando',
                'texto_alt_SEO' => 'Letrero digital funcionando con contenido dinámico',
            ],

            // Letrero Acrílico Minimalista (ID: 4)
            [
                'producto_id' => 4,
                'url_imagen' => 'https://placehold.co/400x300/ecf0f1/333?text=Acrilico+Minimalista',
                'texto_alt_SEO' => 'Letrero acrílico minimalista para oficinas modernas',
            ],
            [
                'producto_id' => 4,
                'url_imagen' => 'https://placehold.co/400x300/ecf0f1/333?text=Montaje+Pared',
                'texto_alt_SEO' => 'Instalación de letrero acrílico en pared de oficina',
            ],

            // Letrero Personalizado XL (ID: 5)
            [
                'producto_id' => 5,
                'url_imagen' => 'https://placehold.co/400x300/e74c3c/white?text=Letrero+XL+Fachada',
                'texto_alt_SEO' => 'Letrero extra grande personalizado para fachada comercial',
            ],
            [
                'producto_id' => 5,
                'url_imagen' => 'https://placehold.co/400x300/e74c3c/white?text=Instalacion+XL',
                'texto_alt_SEO' => 'Proceso de instalación de letrero XL personalizado',
            ],

            // Letrero Eco-Friendly (ID: 6)
            [
                'producto_id' => 6,
                'url_imagen' => 'https://placehold.co/400x300/27ae60/white?text=Letrero+Eco+Bambu',
                'texto_alt_SEO' => 'Letrero ecológico fabricado con bambú sostenible',
            ],
            [
                'producto_id' => 6,
                'url_imagen' => 'https://placehold.co/400x300/27ae60/white?text=Panel+Solar+Integrado',
                'texto_alt_SEO' => 'Panel solar integrado en letrero eco-friendly',
            ],

            // Letrero Interactivo Táctil (ID: 7)
            [
                'producto_id' => 7,
                'url_imagen' => 'https://placehold.co/400x300/9b59b6/white?text=Pantalla+Tactil+43',
                'texto_alt_SEO' => 'Letrero interactivo con pantalla táctil de 43 pulgadas',
            ],
            [
                'producto_id' => 7,
                'url_imagen' => 'https://placehold.co/400x300/9b59b6/white?text=Interface+Interactiva',
                'texto_alt_SEO' => 'Interfaz interactiva del letrero táctil para clientes',
            ],
            [
                'producto_id' => 7,
                'url_imagen' => 'https://placehold.co/400x300/9b59b6/white?text=Menu+Digital',
                'texto_alt_SEO' => 'Menú digital interactivo en letrero táctil',
            ],

            // Letrero Clásico Madera (ID: 8)
            [
                'producto_id' => 8,
                'url_imagen' => 'https://placehold.co/400x300/a0522d/white?text=Letrero+Madera+Roble',
                'texto_alt_SEO' => 'Letrero artesanal tallado en madera de roble',
            ],
            [
                'producto_id' => 8,
                'url_imagen' => 'https://placehold.co/400x300/a0522d/white?text=Tallado+Manual',
                'texto_alt_SEO' => 'Detalle del tallado manual en letrero de madera',
            ],
            [
                'producto_id' => 8,
                'url_imagen' => 'https://placehold.co/400x300/a0522d/white?text=Acabado+Natural',
                'texto_alt_SEO' => 'Acabado natural con barniz en letrero de madera clásico',
            ],
        ];

        DB::table('producto_imagenes')->insert($imagenes);
    }
}
