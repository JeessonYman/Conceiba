<?php
include '../maria_config.php';
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

$model = 'gemma3:1b';

// Prepare payload for Ollama (Local)
$data = [
    'model' => $model,
    'stream' => false,
    'messages' => [
        [
            'role' => 'system',
            'content' => 'Eres M.A.R.I.A (Modelo Avanzado de Respuesta e Interacción Automatizada), un asistente virtual experto en administración de empresas para Conceiba. 
            Conceiba es una empresa peruana de productos de kapok. 
            Tu tono es profesional, útil y amable. 
            Ayudas al administrador a entender métricas, gestionar inventario y ventas.
            Responde de manera concisa y en español.'
        ],
        [
            'role' => 'user',
            'content' => $message
        ]
    ]
];

// Call Ollama API (Local)
$ch = curl_init('http://localhost:11434/api/chat');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json'
]);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($httpCode === 200) {
    $result = json_decode($response, true);
    // Ollama response format: { "model": "...", "created_at": "...", "message": { "role": "assistant", "content": "..." }, ... }
    $reply = $result['message']['content'] ?? 'No pude procesar la respuesta.';
    echo json_encode(['reply' => $reply]);
} else {
    // Fallback error message
    echo json_encode(['reply' => 'Lo siento, no puedo conectar con mi cerebro local (Ollama). Asegúrate de que Ollama esté corriendo con el modelo gemma3:1b. (Error: ' . $httpCode . ')']);
}
