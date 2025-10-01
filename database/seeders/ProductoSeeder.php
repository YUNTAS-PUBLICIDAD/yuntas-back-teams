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
                'imagen_principal' => 'premium-neon.jpg',
                'especificaciones' => ['Brillo: Alto', 'Colores: RGB', 'Dimensiones: Personalizables'],
                'beneficios' => ['Bajo consumo', 'Larga vida útil', 'Fácil instalación'],
            ],
            [
                'nombre' => 'Letrero Vintage Retro',
                'titulo' => 'Letrero Estilo Vintage',
                'link' => 'letrero-vintage-retro',
                'descripcion' => 'Letrero con acabado vintage perfecto para establecimientos con decoración retro. Fabricado con materiales de primera calidad.',
                'seccion' => 'decoración',
                'imagen_principal' => 'vintage-retro.jpg',
                'especificaciones' => ['Material: Metal envejecido', 'Estilo: Retro', 'Dimensiones: Varias'],
                'beneficios' => ['Diseño único', 'Durabilidad', 'Resistente a la corrosión'],
            ],
            [
                'nombre' => 'Letrero Digital Inteligente',
                'titulo' => 'Letrero LED Digital',
                'link' => 'letrero-digital-inteligente',
                'descripcion' => 'Letrero digital con pantalla LED de alta resolución, conectividad WiFi y control remoto mediante app móvil.',
                'seccion' => 'tecnología',
                'imagen_principal' => 'digital-inteligente.jpg',
                'especificaciones' => ['Conectividad: Wi-Fi', 'Pantalla: LED de alta resolución', 'Control: App Móvil'],
                'beneficios' => ['Actualización remota', 'Contenido dinámico', 'Ideal para publicidad'],
            ],
            [
                'nombre' => 'Letrero Acrílico Minimalista',
                'titulo' => 'Letrero Moderno Minimalista',
                'link' => 'letrero-acrilico-minimalista',
                'descripcion' => 'Letrero de acrílico con diseño minimalista, perfecto para oficinas modernas y espacios contemporáneos.',
                'seccion' => 'oficina',
                'imagen_principal' => 'acrilico-minimalista.jpg',
                'especificaciones' => ['Material: Acrílico transparente', 'Acabado: Borde pulido', 'Fijación: Tornillos ocultos'],
                'beneficios' => ['Diseño elegante', 'Fácil de limpiar', 'Apariencia profesional'],
            ],
            [
                'nombre' => 'Letrero Personalizado XL',
                'titulo' => 'Letrero Extra Grande Personalizado',
                'link' => 'letrero-personalizado-xl',
                'descripcion' => 'Letrero de gran formato totalmente personalizable. Ideal para fachadas de tiendas y centros comerciales.',
                'seccion' => 'exterior',
                'imagen_principal' => 'personalizado-xl.jpg',
                'especificaciones' => ['Tamaño: Extra grande', 'Personalización: Total', 'Material: Acero inoxidable'],
                'beneficios' => ['Gran visibilidad', 'Impacto visual', 'Resistente a la intemperie'],
            ],
            [
                'nombre' => 'Letrero Eco-Friendly',
                'titulo' => 'Letrero Ecológico Sostenible',
                'link' => 'letrero-eco-friendly',
                'descripcion' => 'Letrero fabricado con materiales 100% reciclables y energía solar integrada. Perfecto para empresas conscientes del medio ambiente.',
                'seccion' => 'sostenible',
                'imagen_principal' => 'eco-friendly.jpg',
                'especificaciones' => ['Material: Reciclado', 'Fuente de energía: Solar', 'Instalación: Exterior'],
                'beneficios' => ['Amigable con el medio ambiente', 'Autónomo', 'Responsabilidad social'],
            ],
            [
                'nombre' => 'Letrero Interactivo Táctil',
                'titulo' => 'Letrero con Pantalla Táctil',
                'link' => 'letrero-interactivo-tactil',
                'descripcion' => 'Letrero con pantalla táctil interactiva que permite a los clientes navegar por catálogos y ofertas especiales.',
                'seccion' => 'interactivo',
                'imagen_principal' => 'interactivo-tactil.jpg',
                'especificaciones' => ['Pantalla: Táctil capacitiva', 'Funcionalidad: Navegación por menú', 'Conectividad: USB y Wi-Fi'],
                'beneficios' => ['Enganche al cliente', 'Información detallada', 'Interactividad'],
            ],
            [
                'nombre' => 'Letrero Clásico Madera',
                'titulo' => 'Letrero Tradicional de Madera',
                'link' => 'letrero-clasico-madera',
                'descripcion' => 'Letrero artesanal tallado en madera maciza con acabados naturales. Perfecto para cabañas, restaurantes rústicos y tiendas de antaño.',
                'seccion' => 'artesanal',
                'imagen_principal' => 'clasico-madera.jpg',
                'especificaciones' => ['Material: Madera de roble', 'Acabado: Natural, tallado a mano', 'Fijación: Soportes de metal'],
                'beneficios' => ['Aspecto rústico', 'Duradero', 'Hecho a mano'],
            ],
        ];

        foreach ($productos as $productoData) {
            Producto::create($productoData);
        }
    }
}
