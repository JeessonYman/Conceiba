<?php

/**
 * =====================================================
 * M.A.R.I.A - CONFIGURACIÓN Y SEGURIDAD
 * =====================================================
 * Este archivo contiene toda la configuración de M.A.R.I.A
 * IMPORTANTE: Mantener este archivo seguro y no accesible públicamente
 * =====================================================
 */

// =====================================================
// CONFIGURACIÓN GENERAL
// =====================================================

// Activar/Desactivar M.A.R.I.A
define('MARIA_ENABLED', true);

// Límites de uso
define('MARIA_MAX_MESSAGE_LENGTH', 500);  // Máximo caracteres por mensaje
define('MARIA_RATE_LIMIT', 20);           // Mensajes máximos por hora
define('MARIA_SESSION_TIMEOUT', 3600);    // 1 hora en segundos

// =====================================================
// CONFIGURACIÓN DE API - OPENROUTER
// =====================================================

// 🔑 API key de OpenRouter
define('OPENROUTER_API_KEY', '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');

// Modelo de IA (DeepSeek v3.1)
define('OPENROUTER_MODEL', 'deepseek/deepseek-chat-v3-0324:free');

// URL de la API
define('OPENROUTER_API_URL', 'https://openrouter.ai/api/v1/chat/completions');

// Timeout de la API (segundos)
define('API_TIMEOUT', 30);

// =====================================================
// PATRONES DE SEGURIDAD
// =====================================================

$DANGEROUS_PATTERNS = [
    // Inyección SQL
    '/(\b(SELECT|INSERT|UPDATE|DELETE|DROP|CREATE|ALTER|EXEC|EXECUTE|UNION|HAVING|GROUP BY|ORDER BY)\b)/i',
    '/(\bUNION\b.*\bSELECT\b)/i',
    '/(\bOR\b\s*\d+\s*=\s*\d+)/i',
    '/(\bAND\b\s*\d+\s*=\s*\d+)/i',
    '/(;|\-\-|\/\*|\*\/|xp_|sp_)/i',

    // XSS (Cross-Site Scripting)
    '/(<script|<iframe|javascript:|onerror=|onload=|onclick=|onmouseover=)/i',
    '/(<img.*onerror|<svg.*onload)/i',
    '/(eval\(|document\.|window\.|alert\()/i',

    // Comandos de sistema
    '/(\b(eval|exec|system|passthru|shell_exec|`|proc_open|popen)\b)/i',

    // Path traversal
    '/(\.\.\/|\.\.\\\\|\.\.%2f|\.\.%5c)/i',

    // Intentos de obtener información sensible
    '/(\b(password|contraseña|clave|passwd|pwd)\b.*\b(obtener|dame|muestra|lista|ver|select)\b)/i',
];

// =====================================================
// PALABRAS CLAVE PROHIBIDAS
// =====================================================

$FORBIDDEN_KEYWORDS = [
    // Credenciales
    'password',
    'contraseña',
    'clave',
    'passwd',
    'pwd',

    // Información de usuarios
    'email de usuario',
    'correo de usuario',
    'email del cliente',
    'datos personales',
    'información personal',

    // Base de datos
    'base de datos',
    'database',
    'tabla',
    'table',
    'estructura',
    'schema',
    'sql',
    'query',

    // Administración
    'admin',
    'administrator',
    'administrador',
    'root',

    // Seguridad
    'token',
    'session',
    'cookie',
    'hash',
    'api key',
    'secret',
    'private',
    'encryption'
];

// =====================================================
// INFORMACIÓN PÚBLICA DE LA EMPRESA
// =====================================================

$COMPANY_INFO = [
    'nombre' => 'Conceiba',
    'descripcion' => 'Empresa peruana que aprovecha sosteniblemente la fibra vegetal de kapok para elaborar artículos textiles',
    'mision' => 'Generar ingresos en comunidades y contribuir a la preservación de bosques secos',
    'ubicacion' => 'San Isidro, Lima, Perú',
    'whatsapp' => '+51 945 472 993',
    'facebook' => '@conceiba.es',
    'instagram' => '@conceibaperu',
    'youtube' => '@conceiba',
    'website' => 'https://conceiba.com',
    'impacto' => [
        'familias_productoras' => 28,
        'arboles' => 'Cientos de árboles puestos en valor',
        'comunidades' => 'Trabajamos con comunidades locales'
    ]
];

// =====================================================
// CATEGORÍAS DE PRODUCTOS
// =====================================================

$PRODUCT_CATEGORIES = [
    'Sombreros' => 'Sombreros artesanales de kapok y ovino',
    'Peluches' => 'Peluches rellenos con fibra natural de kapok',
    'Cojines' => 'Cojines decorativos y bordados rellenos de kapok',
    'Hilado' => 'Hilado artesanal vegetal de kapok'
];

// =====================================================
// RESPUESTAS PREDEFINIDAS (Respuestas rápidas)
// =====================================================

$QUICK_RESPONSES = [
    'saludo' => '¡Hola! 👋 Soy M.A.R.I.A, tu asistente virtual de Conceiba. Estoy aquí para ayudarte con información sobre nuestros productos de kapok. ¿En qué puedo ayudarte?',

    'que_es_kapok' => 'El kapok es una fibra vegetal natural que proviene del árbol Ceiba (Ceiba pentandra). Es una fibra ecológica, hipoalergénica, antiácaros y muy ligera. La usamos para rellenar nuestros productos textiles de forma sostenible. 🌱',

    'beneficios_kapok' => 'Los beneficios de la fibra de kapok son: ✨ Natural y ecológica, 🌿 Hipoalergénica y antiácaros, 💨 Muy ligera y transpirable, ♻️ Sostenible y renovable, 🛡️ Resistente a la humedad. ¡Es perfecta para productos textiles naturales!',

    'contacto' => 'Puedes contactarnos por: 📱 WhatsApp: +51 945 472 993, 📘 Facebook: @conceiba.es, 📸 Instagram: @conceibaperu, 🎥 YouTube: @conceiba, 📍 Ubicación: San Isidro, Lima, Perú',

    'horario' => 'Puedes contactarnos en horario de oficina de lunes a viernes de 9:00 AM a 6:00 PM (hora de Perú). Para consultas urgentes, escríbenos por WhatsApp al +51 945 472 993.',

    'envios' => 'Realizamos envíos a todo el Perú. El costo y tiempo de entrega dependen de tu ubicación. Contáctanos por WhatsApp al +51 945 472 993 para cotizar el envío a tu ciudad.',

    'metodos_pago' => 'Aceptamos pagos por: 💳 PayPal (tarjetas internacionales), 🏦 Transferencia bancaria, 💰 Yape/Plin, 📱 Contáctanos por WhatsApp para coordinar otros métodos',

    'sobre_conceiba' => 'Conceiba es una empresa peruana comprometida con el medio ambiente. Trabajamos con 28 familias productoras, aprovechando sosteniblemente la fibra de kapok para crear productos textiles únicos y ecológicos. Contribuimos a la preservación de bosques secos en el Perú. 🌳✨',

    'impacto_social' => 'Nuestro impacto: 👨‍👩‍👧‍👦 28 familias productoras beneficiadas, 🌳 Cientos de árboles de kapok puestos en valor, 🌍 Preservación de bosques secos, 💚 Productos 100% ecológicos y sostenibles',

    'garantia' => 'Todos nuestros productos están hechos con fibra de kapok de alta calidad. Si tienes algún problema con tu compra, contáctanos dentro de los primeros 7 días para coordinar un cambio o devolución.'
];

// =====================================================
// FUNCIONES DE SEGURIDAD
// =====================================================

/**
 * Verificar rate limiting (límite de mensajes por hora)
 */
function checkRateLimit($userId = null)
{
    global $pdo;

    $identifier = $userId ?? $_SERVER['REMOTE_ADDR'];
    $timeWindow = time() - 3600; // Última hora

    try {
        $conn = $pdo->open();
        $stmt = $conn->prepare("SELECT COUNT(*) as count FROM maria_logs 
                               WHERE (user_id = :user_id OR :user_id IS NULL) 
                               AND created_at > FROM_UNIXTIME(:time_window)");
        $stmt->execute([
            'user_id' => $userId,
            'time_window' => $timeWindow
        ]);
        $result = $stmt->fetch();
        $pdo->close();

        return $result['count'] < MARIA_RATE_LIMIT;
    } catch (PDOException $e) {
        error_log("Error checking rate limit: " . $e->getMessage());
        return true; // Permitir en caso de error
    }
}

/**
 * Detectar intenciones maliciosas en el mensaje
 */
function detectMaliciousIntent($message)
{
    global $DANGEROUS_PATTERNS, $FORBIDDEN_KEYWORDS;

    // Verificar patrones peligrosos
    foreach ($DANGEROUS_PATTERNS as $pattern) {
        if (preg_match($pattern, $message)) {
            error_log("MARIA SECURITY: Malicious pattern detected - " . substr($message, 0, 100));
            return true;
        }
    }

    // Verificar palabras clave prohibidas en contexto sensible
    $messageLower = strtolower($message);
    foreach ($FORBIDDEN_KEYWORDS as $keyword) {
        if (strpos($messageLower, $keyword) !== false) {
            // Verificar si está pidiendo información sensible
            if (preg_match('/(\bcuál|\bcuales|\bqué|\bque|\bdime|\bmuestra|\bver|\blista|\bdame|\bconsulta|\bobtener)\b/i', $messageLower)) {
                error_log("MARIA SECURITY: Forbidden keyword in sensitive context - " . substr($message, 0, 100));
                return true;
            }
        }
    }

    return false;
}

/**
 * Sanitizar y validar input del usuario
 */
function sanitizeInput($text)
{
    // Eliminar espacios extras
    $text = trim($text);

    // Limitar longitud
    if (strlen($text) > MARIA_MAX_MESSAGE_LENGTH) {
        $text = substr($text, 0, MARIA_MAX_MESSAGE_LENGTH);
    }

    // Escapar HTML
    $text = htmlspecialchars($text, ENT_QUOTES, 'UTF-8');

    return $text;
}

/**
 * Registrar intento de seguridad
 */
function logSecurityAttempt($message, $userId = null)
{
    $logMessage = sprintf(
        "[%s] MARIA SECURITY - User: %s, IP: %s, Message: %s\n",
        date('Y-m-d H:i:s'),
        $userId ?? 'Guest',
        $_SERVER['REMOTE_ADDR'] ?? 'Unknown',
        substr($message, 0, 200)
    );

    error_log($logMessage);
}
/**
 * Limpiar la respuesta del modelo eliminando tokens especiales y formatos innecesarios
 */
function cleanMariaResponse($text)
{
    // Eliminar tokens o marcadores extraños
    $text = preg_replace('/<\|.*?\|>/', '', $text);
    $text = preg_replace('/<｜.*?｜>/', '', $text); // variación japonesa del token
    // Normalizar saltos de línea y espacios pero conservar saltos de línea
    // Reemplazar CRLF y CR por LF
    $text = str_replace(["\r\n", "\r"], "\n", $text);
    // Reemplazar múltiples espacios o tabs por uno (no tocar \n)
    $text = preg_replace('/[ \t]+/', ' ', $text);
    // Compactar más de 2 saltos de línea en máximo dos (para separar párrafos)
    $text = preg_replace('/\n{3,}/', "\n\n", $text);

    // Eliminar caracteres no imprimibles al inicio/fin, sin quitar saltos de línea internos
    $text = trim($text, " \t\n\r\0\x0B");

    // Mantener comillas; JSON encode en PHP manejará el escape correctamente
    return $text;
}

// =====================================================
// FIN DE CONFIGURACIÓN
// =====================================================
