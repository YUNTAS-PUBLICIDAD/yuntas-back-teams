<?php

// Script temporal para limpiar cache después de cambios en permisos
// Subir a public_html/back/ y ejecutar visitando: tu-dominio.com/back/limpiar_cache.php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$kernel = $app->make('Illuminate\Contracts\Console\Kernel');
$kernel->bootstrap();

echo "<h2>Limpiando cache de permisos...</h2>";

try {
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
    
    echo "<h3>🎉 Cache limpiado exitosamente!</h3>";
    echo "<p><strong>IMPORTANTE:</strong> Elimina este archivo por seguridad después de usarlo.</p>";
    
} catch (Exception $e) {
    echo "<p>❌ Error: " . $e->getMessage() . "</p>";
}

?>
