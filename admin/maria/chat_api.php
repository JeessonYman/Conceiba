<?php
include '../../maria_config.php';
header('Content-Type: application/json');

// Get input
$input = json_decode(file_get_contents('php://input'), true);
$message = $input['message'] ?? '';

if (empty($message)) {
    echo json_encode(['error' => 'No message provided']);
    exit;
}

// Basic mock response for now, to be replaced with actual OpenRouter call
// In a real implementation, we would use curl to call the API defined in config
// For this task, I will implement a basic curl call if the key is present, or a simulation.

if (!defined('MARIA_ENABLED') || !MARIA_ENABLED) {
    echo json_encode(['reply' => 'M.A.R.I.A está desactivada en la configuración.']);
    exit;
}

$apiKey = defined('OPENROUTER_API_KEY') ? OPENROUTER_API_KEY : '';
$model = defined('OPENROUTER_MODEL') ? OPENROUTER_MODEL : 'gemma3:1b';

if (empty($apiKey)) {
    echo json_encode(['reply' => 'Error: API Key no configurada. Por favor configura M.A.R.I.A en el panel de administración.']);
    exit;
}

// Prepare payload for OpenRouter
$data = [
    'model' => $model,
    'messages' => [
        [
            'role' => 'system',
            'content' => 'Eres M.A.R.I.A, un asistente virtual experto en administración de empresas para Conceiba. 
            Conceiba es una empresa peruana de productos de kapok. 
            Tu tono es profesional, útil y amable. 
            Ayudas al administrador a entender métricas, gestionar inventario y ventas.
            Responde de manera concisa.'
        ],
        [
            'role' => 'user',
            'content' => $message
        ]
    ]
];

// Call API
$ch = curl_init('https://openrouter.ai/api/v1/chat/completions');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json',
    'Authorization: Bearer ' . $apiKey,
    'HTTP-Referer: https://conceiba.com', // Required by OpenRouter
    'X-Title: Conceiba Admin'
]);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($httpCode === 200) {
    $result = json_decode($response, true);
    $reply = $result['choices'][0]['message']['content'] ?? 'No pude procesar la respuesta.';
    echo json_encode(['reply' => $reply]);
} else {
    // Fallback for demo/testing if API fails or key is invalid
    echo json_encode(['reply' => 'Lo siento, hubo un problema conectando con mi cerebro digital. (API Error: ' . $httpCode . ')']);
}
