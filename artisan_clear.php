<?php

// Script para ejecutar comandos artisan directamente
// Sube este archivo al directorio raíz de Laravel (donde está artisan)

echo "<h2>🔧 Comandos Artisan - Limpieza Cache</h2>";

// Verificar si estamos en directorio correcto
if (!file_exists('artisan')) {
    echo "<p style='color: red;'>❌ Archivo 'artisan' no encontrado. Sube este archivo al directorio raíz de Laravel.</p>";
    exit;
}

echo "<p>✅ Archivo artisan encontrado</p>";

// Ejecutar comandos artisan
$commands = [
    'cache:clear' => 'Limpiar cache general',
    'config:clear' => 'Limpiar cache de configuración', 
    'route:clear' => 'Limpiar cache de rutas',
    'view:clear' => 'Limpiar cache de vistas'
];

echo "<h3>Ejecutando comandos:</h3>";

foreach ($commands as $command => $description) {
    echo "<p><strong>$description:</strong> ";
    
    $output = [];
    $return_var = 0;
    
    // Ejecutar comando artisan
    exec("php artisan $command 2>&1", $output, $return_var);
    
    if ($return_var === 0) {
        echo "<span style='color: green;'>✅ Exitoso</span></p>";
    } else {
        echo "<span style='color: red;'>❌ Error</span></p>";
        echo "<pre>" . implode("\n", $output) . "</pre>";
    }
}

// Limpiar cache específico de permisos usando PHP
echo "<h3>Limpiando cache de permisos:</h3>";

try {
    require_once 'vendor/autoload.php';
    $app = require_once 'bootstrap/app.php';
    $kernel = $app->make('Illuminate\Contracts\Console\Kernel');
    $kernel->bootstrap();
    
    // Limpiar cache de permisos de Spatie
    app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
    echo "<p style='color: green;'>✅ Cache de permisos de Spatie limpiado</p>";
    
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ Error limpiando cache de permisos: " . $e->getMessage() . "</p>";
}

echo "<h3>🎉 LIMPIEZA COMPLETADA</h3>";
echo "<p style='color: red;'><strong>ELIMINA ESTE ARCHIVO INMEDIATAMENTE por seguridad.</strong></p>";

?>
