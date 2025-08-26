<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registro Exitoso - Yuntas</title>
</head>
<body>
    <main>
        <h1>Bienvenido, {{ $data['name'] }}!</h1>
        <p>Gracias por registrarte en Yuntas. Estamos encantados de tenerte con nosotros.</p>

        <div class="user-info">
            <h2>Información de tu cuenta:</h2>
            <p><strong>Email:</strong> {{ $data['email'] }}</p>
            <p><strong>Celular:</strong> {{ $data['celular'] }}</p>
        </div>

        <img src="{{ asset('email/correo-yuntas.webp') }}" alt="Correo de Registro de cliente en Yuntas">
    </main>
    <footer>
        <p>Si tienes alguna pregunta, no dudes en <a href="mailto:yuntasproducciones@gmail.com">contactarnos</a>.</p>
        <p>&copy; 2025 Yuntas - Todos los derechos reservados.</p>
    </footer>

</body>
</html>
