<?php
// Script simple para limpiar cache y mostrar información del servidor
// Subir directamente a la carpeta raíz donde está index.php

echo "<h2>🔧 Limpieza de Cache Laravel - Yuntas</h2>";
echo "<p><strong>Ruta actual:</strong> " . __DIR__ . "</p>";
echo "<p><strong>Archivos en directorio:</strong></p>";
echo "<ul>";
foreach (scandir('.') as $file) {
    if ($file != '.' && $file != '..') {
        echo "<li>$file</li>";
    }
}
echo "</ul>";

// Intentar encontrar la aplicación Laravel
$possible_paths = [
    __DIR__,
    __DIR__ . '/back',
    __DIR__ . '/../',
    __DIR__ . '/../../'
];

$laravel_found = false;
$app_path = '';

foreach ($possible_paths as $path) {
    if (file_exists($path . '/vendor/autoload.php') && file_exists($path . '/bootstrap/app.php')) {
        $laravel_found = true;
        $app_path = $path;
        break;
    }
}

echo "<hr>";

if ($laravel_found) {
    echo "<h3>✅ Laravel encontrado en: $app_path</h3>";
    
    try {
        // Cargar Laravel
        require_once $app_path . '/vendor/autoload.php';
        $app = require_once $app_path . '/bootstrap/app.php';
        $kernel = $app->make('Illuminate\Contracts\Console\Kernel');
        $kernel->bootstrap();
        
        echo "<h3>🚀 Limpiando caches...</h3>";
        
        // Limpiar cache de permisos de Spatie
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
        echo "<p>✅ Cache de permisos limpiado</p>";
        
        // Limpiar otros caches
        \Illuminate\Support\Facades\Artisan::call('cache:clear');
        echo "<p>✅ Cache general limpiado</p>";
        
        \Illuminate\Support\Facades\Artisan::call('config:clear');
        echo "<p>✅ Cache de configuración limpiado</p>";
        
        \Illuminate\Support\Facades\Artisan::call('route:clear');
        echo "<p>✅ Cache de rutas limpiado</p>";
        
        echo "<h3>🎉 CACHE LIMPIADO EXITOSAMENTE!</h3>";
        echo "<p style='color: green;'><strong>Los permisos ahora deberían funcionar correctamente.</strong></p>";
        echo "<p style='color: red;'><strong>IMPORTANTE: Elimina este archivo inmediatamente por seguridad.</strong></p>";
        
    } catch (Exception $e) {
        echo "<p style='color: red;'>❌ Error: " . $e->getMessage() . "</p>";
    }
    
} else {
    echo "<h3>❌ Laravel no encontrado</h3>";
    echo "<p>Por favor, sube este archivo al directorio donde está tu aplicación Laravel.</p>";
    echo "<p>Busca el directorio que contenga los archivos: vendor/, bootstrap/, app/</p>";
}

echo "<hr>";
echo "<p><em>Script ejecutado: " . date('Y-m-d H:i:s') . "</em></p>";
?>
