<?php
// Endpoint simple para que M.A.R.I.A interprete datos de los gráficos
header('Content-Type: application/json; charset=utf-8');
include_once __DIR__ . '/maria_data_helpers.php';
include_once __DIR__ . '/../maria_config.php';

$input = json_decode(file_get_contents('php://input'), true);
$question = isset($input['question']) ? trim($input['question']) : '';
$type = isset($input['type']) ? $input['type'] : 'sales_between';
$period = isset($input['period']) ? $input['period'] : 'month';
$date = isset($input['date']) ? $input['date'] : null;
$year = isset($input['year']) ? $input['year'] : null;

list($start, $end) = range_from_period($period, $date, $year);

$data = null;
switch ($type) {
    case 'top_products':
        $data = get_top_products_between($start, $end, 10);
        break;
    case 'category_sales':
        $data = get_category_sales_between($start, $end);
        break;
    case 'views_top':
        $data = get_product_views_top(10);
        break;
    case 'monthly_year':
        $data = get_monthly_series_year($year ?: date('Y'));
        break;
    case 'yearly_series':
        $data = get_yearly_series_last_n(5, $year ?: date('Y'));
        break;
    default:
        $data = get_daily_series($start, $end);
}

// Construir prompt básico
$summary = "Datos del periodo: {$start} a {$end}.\n";
if (isset($data['labels'])) {
    $summary .= "Etiquetas: " . implode(', ', array_slice($data['labels'], 0, 10)) . "\n";
}
if (isset($data['data'])) {
    $summary .= "Valores (muestra): " . implode(', ', array_slice($data['data'], 0, 10)) . "\n";
}

$reply = "";

// Intentar usar OpenRouter si hay API KEY
if (defined('OPENROUTER_API_KEY') && OPENROUTER_API_KEY && !empty(OPENROUTER_API_KEY)) {
    $prompt = "Eres M.A.R.I.A, asistente de Conceiba. Interpreta estos datos y responde en español de forma clara y breve. Pregunta: {$question}\n\nResumen de datos:\n" . $summary;

    $payload = [
        'model' => OPENROUTER_MODEL,
        'messages' => [
            ['role' => 'system', 'content' => 'Eres un asistente que interpreta informes de ventas y productos. Responde en español.'],
            ['role' => 'user', 'content' => $prompt]
        ],
        'max_tokens' => 500,
        'temperature' => 0.2
    ];

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, OPENROUTER_API_URL);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, API_TIMEOUT);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        'Authorization: Bearer ' . OPENROUTER_API_KEY
    ]);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
    $res = curl_exec($ch);
    $err = curl_error($ch);
    curl_close($ch);
    if ($res === false) {
        $reply = "No se pudo contactar al servicio de IA: $err";
    } else {
        $j = json_decode($res, true);
        if (isset($j['choices'][0]['message']['content'])) {
            $reply = $j['choices'][0]['message']['content'];
        } elseif (isset($j['choices'][0]['text'])) {
            $reply = $j['choices'][0]['text'];
        } else {
            $reply = "Respuesta inesperada del servicio de IA.";
        }
    }
} else {
    // Fallback: interpretación mínima local
    $replyLines = [];
    $replyLines[] = "Resumen del período {$start} a {$end}:";
    if (isset($data['data']) && is_array($data['data'])) {
        $total = array_sum($data['data']);
        $replyLines[] = "Total (suma de valores mostrados): S/ " . number_format($total, 2);
        $replyLines[] = "Media diaria (aprox): S/ " . number_format(count($data['data']) ? ($total / count($data['data'])) : 0, 2);
    }
    if (isset($data['labels']) && isset($data['data'])) {
        $maxIdx = array_keys($data['data'], max($data['data']))[0] ?? 0;
        $replyLines[] = "Pico registrado en: " . ($data['labels'][$maxIdx] ?? 'N/A') . " con " . ($data['data'][$maxIdx] ?? 0);
    }
    $reply = implode("\n", $replyLines);
}

echo json_encode(['ok' => true, 'answer' => cleanMariaResponse($reply)]);
