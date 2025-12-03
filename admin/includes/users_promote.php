<?php
// Habilitar reporte de errores para debugging
error_reporting(E_ALL);
ini_set('display_errors', 0); // No mostrar en pantalla, solo en log

// Log de debugging
$logFile = 'promote_debug.log';
file_put_contents($logFile, "\n=== " . date('Y-m-d H:i:s') . " ===\n", FILE_APPEND);
file_put_contents($logFile, "POST Data: " . print_r($_POST, true) . "\n", FILE_APPEND);

// Establecer header JSON desde el inicio
header('Content-Type: application/json');

$response = array('success' => false, 'message' => '');

try {
    // Iniciar sesión y validar que sea admin (evitar redirect desde session.php en peticiones AJAX)
    if(session_status() !== PHP_SESSION_ACTIVE) session_start();
    if(!isset($_SESSION['admin']) || trim($_SESSION['admin']) == '') {
        http_response_code(403);
        $response['message'] = 'No autorizado. Inicie sesión como administrador.';
        file_put_contents($logFile, "Unauthorized attempt. POST: " . print_r(
            
            
            $_POST, true) . "\n", FILE_APPEND);
        echo json_encode($response);
        exit();
    }

    // Incluir sólo la conexión (evitamos redirecciones/exit en session.php)
    $connPath = __DIR__ . '/../../includes/conn.php';
    if(!file_exists($connPath)) {
        throw new Exception('Archivo de conexión no encontrado: ' . $connPath);
    }
    include $connPath;

    // Verificar que $pdo existe
    if(!isset($pdo) || !method_exists($pdo, 'open')) {
        throw new Exception('Objeto $pdo no disponible tras incluir conn.php');
    }

    // Abrir conexión
    $conn = $pdo->open();

    if(isset($_POST['id'])) {
        $id = $_POST['id'];
        
        // Verificar que el usuario existe y es un cliente (type = 0)
        $stmt = $conn->prepare("SELECT * FROM users WHERE id=:id");
        $stmt->execute(['id' => $id]);
        $user = $stmt->fetch();
        
        if($user) {
            // Verificar que es un cliente (type = 0)
            if($user['type'] == 0) {
                // Actualizar el tipo de usuario a administrador (type = 1)
                $stmt = $conn->prepare("UPDATE users SET type=1 WHERE id=:id");
                $stmt->execute(['id' => $id]);
                
                $response['success'] = true;
                $response['message'] = 'Usuario promovido a administrador exitosamente';
                $_SESSION['success'] = 'Usuario '.$user['firstname'].' '.$user['lastname'].' promovido a administrador exitosamente';
            } else {
                $response['message'] = 'El usuario ya es administrador';
            }
        } else {
            $response['message'] = 'Usuario no encontrado';
        }
    } else {
        $response['message'] = 'ID de usuario no proporcionado';
    }
} catch(PDOException $e) {
    $msg = 'Error de base de datos: ' . $e->getMessage();
    file_put_contents($logFile, "PDOException: " . $e->getMessage() . "\n", FILE_APPEND);
    http_response_code(500);
    $response['message'] = $msg;
} catch(Exception $e) {
    $msg = 'Error: ' . $e->getMessage();
    file_put_contents($logFile, "Exception: " . $e->getMessage() . "\n", FILE_APPEND);
    http_response_code(500);
    $response['message'] = $msg;
}

$pdo->close();

echo json_encode($response);
exit();
?>