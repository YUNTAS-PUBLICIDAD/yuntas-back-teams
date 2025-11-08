<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title>Información Importante</title>
</head>
<body style="margin: 0; padding: 0; background-color: #f5f5f5; font-family: Arial, sans-serif;">
    <table border="0" cellpadding="0" cellspacing="0" width="100%">
        <tr>
            <td align="center" style="padding: 40px 0;">
                <table border="0" cellpadding="0" cellspacing="0" width="600" style="border-collapse: collapse; background-color: #ffffff; border-radius: 10px; overflow: hidden; box-shadow: 0 4px 8px rgba(0,0,0,0.1);">
                    <!-- Header -->
                    <tr>
                        <td style="background-color: #2d3748; padding: 30px 20px; text-align: center;">
                            <h1 style="color: #ffffff; margin: 0; font-size: 28px; font-weight: bold;">
                                ¡Hola, {{ $data['name'] }}!
                            </h1>
                        </td>
                    </tr>

                    <!-- Main Image -->
                    <tr>
                        <td style="padding: 0;">
                            <img src="{{ $data['imagen_principal'] }}" alt="Imagen Principal" width="600" style="display: block; width: 100%; height: auto; border: 0;">
                        </td>
                    </tr>

                    <!-- Content Section -->
                    <tr>
                        <td style="padding: 30px 40px; text-align: center;">
                            <h2 style="color: #2d3748; margin: 0 0 20px 0; font-size: 24px;">
                                Información Especial Para Ti
                            </h2>
                            <p style="color: #4a5568; font-size: 16px; line-height: 1.6; margin: 0 0 20px 0;">
                                Gracias por tu interés. Estamos encantados de compartir esta información contigo.
                            </p>
                            <p style="color: #4a5568; font-size: 16px; line-height: 1.6; margin: 0;">
                                ¡Esperamos que encuentres esto útil y atractivo!
                            </p>
                        </td>
                    </tr>

                    <!-- Features -->
                    <tr>
                        <td style="padding: 20px 40px; background-color: #f7fafc;">
                            <table border="0" cellpadding="0" cellspacing="0" width="100%">
                                <tr>
                                    <td width="33%" align="center" style="padding: 10px;">
                                        <div style="background-color: #e2e8f0; border-radius: 50%; width: 80px; height: 80px; display: flex; align-items: center; justify-content: center; margin: 0 auto 10px;">
                                            <span style="color: #2d3748; font-size: 24px; font-weight: bold;">✓</span>
                                        </div>
                                        <p style="color: #4a5568; font-size: 14px; margin: 0;">Calidad Garantizada</p>
                                    </td>
                                    <td width="33%" align="center" style="padding: 10px;">
                                        <div style="background-color: #e2e8f0; border-radius: 50%; width: 80px; height: 80px; display: flex; align-items: center; justify-content: center; margin: 0 auto 10px;">
                                            <span style="color: #2d3748; font-size: 24px; font-weight: bold;">⚡</span>
                                        </div>
                                        <p style="color: #4a5568; font-size: 14px; margin: 0;">Entrega Rápida</p>
                                    </td>
                                    <td width="33%" align="center" style="padding: 10px;">
                                        <div style="background-color: #e2e8f0; border-radius: 50%; width: 80px; height: 80px; display: flex; align-items: center; justify-content: center; margin: 0 auto 10px;">
                                            <span style="color: #2d3748; font-size: 24px; font-weight: bold;">★</span>
                                        </div>
                                        <p style="color: #4a5568; font-size: 14px; margin: 0;">Servicio Premium</p>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <!-- CTA Button -->
                    <tr>
                        <td style="padding: 40px 20px; text-align: center; background-color: #2d3748;">
                            <a href="https://wa.link/hh5pjv" style="background-color: #ffffff; color: #2d3748; padding: 15px 40px; border-radius: 25px; text-decoration: none; font-weight: bold; font-size: 18px; display: inline-block;">
                                ¡CONTÁCTANOS AHORA!
                            </a>
                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td style="padding: 20px; text-align: center; background-color: #1a202c;">
                            <p style="color: #a0aec0; font-size: 14px; margin: 0 0 10px 0;">
                                &copy; 2024 Yuntas Publicidad. Todos los derechos reservados.
                            </p>
                            <p style="color: #a0aec0; font-size: 12px; margin: 0;">
                                Si tienes alguna pregunta, no dudes en contactarnos.
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
