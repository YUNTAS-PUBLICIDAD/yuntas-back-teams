<?php


namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Producto;

class ProductoSeeder extends Seeder
{
    public function run()
    {
        $productos = [
            [
                'nombre' => 'Letrero Neón LED Premium',
                'titulo' => 'Letrero Neón LED de Alta Calidad',
                'link' => 'letrero-neon-led-premium',
                'descripcion' => 'Letrero de neón LED personalizable con colores vibrantes y bajo consumo energético. Perfecto para restaurantes, bares y tiendas.',
                'seccion' => 'iluminación',
                
            ],
            [
                'nombre' => 'Letrero Vintage Retro',
                'titulo' => 'Letrero Estilo Vintage',
                'link' => 'letrero-vintage-retro',
                'descripcion' => 'Letrero con acabado vintage perfecto para establecimientos con decoración retro. Fabricado con materiales de primera calidad.',
                'seccion' => 'decoración',
                
            ],
            [
                'nombre' => 'Letrero Digital Inteligente',
                'titulo' => 'Letrero LED Digital',
                'link' => 'letrero-digital-inteligente',
                'descripcion' => 'Letrero digital con pantalla LED de alta resolución, conectividad WiFi y control remoto mediante app móvil.',
                'seccion' => 'tecnología',
            ],
            [
                'nombre' => 'Letrero Acrílico Minimalista',
                'titulo' => 'Letrero Moderno Minimalista',
                'link' => 'letrero-acrilico-minimalista',
                'descripcion' => 'Letrero de acrílico con diseño minimalista, perfecto para oficinas modernas y espacios contemporáneos.',
                'seccion' => 'oficina',
            ],
            [
                'nombre' => 'Letrero Personalizado XL',
                'titulo' => 'Letrero Extra Grande Personalizado',
                'link' => 'letrero-personalizado-xl',
                'descripcion' => 'Letrero de gran formato totalmente personalizable. Ideal para fachadas de tiendas y centros comerciales.',
                'seccion' => 'exterior',
            ],
            [
                'nombre' => 'Letrero Eco-Friendly',
                'titulo' => 'Letrero Ecológico Sostenible',
                'link' => 'letrero-eco-friendly',
                'descripcion' => 'Letrero fabricado con materiales 100% reciclables y energía solar integrada. Perfecto para empresas conscientes del medio ambiente.',
                'seccion' => 'sostenible',
            ],
            [
                'nombre' => 'Letrero Interactivo Táctil',
                'titulo' => 'Letrero con Pantalla Táctil',
                'link' => 'letrero-interactivo-tactil',
                'descripcion' => 'Letrero con pantalla táctil interactiva que permite a los clientes navegar por catálogos y ofertas especiales.',
                'seccion' => 'interactivo',
            ],
            [
                'nombre' => 'Letrero Clásico Madera',
                'titulo' => 'Letrero Tradicional de Madera',
                'link' => 'letrero-clasico-madera',
                'descripcion' => 'Letrero artesanal tallado en madera maciza con acabados naturales. Perfecto para cabañas, restaurantes rústicos y tiendas de antaño.',
                'seccion' => 'artesanal',
                
            ],
        ];

        foreach ($productos as $productoData) {
            Producto::create($productoData);
        }
    }
}
