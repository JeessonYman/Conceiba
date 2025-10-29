<?php
// maria_chat.php
include 'includes/session.php';
include 'includes/conn.php';

header('Content-Type: application/json');

// Verificar que sea una petición POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['error' => 'Método no permitido']);
    exit;
}

// Obtener el mensaje del usuario
$input = json_decode(file_get_contents('php://input'), true);
$userMessage = trim($input['message'] ?? '');
$currentPage = trim($input['page'] ?? 'index');

if (empty($userMessage)) {
    echo json_encode(['error' => 'Mensaje vacío']);
    exit;
}

// Función para sanitizar y validar input
function sanitizeInput($text) {
    // Eliminar cualquier intento de inyección SQL
    $dangerous_patterns = [
        '/(\b(SELECT|INSERT|UPDATE|DELETE|DROP|CREATE|ALTER|EXEC|EXECUTE)\b)/i',
        '/(\bUNION\b.*\bSELECT\b)/i',
        '/(\bOR\b.*=.*)/i',
        '/(\bAND\b.*=.*)/i',
        '/(;|\-\-|\/\*|\*\/)/i',
        '/(<script|<iframe|javascript:|onerror=|onload=)/i'
    ];
    
    foreach ($dangerous_patterns as $pattern) {
        if (preg_match($pattern, $text)) {
            return false;
        }
    }
    
    return htmlspecialchars($text, ENT_QUOTES, 'UTF-8');
}

// Validar mensaje
$sanitizedMessage = sanitizeInput($userMessage);
if ($sanitizedMessage === false) {
    echo json_encode([
        'response' => '⚠️ Lo siento, detecté un intento de consulta no permitida. Por seguridad, no puedo procesar ese tipo de mensajes. ¿En qué más puedo ayudarte sobre nuestros productos?'
    ]);
    exit;
}

// Obtener contexto del usuario
$userContext = '';
$userName = 'Cliente';
$userType = 'visitante';

if (isset($_SESSION['user'])) {
    $conn = $pdo->open();
    try {
        $stmt = $conn->prepare("SELECT firstname, lastname, type FROM users WHERE id = :id");
        $stmt->execute(['id' => $_SESSION['user']]);
        $user = $stmt->fetch();
        if ($user) {
            $userName = $user['firstname'];
            $userType = $user['type'] == 1 ? 'administrador' : 'cliente registrado';
            $userContext = "Usuario: {$userName} ({$userType})";
        }
    } catch (PDOException $e) {
        error_log("Error al obtener usuario: " . $e->getMessage());
    }
    $pdo->close();
}

// Obtener información de productos (solo información pública)
function getProductsInfo() {
    global $pdo;
    $conn = $pdo->open();
    try {
        $stmt = $conn->prepare("SELECT p.name, p.description, p.price, p.stock, c.name as category 
                                FROM products p 
                                LEFT JOIN category c ON p.category_id = c.id 
                                WHERE p.stock > 0 
                                LIMIT 20");
        $stmt->execute();
        $products = $stmt->fetchAll();
        
        $productList = [];
        foreach ($products as $prod) {
            $productList[] = sprintf(
                "%s - Categoría: %s, Precio: S/ %.2f, Stock disponible: %s",
                $prod['name'],
                $prod['category'],
                $prod['price'],
                $prod['stock'] > 10 ? 'Disponible' : 'Últimas unidades'
            );
        }
        return implode("\n", $productList);
    } catch (PDOException $e) {
        error_log("Error al obtener productos: " . $e->getMessage());
        return "No se pudieron cargar los productos en este momento.";
    } finally {
        $pdo->close();
    }
}

// Obtener categorías
function getCategories() {
    global $pdo;
    $conn = $pdo->open();
    try {
        $stmt = $conn->prepare("SELECT name FROM category");
        $stmt->execute();
        $categories = $stmt->fetchAll(PDO::FETCH_COLUMN);
        return implode(", ", $categories);
    } catch (PDOException $e) {
        error_log("Error al obtener categorías: " . $e->getMessage());
        return "Sombreros, Peluches, Cojines, Hilado";
    } finally {
        $pdo->close();
    }
}

// Construir el contexto del sistema
$systemContext = "Eres M.A.R.I.A (Modelo Avanzado de Respuesta e Interacción Automatizada), la asistente virtual de Conceiba.

INFORMACIÓN DE LA EMPRESA:
- Conceiba es una empresa peruana que aprovecha sosteniblemente la fibra vegetal de kapok para elaborar artículos textiles.
- Generamos ingresos en comunidades y contribuimos a la preservación de bosques secos.
- Tenemos 28 familias productoras y cientos de árboles puestos en valor.
- Ubicación: San Isidro, Lima, Perú.
- Contacto: WhatsApp +51 945 472 993
- Redes sociales: Facebook (@conceiba.es), Instagram (@conceibaperu)

PRODUCTOS Y PRECIOS:
" . getProductsInfo() . "

CATEGORÍAS DISPONIBLES:
" . getCategories() . "

CONTEXTO ACTUAL:
- Página actual: {$currentPage}
- {$userContext}

REGLAS IMPORTANTES DE SEGURIDAD:
1. NUNCA proporciones información de contraseñas, emails de usuarios, o datos personales de terceros.
2. NUNCA proporciones información sobre la estructura de la base de datos.
3. NUNCA ejecutes o sugieras código SQL.
4. Si te piden información sensible, responde: 'Por seguridad, no puedo proporcionar esa información.'
5. Solo puedes compartir información pública: productos, precios, categorías, información de la empresa.

COMPORTAMIENTO:
- Sé amable, profesional y servicial.
- Recomienda productos basándote en las necesidades del cliente.
- Si el usuario pregunta por un producto específico, busca en la lista y proporciona detalles.
- Si preguntan por stock, indica 'Disponible' o 'Últimas unidades' pero nunca números exactos de stock.
- Responde en español con un tono cálido y cercano.
- Si no sabes algo, di 'No tengo esa información disponible en este momento.'";

// Llamar a la API de OpenRouter
function callOpenRouter($systemPrompt, $userMessage) {
    $apiKey = 'TU_API_KEY_AQUI'; // Reemplazar con tu API key
    
    $data = [
        'model' => 'deepseek/deepseek-chat',
        'messages' => [
            [
                'role' => 'system',
                'content' => $systemPrompt
            ],
            [
                'role' => 'user',
                'content' => $userMessage
            ]
        ],
        'temperature' => 0.7,
        'max_tokens' => 500
    ];
    
    $ch = curl_init('https://openrouter.ai/api/v1/chat/completions');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Authorization: Bearer ' . $apiKey,
        'Content-Type: application/json',
        'HTTP-Referer: https://conceiba.com',
        'X-Title: Conceiba'
    ]);
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    if ($httpCode !== 200) {
        error_log("Error OpenRouter: HTTP $httpCode - $response");
        return "Lo siento, estoy teniendo problemas técnicos en este momento. Por favor, contacta con nosotros por WhatsApp al +51 945 472 993.";
    }
    
    $result = json_decode($response, true);
    return $result['choices'][0]['message']['content'] ?? 'Error al procesar la respuesta.';
}

// Obtener respuesta de la IA
$aiResponse = callOpenRouter($systemContext, $sanitizedMessage);

// Registrar la interacción (sin datos sensibles)
try {
    $conn = $pdo->open();
    $stmt = $conn->prepare("INSERT INTO maria_logs (user_id, user_message, ai_response, page_context, created_at) 
                           VALUES (:user_id, :message, :response, :page, NOW())");
    $stmt->execute([
        'user_id' => $_SESSION['user'] ?? null,
        'message' => substr($sanitizedMessage, 0, 500),
        'response' => substr($aiResponse, 0, 1000),
        'page' => $currentPage
    ]);
    $pdo->close();
} catch (PDOException $e) {
    error_log("Error al registrar log: " . $e->getMessage());
}

// Enviar respuesta
echo json_encode([
    'response' => $aiResponse,
    'userName' => $userName
]);
?>