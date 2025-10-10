<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Producto</title>
</head>

<body style="font-family: Arial, sans-serif; background-color: #f5f5f5; margin: 0; padding: 0; box-sizing: border-box;">

    <div style="max-width: 1200px; margin: 0 auto; background-color: white; box-sizing: border-box;">

        {{-- Header dinámico --}}
        <div style="background-color: #1e3a5f; color: white; text-align: center; padding: 20px; font-size: 24px; font-weight: bold; font-style: italic; letter-spacing: 1px;">
            {{ $data['producto_titulo'] }}
        </div>

        {{-- Imagen principal dinámica (sin asset()) --}}
        <div style="width: 100%; overflow: hidden;">
            <img src="{{ $data['imagen_principal'] }}" alt="Producto" style="width: 100%; height: auto; display: block;">
        </div>

        <div style="padding: 30px 20px; text-align: center; box-sizing: border-box;">
            {{-- Tagline dinámico --}}
            <div style="color: #333; font-size: 20px; font-weight: bold; margin-bottom: 25px;">
                {{ $data['producto_descripcion'] }}
            </div>

            {{-- Grid de imágenes secundarias dinámicas --}}
            @if(isset($data['imagenes_secundarias']) && count($data['imagenes_secundarias']) > 0)
            <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 15px; margin-bottom: 30px;">
                @foreach($data['imagenes_secundarias'] as $imagen)
                <div style="background-color: transparent; border-radius: 10px; aspect-ratio: 4 / 5; overflow: hidden; display: flex; align-items: center; justify-content: center;">
                    <img src="{{ $imagen }}" alt="Imagen {{ $loop->iteration }}" style="width: 100%; height: 100%; object-fit: cover; border-radius: 10px;">
                </div>
                @endforeach
            </div>
            @endif

            <div style="height: 2px; background-color: black; width: 100%; margin: 0 auto 20px auto;"></div>

            {{-- Sección estática --}}
            <div style="background-color: white; padding: 15px; margin-bottom: 20px;">
                <div style="color: #1e3a5f; font-size: 22px; font-weight: bold; margin-bottom: 3px;">ENVÍO GRATIS</div>
                <div style="color: #666; font-size: 14px;">A TODO LIMA</div>
            </div>
        </div>

        <div style="background-color: #1e3a5f; padding: 20px; text-align: center;">
            <a href="#" style="background-color: white; color: #1e3a5f; border: 3px solid #1e3a5f; border-radius: 25px; padding: 12px 40px; font-size: 16px; font-weight: bold; text-transform: uppercase; text-decoration: none; display: inline-block;">¡COTIZA HOY!</a>
        </div>
    </div>
</body>

</html>