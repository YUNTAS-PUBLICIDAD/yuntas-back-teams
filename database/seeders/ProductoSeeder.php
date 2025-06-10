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
                'subtitulo' => 'Iluminación profesional para tu negocio',
                'link' => 'letrero-neon-led-premium',
                'lema' => 'Brilla con estilo',
                'descripcion' => 'Letrero de neón LED personalizable con colores vibrantes y bajo consumo energético. Perfecto para restaurantes, bares y tiendas.',
                'stock' => 50,
                'precio' => 299.99,
                'seccion' => 'iluminación',
                'especificaciones' => json_encode([
                    'color' => 'multicolor',
                    'material' => 'acrílico',
                    'voltaje' => '12V',
                    'consumo' => '24W',
                ]),
            ],
            [
                'nombre' => 'Letrero Vintage Retro',
                'titulo' => 'Letrero Estilo Vintage',
                'subtitulo' => 'Diseño retro auténtico',
                'link' => 'letrero-vintage-retro',
                'lema' => 'Nostalgia que vende',
                'descripcion' => 'Letrero con acabado vintage perfecto para establecimientos con decoración retro. Fabricado con materiales de primera calidad.',
                'stock' => 30,
                'precio' => 189.99,
                'seccion' => 'decoración',
                'especificaciones' => json_encode([
                    'color' => 'sepia',
                    'material' => 'madera',
                    'acabado' => 'envejecido',
                ]),
            ],
            [
                'nombre' => 'Letrero Digital Inteligente',
                'titulo' => 'Letrero LED Digital',
                'subtitulo' => 'Tecnología de última generación',
                'link' => 'letrero-digital-inteligente',
                'lema' => 'El futuro de la publicidad',
                'descripcion' => 'Letrero digital con pantalla LED de alta resolución, conectividad WiFi y control remoto mediante app móvil.',
                'stock' => 25,
                'precio' => 599.99,
                'seccion' => 'tecnología',
                'especificaciones' => json_encode([
                    'resolución' => '1920x1080',
                    'conectividad' => 'WiFi',
                    'pantalla' => '32 pulgadas',
                    'control' => 'app móvil',
                ]),
            ],
            [
                'nombre' => 'Letrero Acrílico Minimalista',
                'titulo' => 'Letrero Moderno Minimalista',
                'subtitulo' => 'Elegancia en simplicidad',
                'link' => 'letrero-acrilico-minimalista',
                'lema' => 'Menos es más',
                'descripcion' => 'Letrero de acrílico con diseño minimalista, perfecto para oficinas modernas y espacios contemporáneos.',
                'stock' => 75,
                'precio' => 149.99,
                'seccion' => 'oficina',
                'especificaciones' => json_encode([
                    'color' => 'transparente',
                    'material' => 'acrílico',
                    'grosor' => '5mm',
                    'montaje' => 'pared',
                ]),
            ],
            [
                'nombre' => 'Letrero Personalizado XL',
                'titulo' => 'Letrero Extra Grande Personalizado',
                'subtitulo' => 'Máximo impacto visual',
                'link' => 'letrero-personalizado-xl',
                'lema' => 'Grande en todos los sentidos',
                'descripcion' => 'Letrero de gran formato totalmente personalizable. Ideal para fachadas de tiendas y centros comerciales.',
                'stock' => 15,
                'precio' => 899.99,
                'seccion' => 'exterior',
                'especificaciones' => json_encode([
                    'tamaño' => 'personalizable',
                    'material' => 'PVC',
                    'resistencia' => 'intemperie',
                    'instalación' => 'profesional',
                ]),
            ],
            [
                'nombre' => 'Letrero Eco-Friendly',
                'titulo' => 'Letrero Ecológico Sostenible',
                'subtitulo' => 'Responsabilidad ambiental',
                'link' => 'letrero-eco-friendly',
                'lema' => 'Cuida el planeta mientras vendes',
                'descripcion' => 'Letrero fabricado con materiales 100% reciclables y energía solar integrada. Perfecto para empresas conscientes del medio ambiente.',
                'stock' => 40,
                'precio' => 399.99,
                'seccion' => 'sostenible',
                'especificaciones' => json_encode([
                    'material' => 'bambú',
                    'energía' => 'solar',
                    'certificación' => 'eco-friendly',
                    'reciclable' => '100%',
                ]),
            ],
            [
                'nombre' => 'Letrero Interactivo Táctil',
                'titulo' => 'Letrero con Pantalla Táctil',
                'subtitulo' => 'Interacción directa con clientes',
                'link' => 'letrero-interactivo-tactil',
                'lema' => 'Toca y descubre',
                'descripcion' => 'Letrero con pantalla táctil interactiva que permite a los clientes navegar por catálogos y ofertas especiales.',
                'stock' => 20,
                'precio' => 1299.99,
                'seccion' => 'interactivo',
                'especificaciones' => json_encode([
                    'pantalla' => 'táctil 43"',
                    'procesador' => 'Android',
                    'conectividad' => 'WiFi/Bluetooth',
                    'software' => 'incluido',
                ]),
            ],
            [
                'nombre' => 'Letrero Clásico Madera',
                'titulo' => 'Letrero Tradicional de Madera',
                'subtitulo' => 'Artesanía tradicional',
                'link' => 'letrero-clasico-madera',
                'lema' => 'Tradición que perdura',
                'descripcion' => 'Letrero artesanal tallado en madera maciza con acabados naturales. Perfecto para cabañas, restaurantes rústicos y tiendas de antaño.',
                'stock' => 35,
                'precio' => 249.99,
                'seccion' => 'artesanal',
                'especificaciones' => json_encode([
                    'material' => 'roble',
                    'acabado' => 'barniz natural',
                    'tallado' => 'manual',
                    'tratamiento' => 'anti-humedad',
                ]),
            ],
        ];

        foreach ($productos as $productoData) {
            $producto = Producto::create($productoData);

            // Crear dimensiones si el método existe
            if (method_exists($producto, 'dimensiones')) {
                $dimensiones = [
                    ['tipo' => 'alto', 'valor' => rand(20, 100) . 'cm'],
                    ['tipo' => 'ancho', 'valor' => rand(30, 200) . 'cm'],
                    ['tipo' => 'largo', 'valor' => rand(5, 20) . 'cm'],
                ];
                $producto->dimensiones()->createMany($dimensiones);
            }
        }
    }
}
