<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Producto</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            background-color: white;
        }

        .header {
            background-color: #1e3a5f;
            color: white;
            text-align: center;
            padding: 20px;
            font-size: 24px;
            font-weight: bold;
            font-style: italic;
            letter-spacing: 1px;
        }

        .main-image {
            width: 100%;
            overflow: hidden;
        }

        .main-image img {
            width: 100%;
            height: auto;
            display: block;
        }

        .content {
            padding: 30px 20px;
            text-align: center;
        }

        .tagline {
            color: #333;
            font-size: 20px;
            font-weight: bold;
            margin-bottom: 25px;
        }

        .products-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 15px;
            margin-bottom: 30px;
        }

        .product-card {
            background-color: transparent;
            border-radius: 10px;
            aspect-ratio: 4 / 5;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .product-logo {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 10px;
        }

        .separator {
            height: 2px;
            background-color: black;
            width: 100%;
            margin: 0 auto 20px auto;
        }

        .shipping {
            background-color: white;
            padding: 15px;
            margin-bottom: 20px;
        }

        .shipping-title {
            color: #1e3a5f;
            font-size: 22px;
            font-weight: bold;
            margin-bottom: 3px;
        }

        .shipping-subtitle {
            color: #666;
            font-size: 14px;
        }

        .cta-button {
            background-color: white;
            color: #1e3a5f;
            border: 3px solid #1e3a5f;
            border-radius: 25px;
            padding: 12px 40px;
            font-size: 16px;
            font-weight: bold;
            text-transform: uppercase;
            text-decoration: none;
            display: inline-block;
        }

        .footer-section {
            background-color: #1e3a5f;
            padding: 20px;
            text-align: center;
        }

        @media (max-width: 768px) {
            .products-grid {
                grid-template-columns: 1fr;
            }

            .tagline {
                font-size: 18px;
            }

            .cta-button {
                padding: 10px 30px;
                font-size: 14px;
            }
        }

        @media (max-width: 480px) {
            .content {
                padding: 20px 15px;
            }

            .tagline {
                font-size: 16px;
            }

            .shipping-title {
                font-size: 18px;
            }

            .shipping-subtitle {
                font-size: 13px;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        {{-- Header dinámico --}}
        <div class="header">
            {{ $data['producto_titulo'] }}
        </div>

        {{-- Imagen principal dinámica (sin asset()) --}}
        <div class="main-image">
            <img src="{{ $data['imagen_principal'] }}" alt="Producto">
        </div>

        <div class="content">
            {{-- Tagline dinámico --}}
            <div class="tagline">
                {{ $data['producto_descripcion'] }}
            </div>

            {{-- Grid de imágenes secundarias dinámicas --}}
            @if(isset($data['imagenes_secundarias']) && count($data['imagenes_secundarias']) > 0)
            <div class="products-grid">
                @foreach($data['imagenes_secundarias'] as $imagen)
                <div class="product-card">
                    <img src="{{ $imagen }}" alt="Imagen {{ $loop->iteration }}" class="product-logo">
                </div>
                @endforeach
            </div>
            @endif


            <div class="separator"></div>

            {{-- Sección estática --}}
            <div class="shipping">
                <div class="shipping-title">ENVÍO GRATIS</div>
                <div class="shipping-subtitle">A TODO LIMA</div>
            </div>
        </div>

        <div class="footer-section">
            <a href="#" class="cta-button">¡COTIZA HOY!</a>
        </div>
    </div>
</body>


</html>