<?php

/**
 * =====================================================
 * M.A.R.I.A - BACKEND DEL CHATBOT CON IA
 * =====================================================
 * Sistema de IA con seguridad robusta y respuestas contextuales
 * =====================================================
 */

require_once 'includes/session.php';
require_once 'includes/conn.php';
require_once 'maria_config.php';

// Asegurar conexión a la base de datos (usar $conn, no sobreescribir $pdo)
$conn = $pdo->open();

header('Content-Type: application/json');

// =====================================================
// VALIDACIONES INICIALES
// =====================================================

// Verificar método POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Método no permitido']);
    exit;
}

// Verificar que M.A.R.I.A esté habilitada
if (!MARIA_ENABLED) {
    echo json_encode(['error' => 'El servicio no está disponible en este momento. Por favor, contáctanos por WhatsApp.']);
    exit;
}

// =====================================================
// OBTENER Y VALIDAR DATOS
// =====================================================

$input = json_decode(file_get_contents('php://input'), true);
$userMessage = trim($input['message'] ?? '');
$currentPage = trim($input['page'] ?? 'index.php');
$currentProduct = trim($input['product'] ?? '');
$currentCategory = trim($input['category'] ?? '');
$currentTitle = trim($input['title'] ?? ''); // Nuevo: Título de la página

// Validar mensaje no vacío
if (empty($userMessage)) {
    echo json_encode(['error' => 'Mensaje vacío']);
    exit;
}

// Validar longitud del mensaje
if (strlen($userMessage) > MARIA_MAX_MESSAGE_LENGTH) {
    echo json_encode(['error' => 'Mensaje demasiado largo. Máximo ' . MARIA_MAX_MESSAGE_LENGTH . ' caracteres.']);
    exit;
}

// =====================================================
// VERIFICAR RATE LIMITING
// =====================================================

$userId = $_SESSION['user'] ?? null;

if (!checkRateLimit($userId)) {
    echo json_encode([
        'response' => '⏱️ Has enviado muchos mensajes recientemente. Por favor, espera unos minutos antes de continuar.'
    ]);
    exit;
}

// =====================================================
// SEGURIDAD: DETECTAR INTENCIONES MALICIOSAS
// =====================================================

if (detectMaliciousIntent($userMessage)) {
    logSecurityAttempt($userMessage, $userId);

    echo json_encode([
        'response' => '⚠️ Por tu seguridad y la nuestra, no puedo procesar ese tipo de consultas. ¿Hay algo más en lo que pueda ayudarte? 😊'
    ]);
    exit;
}

// Sanitizar mensaje
$sanitizedMessage = sanitizeInput($userMessage);

// =====================================================
// OBTENER CONTEXTO DEL USUARIO
// =====================================================

$userContext = '';
$userName = 'Cliente';
$userType = 'visitante';

if (isset($_SESSION['user'])) {
    try {
        $stmt = $conn->prepare("SELECT firstname, type FROM users WHERE id = :id");
        $stmt->execute(['id' => $_SESSION['user']]);
        $userData = $stmt->fetch();

        if ($userData) {
            $userName = $userData['firstname'];
            $userType = $userData['type'];
            $userContext = "Usuario: {$userName} ({$userType})";
        }
    } catch (PDOException $e) {
        error_log('Error al obtener datos de usuario: ' . $e->getMessage());
    }
}

// =====================================================
// FUNCIONES PARA OBTENER INFORMACIÓN DE PRODUCTOS
// =====================================================

/**
 * Obtener productos disponibles
 */
function getAvailableProducts()
{
    global $pdo, $conn;

    if (!$pdo) {
        error_log('No hay conexión a la base de datos');
        return "Lo siento, hay un problema de conexión. Inténtalo más tarde.";
    }

    try {
        // Verificar si la tabla existe (rápido)
        $stmt = $conn->prepare("SELECT 1 FROM information_schema.tables WHERE table_schema = DATABASE() AND table_name = 'products' LIMIT 1");
        $stmt->execute();
        if ($stmt->fetchColumn() === false) {
            error_log('La tabla products no existe');
            return "Lo siento, hay un problema con la base de datos.";
        }

        // Seleccionar solo columnas necesarias y limitar resultados para evitar carga
        $maxProducts = 50;
        $stmt = $conn->prepare("SELECT p.id, p.slug, p.name, p.price, p.stock, p.description, c.name as catname
                               FROM products p
                               LEFT JOIN category c ON c.id = p.category_id
                               WHERE p.stock > 0
                               ORDER BY p.id DESC
                               LIMIT :max");
        $stmt->bindParam(':max', $maxProducts, PDO::PARAM_INT);
        $stmt->execute();

        $productList = [];
        while ($row = $stmt->fetch()) {
            $price = number_format($row['price'], 2);
            $shortName = mb_strimwidth($row['name'], 0, 120, '...');
            // Usar placeholders [LINK:TYPE:SLUG|TEXT] que el frontend convertirá en <a>
            $productInfo = "🏷️ [LINK:product:{$row['slug']}|{$shortName}] - S/ {$price}\n";
            $productInfo .= "  Categoría: [LINK:category:{$row['catname']}|{$row['catname']}]\n";
            $productInfo .= "  Stock: {$row['stock']} unidades";
            $productList[] = $productInfo;
        }

        if (empty($productList)) {
            return "Lo siento, no hay productos disponibles en este momento.";
        }

        return "Estos son nuestros productos disponibles:\n\n" . implode("\n\n", $productList);
    } catch (PDOException $e) {
        error_log('Error al obtener productos: ' . $e->getMessage());
        return "Lo siento, hubo un error al consultar los productos. Por favor, intenta más tarde.";
    }
}

/**
 * Buscar producto específico
 */
function searchProduct($query)
{
    global $pdo, $conn;

    try {
        // Buscar con límite y seleccionando sólo las columnas necesarias
        $maxResults = 30;
        // Filtrar por stock > 0 para evitar recomendar productos sin stock
        $sql = "SELECT p.id, p.slug, p.name, p.price, p.stock, p.description, c.name as catname
                                     FROM products p
                                     LEFT JOIN category c ON c.id = p.category_id
                                     WHERE (LOWER(p.name) LIKE LOWER(:query)
                                         OR LOWER(p.description) LIKE LOWER(:query)
                                         OR LOWER(c.name) LIKE LOWER(:query))
                                        AND p.stock > 0
                                     LIMIT :max";

        $stmt = $conn->prepare($sql);

        $searchTerm = "%{$query}%";
        $stmt->bindParam(':query', $searchTerm, PDO::PARAM_STR);
        $stmt->bindParam(':max', $maxResults, PDO::PARAM_INT);
        $stmt->execute();

        $result = [];
        while ($row = $stmt->fetch()) {
            $price = number_format($row['price'], 2);
            $nameShort = mb_strimwidth($row['name'], 0, 80, '...');
            $productInfo = "🏷️ [LINK:product:{$row['slug']}|{$nameShort}]\n";
            $productInfo .= "💰 Precio: S/ {$price}\n";
            $productInfo .= "📦 Stock: {$row['stock']} unidades\n";
            $productInfo .= "🏷️ Categoría: [LINK:category:{$row['catname']}|{$row['catname']}]\n";
            $productInfo .= "📝 " . mb_strimwidth($row['description'], 0, 220, '...');
            $result[] = $productInfo;
        }

        if (empty($result)) {
            return "No encontré productos que coincidan con '{$query}'. ¿Quieres ver todos nuestros productos disponibles?";
        }

        return "Encontré estos productos:\n\n" . implode("\n\n", $result);
    } catch (PDOException $e) {
        error_log('Error al buscar productos: ' . $e->getMessage());
        return "Lo siento, hubo un error al buscar productos. Por favor, intenta más tarde.";
    }
}

/**
 * Obtener detalles de un producto por nombre/consulta (devuelve un string con información detallada)
 * Retorna false si no se encontró nada.
 */
function getProductDetailsByName($query)
{
    global $pdo, $conn;

    try {
        $stmt = $conn->prepare("SELECT p.id, p.slug, p.name, p.price, p.stock, p.description, c.name as catname
                                FROM products p
                                LEFT JOIN category c ON c.id = p.category_id
                                WHERE LOWER(p.name) LIKE LOWER(:q)
                                OR LOWER(p.slug) = LOWER(:slug)
                                LIMIT 1");

        $like = "%{$query}%";
        $slug = preg_replace('/[^a-z0-9\-]/i', '', strtolower($query));
        $stmt->bindParam(':q', $like, PDO::PARAM_STR);
        $stmt->bindParam(':slug', $slug, PDO::PARAM_STR);
        $stmt->execute();

        $row = $stmt->fetch();
        if (!$row) return false;

        $price = number_format($row['price'], 2);
        $stock = intval($row['stock']);
        $stockText = $stock > 0 ? "En stock: {$stock} unidades" : "Actualmente sin stock";

        $details = "🛍️ " . $row['name'] . "\n";
        $details .= "Categoría: " . ($row['catname'] ?? '—') . "\n";
        $details .= "Precio: S/ {$price}\n";
        $details .= "{$stockText}\n";
        if (!empty($row['description'])) {
            $details .= "\nDescripción:\n" . mb_strimwidth($row['description'], 0, 800, '...') . "\n";
        }

        $details .= "\nSi quieres, puedo recomendar alternativas similares con stock disponible. 😊";

        return $details;
    } catch (PDOException $e) {
        error_log('Error getProductDetailsByName: ' . $e->getMessage());
        return false;
    }
}

/**
 * Obtener productos disponibles por categoría (filtra stock > 0)
 */
function getProductsByCategory($category)
{
    global $pdo, $conn;

    try {
        $maxProducts = 50;
        $stmt = $conn->prepare("SELECT p.id, p.slug, p.name, p.price, p.stock, p.description
                               FROM products p
                               LEFT JOIN category c ON c.id = p.category_id
                               WHERE p.stock > 0 AND LOWER(c.name) LIKE LOWER(:cat)
                               ORDER BY p.id DESC
                               LIMIT :max");
        $likeCat = "%{$category}%";
        $stmt->bindParam(':cat', $likeCat, PDO::PARAM_STR);
        $stmt->bindParam(':max', $maxProducts, PDO::PARAM_INT);
        $stmt->execute();

        $productList = [];
        while ($row = $stmt->fetch()) {
            $price = number_format($row['price'], 2);
            $shortName = mb_strimwidth($row['name'], 0, 120, '...');
            $productInfo = "🏷️ [LINK:product:{$row['slug']}|{$shortName}] - S/ {$price}\n";
            $productInfo .= "  Stock: {$row['stock']} unidades";
            $productList[] = $productInfo;
        }

        if (empty($productList)) {
            return "Lo siento, no encontré productos disponibles en la categoría '{$category}' con stock en este momento.";
        }

        return "Recomendaciones en la categoría '{$category}':\n\n" . implode("\n\n", $productList);
    } catch (PDOException $e) {
        error_log('Error getProductsByCategory: ' . $e->getMessage());
        return "Lo siento, hubo un error al consultar los productos de la categoría. Inténtalo más tarde.";
    }
}

// =====================================================
// RESPUESTAS RÁPIDAS (sin usar API)
// =====================================================

function getQuickResponse($message)
{
    global $QUICK_RESPONSES;

    $messageLower = strtolower($message);

    // Patrones de preguntas comunes
    $patterns = [
        'productos|disponible|stock|tienen|catálogo' => 'productos',
        'kapok|fibra|material' => 'que_es_kapok',
        'beneficios|ventajas' => 'beneficios_kapok',
        'contacto|ubicación|teléfono|whatsapp' => 'contacto',
        'horario|atención' => 'horario',
        'envío|envíos|delivery' => 'envios',
        'pago|pagos|yape|plin' => 'metodos_pago',
        'empresa|conceiba' => 'sobre_conceiba',
        'impacto|social|comunidad' => 'impacto_social',
        'garantía|devolución|cambio' => 'garantia'
    ];

    foreach ($patterns as $pattern => $response) {
        if (preg_match("/\b({$pattern})\b/i", $messageLower)) {
            if ($response === 'productos') {
                return getAvailableProducts();
            }
            return $QUICK_RESPONSES[$response] ?? false;
        }
    }

    return false;
}

// Intentar respuesta rápida primero
$quickResponse = getQuickResponse($sanitizedMessage);

if ($quickResponse) {
    echo json_encode([
        'response' => $quickResponse,
        'timestamp' => date('Y-m-d H:i:s')
    ]);
    exit;
}

// =====================================================
// MANEJO LOCAL DE INTENCIONES IMPORTANTES (DETALLES / RECOMENDACIONES)
// =====================================================
// Si el usuario pide "más información" o "detalles" sobre un producto, intentar devolver la ficha localmente
if (preg_match('/(más información|mas información|más info|mas info|detalles|detalle|información|info)/i', $sanitizedMessage)) {
    $detail = getProductDetailsByName($sanitizedMessage);
    if ($detail) {
        echo json_encode([
            'response' => $detail,
            'timestamp' => date('Y-m-d H:i:s')
        ]);
        exit;
    }
}

// Si el usuario pide recomendaciones y hay categoría actual, priorizar productos de esa categoría con stock
if (preg_match('/(recomi[e|é]nda|qué me recomiendas|que me recomiendas|qué producto|que producto|recomendación|suger(e|í)encia)/i', $sanitizedMessage) && !empty($currentCategory)) {
    $recs = getProductsByCategory($currentCategory);
    if ($recs) {
        echo json_encode([
            'response' => $recs,
            'timestamp' => date('Y-m-d H:i:s')
        ]);
        exit;
    }
}

// =====================================================
// CONSTRUIR CONTEXTO PARA LA IA
// =====================================================

$productsInfo = getAvailableProducts();

// Buscar productos específicos si mencionan categorías
$specificProducts = '';
if (preg_match('/\b(sombrero|peluche|cojin|cojín|hilado|conejo|coneja|oso|decorativo|bordado)\b/i', $sanitizedMessage, $matches)) {
    $specificProducts = searchProduct($matches[0]);
}

// Contexto de página
$pageContext = "Página actual: {$currentPage}";
// Si se proporciona slug de producto, obtener detalles mínimos para contexto
$currentProductInfo = '';
if (!empty($currentProduct)) {
    try {
        $pstmt = $conn->prepare("SELECT p.slug, p.name, p.price, p.stock, p.description, c.name as catname FROM products p LEFT JOIN category c ON c.id = p.category_id WHERE p.slug = :slug LIMIT 1");
        $pstmt->execute(['slug' => $currentProduct]);
        $pinfo = $pstmt->fetch();
        if ($pinfo) {
            $price = number_format($pinfo['price'], 2);
            $currentProductInfo = "Producto actual: {$pinfo['name']} (S/ {$price}) — Categoría: {$pinfo['catname']} — Stock: {$pinfo['stock']} unidades.\n";
            $pageContext .= "\nViendo producto: {$pinfo['name']}";
        }
    } catch (PDOException $e) {
        error_log('Error al obtener info de producto actual: ' . $e->getMessage());
    }
}
// Si se pasó nombre de categoría en querystring, añadirlo
if (!empty($currentCategory)) {
    $pageContext .= "\nViendo categoría: {$currentCategory}";
}

switch ($currentPage) {
    case 'product.php':
        $pageContext .= "\nViendo detalles de un producto específico";
        break;
    case 'category.php':
        $pageContext .= "\nViendo productos por categoría";
        break;
    case 'cart_view.php':
        $pageContext .= "\nViendo el carrito de compras";
        break;
}

// =====================================================
// DEFINIR SYSTEM PROMPT
// =====================================================

$companyInfoStr = "";
foreach ($COMPANY_INFO as $key => $value) {
    $companyInfoStr .= "- " . ucfirst($key) . ": $value\n";
}

$systemPrompt = "Eres MARÍA (Modelo Avanzado de Respuesta e Interacción Automatizada), la asistente virtual amigable y profesional de Conceiba.
Tu objetivo es ayudar a los clientes a encontrar productos, responder dudas sobre envíos, pagos y la empresa.

INFORMACIÓN DE LA EMPRESA:
{$companyInfoStr}

CONTEXTO ACTUAL:
{$userContext}
{$pageContext}
- Título de la página: {$currentTitle}
{$currentProductInfo}

INSTRUCCIONES:
1. TU IDENTIDAD ES INNEGOCIABLE: SIEMPRE te llamas MARÍA. NUNCA uses otro nombre. Si te preguntan quién eres, responde SIEMPRE que eres MARÍA.
2. Responde de forma breve, amable y con emojis.
3. Si te preguntan por productos, usa la información disponible en 'PRODUCTOS DISPONIBLES' o 'PRODUCTOS ESPECÍFICOS'.
4. Si no sabes algo, sugiere contactar por WhatsApp.
5. NO inventes productos que no estén en la lista.

PRODUCTOS DISPONIBLES:
{$productsInfo}

{$specificProducts}
";

// =====================================================
// LLAMADA A LA API
// =====================================================

try {
    $ch = curl_init(OPENROUTER_API_URL);

    $data = [
        'model' => OPENROUTER_MODEL,
        'messages' => [
            ['role' => 'system', 'content' => $systemPrompt],
            ['role' => 'user', 'content' => $sanitizedMessage]
        ]
    ];

    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => json_encode($data),
        CURLOPT_HTTPHEADER => [
            'Content-Type: application/json',
            'Authorization: Bearer ' . OPENROUTER_API_KEY,
            'HTTP-Referer: http://localhost',
            'X-Title: Conceiba Chat'
        ],
        CURLOPT_TIMEOUT => API_TIMEOUT
    ]);

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

    // 1️⃣ Verificar error de conexión CURL
    if ($response === false || curl_errno($ch)) {
        $curlErr = curl_error($ch);
        @file_put_contents(__DIR__ . '/maria_debug.log', "[" . date('Y-m-d H:i:s') . "] CURL_ERROR: " . $curlErr . "\n", FILE_APPEND);
        $aiResponse = "❌ Error de conexión con la API: " . htmlspecialchars($curlErr);
    }

    // 2️⃣ Verificar código HTTP de respuesta
    elseif ($httpCode !== 200) {
        @file_put_contents(__DIR__ . '/maria_debug.log', "[" . date('Y-m-d H:i:s') . "] HTTP_CODE: " . $httpCode . " RESPONSE: " . substr($response, 0, 2000) . "\n", FILE_APPEND);
        $aiResponse = "❌ Error en la API: código HTTP " . $httpCode;
    }

    // 3️⃣ Decodificar respuesta JSON
    else {
        $responseData = json_decode($response, true);

        if (json_last_error() !== JSON_ERROR_NONE || empty($responseData)) {
            @file_put_contents(__DIR__ . '/maria_debug.log', "[" . date('Y-m-d H:i:s') . "] JSON_ERROR: " . json_last_error_msg() . "\n", FILE_APPEND);
            $aiResponse = "❌ Error: no se recibió una respuesta válida del modelo.";
        } elseif (isset($responseData['choices'][0]['message']['content'])) {
            // ✅ Limpiar y guardar respuesta del modelo
            $aiResponse = cleanMariaResponse($responseData['choices'][0]['message']['content']);
        } elseif (isset($responseData['error'])) {
            // ⚠️ Mostrar error legible
            $aiResponse = "❌ Error del modelo: " . cleanMariaResponse($responseData['error']['message'] ?? 'Error desconocido.');
        } else {
            $aiResponse = "❌ No se pudo obtener una respuesta válida del modelo.";
        }
    }
} catch (Exception $e) {
    // 4️⃣ Captura de excepciones
    error_log('Error en OpenRouter API: ' . $e->getMessage());
    @file_put_contents(__DIR__ . '/maria_debug.log', "[" . date('Y-m-d H:i:s') . "] EXCEPTION: " . $e->getMessage() . "\n", FILE_APPEND);
    $aiResponse = "😔 Lo siento, estoy experimentando dificultades técnicas. Por favor, contáctanos por WhatsApp: +51 945 472 993";
}

// =====================================================
// FALLBACK LOCAL SI LA API NO RESPONDE O DEVUELVE ERROR
// =====================================================
// Si la respuesta proveniente de la IA contiene errores detectables, intentar una respuesta local
if (isset($aiResponse) && (strpos($aiResponse, '❌') === 0 || stripos($aiResponse, 'Error') !== false || stripos($aiResponse, 'dificultades técnicas') !== false)) {
    @file_put_contents(__DIR__ . '/maria_debug.log', "[" . date('Y-m-d H:i:s') . "] FALLBACK_TRIGGERED: message='" . substr($sanitizedMessage, 0, 200) . "' aiResponse='" . substr($aiResponse, 0, 200) . "'\n", FILE_APPEND);

    // Intentar respuesta rápida local
    $local = getQuickResponse($sanitizedMessage);
    if ($local) {
        $aiResponse = $local . "\n\n(Respuesta desde el sistema local porque el servicio de IA no está disponible)";
    } else {
        // Si hay info de productos, agregarla como ayuda temporal
        $fallbackInfo = $productsInfo ?? '';
        // Intentar priorizar recomendaciones por categoría si existe
        if (!empty($currentCategory)) {
            $catRec = getProductsByCategory($currentCategory);
            if ($catRec) $fallbackInfo = $catRec;
        }

        // Mensaje claro al usuario
        $aiResponse = "Lo siento, no puedo conectar con el servicio de inteligencia artificial en este momento. " .
            "Pero puedo ofrecer información básica localmente:\n\n" . $fallbackInfo . "\n\nSi necesitas atención inmediata, contáctanos por WhatsApp: " . ($COMPANY_INFO['whatsapp'] ?? 'N/A');
    }
}


// =====================================================
// REGISTRAR LA INTERACCIÓN
// =====================================================

try {
    $stmt = $conn->prepare("INSERT INTO chat_history (user_id, message, response, created_at) VALUES (:user_id, :message, :response, NOW())");
    $stmt->execute([
        'user_id' => $userId,
        'message' => $sanitizedMessage,
        'response' => $aiResponse
    ]);
} catch (PDOException $e) {
    error_log('Error al registrar chat: ' . $e->getMessage());
}

// =====================================================
// ENVIAR RESPUESTA
// =====================================================

echo json_encode([
    'response' => $aiResponse,
    'timestamp' => date('Y-m-d H:i:s')
]);
