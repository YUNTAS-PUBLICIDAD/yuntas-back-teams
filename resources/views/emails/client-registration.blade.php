<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro Exitoso - Yuntas</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333333;
            background-color: #f9f9f9;
            margin: 0;
            padding: 0;
        }

        .container {
            margin: 0 auto;
            background-color: #ffffff;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .header {
            width: 100%;
            height: 250px;
            position: relative;
            display: flex;
            justify-content: center;
            align-items: center;
            overflow: hidden;
        }

        .banner-background {
            position: absolute;
            width: 100%;
            height: 100%;
            z-index: 1;
        }

        .header-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 2;
        }

        .button-container {
            position: absolute;
            top: 60%;
            left: 10%;
            transform: translateY(-50%);
            z-index: 3;
        }

        .whatsapp-button {
            background-color: #22C1DE;
            color: white;
            text-decoration: none;
            padding: 15px 30px;
            border-radius: 50px;
            font-weight: bold;
            font-size: 18px;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3);
            display: inline-block;
            text-align: center;
            white-space: nowrap;
        }

        .whatsapp-button:hover {
            background-color: #1990A6;
            transform: translateY(-2px) scale(1.05);
            box-shadow: 0 6px 18px rgba(0, 0, 0, 0.4);
        }

        .main-content {
            padding: 30px;
        }

        .greeting {
            font-size: 24px;
            margin-bottom: 20px;
            color: #2c3e50;
        }

        .message {
            margin-bottom: 15px;
            font-size: 16px;
        }

        .contact-info {
            margin-top: 25px;
            padding: 15px;
            background-color: #f5f7fa;
            border-radius: 5px;
        }

        .contact-item {
            margin-bottom: 10px;
        }

        .whatsapp-link {
            color: #25D366;
            text-decoration: none;
            font-weight: bold;
        }

        .whatsapp-link:hover {
            text-decoration: underline;
        }

        .footer {
            text-align: center;
            padding: 20px;
            background-color: #2c3e50;
            color: #ffffff;
        }

        .social-media {
            display: flex;
            justify-content: center;
            gap: 15px;
            margin-bottom: 15px;
        }

        .social-icon {
            width: 32px;
            height: 32px;
            transition: transform 0.3s ease;
        }

        .social-icon:hover {
            transform: scale(1.1);
        }

        .copyright {
            font-size: 14px;
            margin-top: 15px;
        }

        @media (max-width: 480px) {
            .main-content {
                padding: 20px;
            }

            .greeting {
                font-size: 20px;
            }

            .social-media {
                gap: 10px;
            }

            .header {
                height: 200px;
            }

            .whatsapp-button {
                padding: 10px 20px;
                font-size: 14px;
            }

            .button-container {
                left: 5%;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <header class="header">
            <img src="{{ asset('email/banner.webp') }}" alt="Banner Yuntas" class="banner-background">
            <div class="header-overlay"></div>
            <div class="button-container">
                <a href="https://wa.me/05192849782" class="whatsapp-button">COTIZA AHORA O ASESÓRATE AQUÍ</a>
            </div>
        </header>

        <main class="main-content">
            <h1 class="greeting">Hola {{ $data['name'] }},</h1>
            <p class="message">Aquí no solo encontrarás productos, sino ideas, inspiración y un equipo listo para transformar tus espacios en experiencias únicas 🚀.</p>
            <p class="message">Prepárate para recibir novedades, beneficios y sorpresas exclusivas muy pronto.</p>
            <p class="message">Si tienes alguna idea o proyecto en mente, no dudes en escribirnos:</p>

            <div class="contact-info">
                <p class="contact-item">💬 Contáctanos directamente por WhatsApp: <a href="https://wa.me/05192849782" class="whatsapp-link">https://wa.me/05192849782</a></p>
                <p class="contact-item">👉 Síguenos en nuestras redes sociales:</p>
            </div>
        </main>

        <footer class="footer">
            <div class="social-media">
                <a href="https://www.facebook.com/YuntasProducciones" target="_blank" rel="noopener noreferrer"><img src="https://cdn-icons-png.flaticon.com/512/733/733547.png" alt="Facebook" class="social-icon"></a>
                <a href="https://www.instagram.com/yuntasproducciones/?hl=es" target="_blank" rel="noopener noreferrer"><img src="https://cdn-icons-png.flaticon.com/512/174/174855.png" alt="Instagram" class="social-icon"></a>
                <a href="https://www.tiktok.com/@yuntasproducciones" target="_blank" rel="noopener noreferrer"><img src="https://cdn-icons-png.flaticon.com/512/3046/3046126.png" alt="TikTok" class="social-icon"></a>
                <a href="https://www.youtube.com/@yuntasproducciones5082/videos" target="_blank" rel="noopener noreferrer"><img src="https://cdn-icons-png.flaticon.com/512/1384/1384060.png" alt="YouTube" class="social-icon"></a>
            </div>
            <p class="copyright">&copy; 2025 Yuntas - Todos los derechos reservados.</p>
        </footer>
    </div>
</body>

</html>