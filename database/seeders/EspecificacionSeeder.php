<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Producto; 
use App\Models\Especificacion; 

class EspecificacionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $productos = Producto::all();

        $especificacionesPorProducto = [
            'Letrero Neón LED Premium' => [
                ['clave' => 'Color', 'valor' => 'Rojo, Azul, Verde, Blanco (personalizable)'],
                ['clave' => 'Material', 'valor' => 'Acrílico y Silicona LED'],
                ['clave' => 'Alimentación', 'valor' => '12V DC'],
                ['clave' => 'Uso', 'valor' => 'Interior'],
            ],
            'Letrero Vintage Retro' => [
                ['clave' => 'Material', 'valor' => 'Metal envejecido'],
                ['clave' => 'Acabado', 'valor' => 'Pintura desgastada'],
                ['clave' => 'Estilo', 'valor' => 'Industrial, retro'],
                ['clave' => 'Montaje', 'valor' => 'Pared'],
            ],
            'Letrero Digital Inteligente' => [
                ['clave' => 'Pantalla', 'valor' => 'LED Full Color'],
                ['clave' => 'Conectividad', 'valor' => 'WiFi, USB'],
                ['clave' => 'Software', 'valor' => 'Control por App móvil'],
                ['clave' => 'Resolución', 'valor' => 'Alta Definición'],
            ],
            'Letrero Acrílico Minimalista' => [
                ['clave' => 'Material', 'valor' => 'Acrílico transparente'],
                ['clave' => 'Grosor', 'valor' => '5mm'],
                ['clave' => 'Montaje', 'valor' => 'Separadores metálicos'],
                ['clave' => 'Estilo', 'valor' => 'Moderno, elegante'],
            ],
            'Letrero Personalizado XL' => [
                ['clave' => 'Tamaño', 'valor' => 'Personalizable (hasta 3x2 metros)'],
                ['clave' => 'Material', 'valor' => 'Aluminio, Acrílico'],
                ['clave' => 'Uso', 'valor' => 'Exterior, Interior'],
                ['clave' => 'Iluminación', 'valor' => 'Opcional (LED)'],
            ],
            'Letrero Eco-Friendly' => [
                ['clave' => 'Material', 'valor' => 'Madera reciclada, Plástico reciclado'],
                ['clave' => 'Fuente de energía', 'valor' => 'Panel solar integrado'],
                ['clave' => 'Certificación', 'valor' => 'Eco-label'],
                ['clave' => 'Durabilidad', 'valor' => 'Alta'],
            ],
            'Letrero Interactivo Táctil' => [
                ['clave' => 'Pantalla', 'valor' => 'Táctil Capacitiva'],
                ['clave' => 'Sistema Operativo', 'valor' => 'Android / Linux'],
                ['clave' => 'Conectividad', 'valor' => 'Ethernet, WiFi'],
                ['clave' => 'Funcionalidad', 'valor' => 'Navegación, multimedia'],
            ],
            'Letrero Clásico Madera' => [
                ['clave' => 'Material', 'valor' => 'Madera de pino maciza'],
                ['clave' => 'Acabado', 'valor' => 'Barniz protector'],
                ['clave' => 'Talla', 'valor' => 'Artesanal'],
                ['clave' => 'Estilo', 'valor' => 'Rústico, tradicional'],
            ],
        ];

        foreach ($productos as $producto) {
            if (isset($especificacionesPorProducto[$producto->nombre])) {
                foreach ($especificacionesPorProducto[$producto->nombre] as $espData) {
                    Especificacion::create([
                        'producto_id' => $producto->id,
                        'clave' => $espData['clave'],
                        'valor' => $espData['valor'],
                    ]);
                }
            } else {

                Especificacion::create([
                    'producto_id' => $producto->id,
                    'clave' => 'Material',
                    'valor' => 'Material genérico',
                ]);
                Especificacion::create([
                    'producto_id' => $producto->id,
                    'clave' => 'Dimensiones',
                    'valor' => 'Variable',
                ]);
            }
        }
    }
}