<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ideas de mensaje luego de registro</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f4f4f4;
        }

        .email-container {
            background-color: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
        }

        .welcome-title {
            color: #2c5aa0;
            font-size: 24px;
            margin-bottom: 20px;
        }

        .highlight {
            background: linear-gradient(90deg, #4a90e2, #7b68ee);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            font-weight: bold;
        }

        .contact-info {
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 6px;
            margin: 20px 0;
            border-left: 4px solid #4a90e2;
        }

        .contact-info h3 {
            margin-top: 0;
            color: #2c5aa0;
        }

        .cta {
            background: linear-gradient(135deg, #4a90e2, #7b68ee);
            color: white;
            padding: 15px 30px;
            border-radius: 25px;
            text-decoration: none;
            display: inline-block;
            font-weight: bold;
            margin: 20px 0;
            text-align: center;
        }

        .footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid #eee;
            font-size: 14px;
            color: #666;
            text-align: center;
        }

        .team-signature {
            margin-top: 30px;
            font-style: italic;
            color: #4a90e2;
        }

        .emoji {
            font-size: 1.2em;
        }
    </style>
</head>

<body>
    <div class="email-container">
        <!-- Encabezado -->
        <div class="header">
            <h1 class="welcome-title">¡Hola {{ $data['name'] }}! <span class="emoji">👋</span></h1>
        </div>

        <!-- Cuerpo del mensaje -->
        <div>
            <p>Gracias por registrarte en <strong>Yuntas</strong>.</p>

            <p>Ahora formas parte de una comunidad única <span class="highlight">que convierte cualquier espacio en algo único y lleno de luz</span> <span class="emoji">🌟✨</span></p>

            <p>Queremos que tu experiencia sea tan brillante como nuestros letreros, por eso aquí tienes la información de tu cuenta:</p>

            <!-- Información de contacto -->
            <div class="contact-info">
                <p><span class="emoji">💎</span> <strong>Email registrado:</strong> {{ $data['email'] }}</p>
                <p><span class="emoji">💎</span> <strong>Celular:</strong> {{ $data['celular'] }}</p>
            </div>

            <p><span class="emoji">🚀</span> Desde ahora podrás acceder a nuestros diseños exclusivos, promociones y todas las novedades que tenemos para ti.</p>

            <!-- Pie de página -->
            <div>
                <p><strong>Pie de página:</strong></p>
                <p>Si tienes alguna duda o consulta, recuerda que siempre estamos para ti.</p>
                <p><span class="emoji">📞</span> <a href="mailto:yuntasproducciones@gmail.com">Contáctanos aquí</a></p>
            </div>

            <!-- Firma del equipo -->
            <div class="team-signature">
                <p>Con cariño,<br>
                    El equipo de <strong>Yuntas</strong> <span class="emoji">💜</span></p>
            </div>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p>&copy; 2025 Yuntas – Todos los derechos reservados</p>
            <hr style="margin: 20px 0; border: none; border-top: 1px solid #eee;">
            <p><strong>¡Bienvenid@ a la familia de Yuntas!</strong> <span class="emoji">🏠</span></p>
        </div>
    </div>
</body>

</html>