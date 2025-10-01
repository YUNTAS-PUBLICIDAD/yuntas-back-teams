<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title>{{ $data['producto_nombre'] }}</title>
</head>
<body style="margin: 0; padding: 0; background-color: #333333; font-family: Arial, sans-serif;">
    <table border="0" cellpadding="0" cellspacing="0" width="100%">
        <tr>
            <td>
                <table align="center" border="0" cellpadding="0" cellspacing="0" width="600" style="border-collapse: collapse; max-width: 600px; width: 100%;">
                    <tr>
                        <td style="background-color: #2a3b4e; padding: 30px 20px; text-align: center; color: #ffffff;">
                            <h1 style="margin: 0; font-size: 20px;">{{ $data['producto_nombre'] }}</h1>
                            <h2 style="margin: 10px 0 0; font-size: 24px; font-weight: bold;">{{ $data['producto_titulo'] }}</h2>
                        </td>
                    </tr>
                    <tr>
                        <td style="background-color: #2a3b4e;">
                            {{-- asset() genera la URL completa a tu imagen, lo cual es necesario para los correos --}}
                            <img src="{{ asset($data['imagen_principal']) }}" alt="{{ $data['producto_nombre'] }}" width="600" style="display: block; width: 100%; max-width: 600px; height: auto;">
                        </td>
                    </tr>
                    <tr>
                        <td style="background-color: #2a3b4e; padding: 20px 30px; text-align: center; color: #dddddd; font-size: 16px;">
                            <p style="margin: 0;">{{ $data['producto_descripcion'] }}</p>
                        </td>
                    </tr>
                    <tr>
                        <td style="background-color: #ffffff; padding: 20px 10px; font-size: 0; text-align: center;">
                            {{-- Bucle para las imágenes secundarias --}}
                            @if(!empty($data['imagenes_secundarias']))
                                @foreach($data['imagenes_secundarias'] as $imagen)
                                    <img src="{{ asset($imagen->ruta_imagen) }}" alt="Imagen secundaria" width="280" style="display: inline-block; width: 100%; max-width: 280px; height: auto; margin: 5px; border-radius: 15px;">
                                @endforeach
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td style="background-color: #2a3b4e; padding: 30px 20px; text-align: center;">
                            {{-- ✅ 2. EL LINK DEL BOTÓN AHORA USA UNA VARIABLE --}}
                            <a href="{{ $data['url_cotizacion'] ?? '#' }}" style="background-color: #ffffff; color: #2a3b4e; padding: 15px 30px; border-radius: 30px; text-decoration: none; font-weight: bold; font-size: 18px;">
                                ¡COTIZA HOY!
                            </a>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>