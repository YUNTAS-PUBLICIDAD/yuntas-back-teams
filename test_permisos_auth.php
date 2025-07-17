<?php

echo "=== PROBANDO SISTEMA DE PERMISOS EN PRODUCCIÓN ===\n";

$baseUrl = 'https://apiyuntas.yuntaspublicidad.com/api';

function makeAuthenticatedRequest($url, $method = 'GET', $data = null, $token = null) {
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

// 1. Intentar hacer login
echo "1. Intentando hacer login...\n";
echo "Por favor ingresa las credenciales:\n";

echo "Email: ";
$handle = fopen("php://stdin", "r");
$email = trim(fgets($handle));

echo "Password: ";
// En un script real, usarías una función para ocultar la contraseña
$password = trim(fgets($handle));
fclose($handle);

$loginData = [
    'email' => $email,
    'password' => $password,
    'device_name' => 'diagnostico-script'
];

$loginResult = makeAuthenticatedRequest("{$baseUrl}/v1/auth/login", 'POST', $loginData);

echo "Código HTTP: {$loginResult['http_code']}\n";

if ($loginResult['http_code'] == 200) {
    $loginResponse = json_decode($loginResult['response'], true);
    
    if (isset($loginResponse['data']['token'])) {
        $token = $loginResponse['data']['token'];
        echo "✅ Login exitoso! Token obtenido.\n";
        
        // 2. Obtener información del usuario
        echo "\n2. Obteniendo información del usuario...\n";
        $userResult = makeAuthenticatedRequest("{$baseUrl}/user", 'GET', null, $token);
        
        if ($userResult['http_code'] == 200) {
            $userResponse = json_decode($userResult['response'], true);
            
            if (isset($userResponse['data'])) {
                $userData = $userResponse['data'];
                echo "Usuario: {$userData['name']} ({$userData['email']})\n";
                
                if (isset($userData['roles']) && is_array($userData['roles'])) {
                    echo "Roles: ";
                    foreach ($userData['roles'] as $role) {
                        echo $role['name'] . " ";
                    }
                    echo "\n";
                }
                
                if (isset($userData['permissions']) && is_array($userData['permissions'])) {
                    echo "Permisos (" . count($userData['permissions']) . "):\n";
                    foreach ($userData['permissions'] as $permission) {
                        echo "- {$permission['name']}\n";
                    }
                }
                
                // 3. Intentar crear un producto de prueba
                echo "\n3. Intentando crear un producto de prueba...\n";
                
                $testProduct = [
                    'nombre' => 'Test Permisos',
                    'link' => 'test-permisos-' . time(),
                    'titulo' => 'Producto de Prueba Permisos',
                    'subtitulo' => 'Test',
                    'lema' => 'Testing permissions',
                    'descripcion' => 'Producto creado para probar permisos',
                    'stock' => 1,
                    'precio' => 100.00,
                    'seccion' => 'Test'
                ];
                
                // Para este test, solo probamos la validación sin archivos
                $productResult = makeAuthenticatedRequest("{$baseUrl}/v1/productos", 'POST', $testProduct, $token);
                
                echo "Código HTTP: {$productResult['http_code']}\n";
                
                if ($productResult['http_code'] == 422) {
                    echo "✅ La validación funciona (esperado por falta de imagen_principal)\n";
                    $validationResponse = json_decode($productResult['response'], true);
                    if (isset($validationResponse['errors'])) {
                        echo "Errores de validación:\n";
                        foreach ($validationResponse['errors'] as $field => $errors) {
                            echo "- {$field}: " . implode(', ', $errors) . "\n";
                        }
                    }
                } elseif ($productResult['http_code'] == 403) {
                    echo "❌ ERROR 403: Usuario no tiene permisos para crear productos\n";
                    echo "Respuesta: {$productResult['response']}\n";
                } elseif ($productResult['http_code'] == 201) {
                    echo "✅ Producto creado exitosamente! (No esperado sin imagen)\n";
                } else {
                    echo "❓ Respuesta inesperada: {$productResult['http_code']}\n";
                    echo "Respuesta: {$productResult['response']}\n";
                }
                
            } else {
                echo "❌ Error: No se pudo obtener datos del usuario\n";
                echo "Respuesta: {$userResult['response']}\n";
            }
        } else {
            echo "❌ Error obteniendo usuario: {$userResult['http_code']}\n";
            echo "Respuesta: {$userResult['response']}\n";
        }
        
    } else {
        echo "❌ Error: No se pudo obtener token del login\n";
        echo "Respuesta: {$loginResult['response']}\n";
    }
} else {
    echo "❌ Error en login: {$loginResult['http_code']}\n";
    echo "Respuesta: {$loginResult['response']}\n";
}

echo "\n=== DIAGNÓSTICO COMPLETADO ===\n";
