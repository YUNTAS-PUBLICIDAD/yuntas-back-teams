<?php

echo "=== PRUEBA DIRECTA DE PERMISOS DE PRODUCTOS ===\n";

$baseUrl = 'https://apiyuntas.yuntaspublicidad.com/api';

function makeRequest($url, $method = 'GET', $data = null, $token = null) {
    $ch = curl_init();
    
    $headers = [
        'Content-Type: application/json',
        'Accept: application/json'
    ];
    
    if ($token) {
        $headers[] = 'Authorization: Bearer ' . $token;
    }
    
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    
    if ($method === 'POST') {
        curl_setopt($ch, CURLOPT_POST, true);
        if ($data) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        }
    }
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $error = curl_error($ch);
    
    curl_close($ch);
    
    return [
        'http_code' => $httpCode,
        'response' => $response,
        'error' => $error
    ];
}

// 1. Login
echo "1. Haciendo login...\n";
$loginData = [
    'email' => 'admin@gmail.com',
    'password' => 'admin',
    'device_name' => 'test-permisos'
];

$loginResult = makeRequest("{$baseUrl}/v1/auth/login", 'POST', $loginData);

if ($loginResult['http_code'] == 200) {
    $loginResponse = json_decode($loginResult['response'], true);
    $token = $loginResponse['data']['token'];
    echo "✅ Login exitoso\n";
    
    // 2. Probar crear producto (debería fallar por validación, no por permisos)
    echo "\n2. Probando crear producto...\n";
    $testProduct = [
        'nombre' => 'Test Diagnostico',
        'link' => 'test-diag-' . time(),
        'titulo' => 'Producto Test',
        'subtitulo' => 'Test',
        'lema' => 'Test lema',
        'descripcion' => 'Producto de prueba para verificar permisos',
        'stock' => 1,
        'precio' => 100.00,
        'seccion' => 'Test'
    ];
    
    $productResult = makeRequest("{$baseUrl}/v1/productos", 'POST', $testProduct, $token);
    
    echo "Código HTTP: {$productResult['http_code']}\n";
    
    if ($productResult['http_code'] == 403) {
        echo "🚨 PROBLEMA CONFIRMADO: Error 403 - Sin permisos\n";
        $error = json_decode($productResult['response'], true);
        echo "Mensaje: " . ($error['message'] ?? 'Sin mensaje') . "\n";
        echo "\n=== CAUSA DEL PROBLEMA ===\n";
        echo "El usuario admin no tiene los permisos necesarios en producción.\n";
        echo "SOLUCIÓN: Necesitas ejecutar el script de reparación de permisos.\n";
    } elseif ($productResult['http_code'] == 422) {
        echo "✅ PERMISOS FUNCIONAN: Error 422 - Validación (esperado)\n";
        $validation = json_decode($productResult['response'], true);
        echo "Errores de validación:\n";
        if (isset($validation['errors'])) {
            foreach ($validation['errors'] as $field => $errors) {
                echo "- {$field}: " . implode(', ', $errors) . "\n";
            }
        }
        echo "\n🎉 CONCLUSIÓN: Los permisos están funcionando correctamente!\n";
        echo "El error 422 es normal porque falta la imagen_principal requerida.\n";
    } elseif ($productResult['http_code'] == 201) {
        echo "✅ PRODUCTO CREADO: Permisos funcionan perfectamente\n";
        echo "🎉 CONCLUSIÓN: No hay problema de permisos!\n";
    } else {
        echo "❓ Respuesta inesperada: {$productResult['http_code']}\n";
        echo "Respuesta completa:\n{$productResult['response']}\n";
    }
    
} else {
    echo "❌ Error en login: {$loginResult['http_code']}\n";
    echo "Respuesta: {$loginResult['response']}\n";
}

echo "\n=== DIAGNÓSTICO COMPLETADO ===\n";
