<?php

echo "=== PROBANDO ENDPOINTS DE DIAGNÓSTICO EN PRODUCCIÓN ===\n";
echo "Fecha: " . date('Y-m-d H:i:s') . "\n\n";

$baseUrl = 'https://apiyuntas.yuntaspublicidad.com/api';

// Función para hacer peticiones HTTP
function makeRequest($url, $method = 'GET', $data = null) {
    $ch = curl_init();
    
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36');
    
    if ($method === 'POST') {
        curl_setopt($ch, CURLOPT_POST, true);
        if ($data) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Content-Type: application/json',
                'Accept: application/json'
            ]);
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

// 1. Probar endpoint de diagnóstico
echo "1. Probando endpoint de diagnóstico...\n";
echo "URL: {$baseUrl}/diagnostico-permisos\n";

$result = makeRequest("{$baseUrl}/diagnostico-permisos");

echo "Código HTTP: {$result['http_code']}\n";

if ($result['error']) {
    echo "Error de cURL: {$result['error']}\n";
} else {
    echo "Respuesta recibida:\n";
    if ($result['http_code'] == 200) {
        $jsonResponse = json_decode($result['response'], true);
        if ($jsonResponse && isset($jsonResponse['diagnostico'])) {
            echo $jsonResponse['diagnostico'] . "\n";
        } else {
            echo "Respuesta no es JSON válido o no tiene el campo 'diagnostico':\n";
            echo $result['response'] . "\n";
        }
    } else {
        echo "Respuesta de error:\n";
        echo $result['response'] . "\n";
    }
}

echo "\n" . str_repeat("=", 60) . "\n\n";

// 2. Si el diagnóstico funciona, preguntar si queremos reparar
if ($result['http_code'] == 200) {
    echo "2. ¿Quieres ejecutar la reparación automática? (s/n): ";
    $handle = fopen("php://stdin", "r");
    $input = trim(fgets($handle));
    fclose($handle);
    
    if (strtolower($input) === 's' || strtolower($input) === 'si') {
        echo "\nEjecutando reparación...\n";
        echo "URL: {$baseUrl}/reparar-permisos\n";
        
        $repairResult = makeRequest("{$baseUrl}/reparar-permisos", 'POST');
        
        echo "Código HTTP: {$repairResult['http_code']}\n";
        
        if ($repairResult['error']) {
            echo "Error de cURL: {$repairResult['error']}\n";
        } else {
            if ($repairResult['http_code'] == 200) {
                $jsonResponse = json_decode($repairResult['response'], true);
                if ($jsonResponse && isset($jsonResponse['reparacion'])) {
                    echo "Reparación completada:\n";
                    echo $jsonResponse['reparacion'] . "\n";
                } else {
                    echo "Respuesta de reparación no válida:\n";
                    echo $repairResult['response'] . "\n";
                }
            } else {
                echo "Error en reparación:\n";
                echo $repairResult['response'] . "\n";
            }
        }
    } else {
        echo "Reparación no ejecutada.\n";
    }
} else {
    echo "No se puede ejecutar reparación porque el diagnóstico falló.\n";
}

echo "\n=== PRUEBA COMPLETADA ===\n";
